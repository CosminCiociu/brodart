$ErrorActionPreference = 'Stop'
$root = 'E:\update-2022\Brod-art\email_photos'
$output = Join-Path $PSScriptRoot '..\docs\PUBLIC-PRODUCT-PAGE-CACHE.json'
$sitemap = (Invoke-WebRequest -Uri 'https://mendolafabrics.ro/wp-sitemap-posts-product-1.xml' -UseBasicParsing -TimeoutSec 30).Content
$urls = [regex]::Matches($sitemap, '(?is)<loc>(.*?)</loc>') | ForEach-Object { [System.Net.WebUtility]::HtmlDecode($_.Groups[1].Value) }
$models = Get-ChildItem -LiteralPath $root -File | Where-Object { $_.Extension -match '(?i)^\.(jpg|jpeg|png|webp)$' } | ForEach-Object { if ($_.Name -match '^(14|149)-[A-Z0-9]+') { $Matches[0].ToUpperInvariant() } } | Sort-Object -Unique
$targets = $urls | Where-Object {
    $url = $_
    $models | Where-Object {
        $token = $_ -replace '^(14|149)-', ''
        $url -match "(?i)/(?:14|149)-$([regex]::Escape($token))(?:-|/)"
    } | Select-Object -First 1
} | Sort-Object -Unique

$jobs = foreach ($url in $targets) {
    Start-Job -ArgumentList $url -ScriptBlock {
        param($target)
        try {
            $response = Invoke-WebRequest -Uri $target -UseBasicParsing -TimeoutSec 20 -Headers @{ 'User-Agent' = 'Brodart catalog importer/1.0' }
            [PSCustomObject]@{ url = $target; status = [int]$response.StatusCode; html = $response.Content }
        }
        catch {
            [PSCustomObject]@{ url = $target; status = 0; html = '' }
        }
    }
}
$results = Wait-Job -Job $jobs | Receive-Job
$results | Where-Object { $_.status -eq 200 -and $_.html } | ConvertTo-Json -Depth 4 | Set-Content -LiteralPath $output -Encoding UTF8
$jobs | Remove-Job -Force
Write-Output "Cached $(@($results | Where-Object status -eq 200).Count) of $($targets.Count) matching public product pages to $output."
