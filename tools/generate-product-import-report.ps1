$ErrorActionPreference = 'Stop'

$root = 'E:\update-2022\Brod-art\email_photos'
$output = Join-Path $PSScriptRoot '..\docs\PRODUCT-IMPORT-REPORT.csv'
$imported = @{
    '14-ALANA'   = [PSCustomObject]@{ Product = 'Perdea Alana Crem T08'; ImportStatus = 'importat'; WooStatus = 'publish'; ProductId = '700'; Notes = 'Produs simplu; SKU 14-ALANA-T08.' }
    '14-ALEGRIA' = [PSCustomObject]@{ Product = 'Perdea Alegria din Colectia Scandi'; ImportStatus = 'importat partial'; WooStatus = 'draft (parinte), publish (variatii)'; ProductId = '728; variatii 729, 730'; Notes = 'SKU 14-ALEGRIA-T04 si 14-ALEGRIA-T05 confirmate in WooCommerce.' }
    '14-AMALFI'  = [PSCustomObject]@{ Product = 'Draperie Amalfi'; ImportStatus = 'importat partial'; WooStatus = 'draft'; ProductId = '747'; Notes = 'Colectia Jade este atribut separat; importate 16 imagini pentru V5, V8 si V13; V6 si V10 nu au fost confirmate in sursa publica.' }
}
$notFound = @{
    '14-ALFAMA' = [PSCustomObject]@{ Product = ''; ImportStatus = 'negasit'; WooStatus = 'nu exista'; ProductId = ''; Notes = 'Modelul nu a fost gasit in sitemap, cautarea sau paginile publice Mendola verificate.' }
}
$batchReportPath = Join-Path $PSScriptRoot '..\docs\PRODUCT-IMPORT-BATCH-REPORT.csv'
if (Test-Path -LiteralPath $batchReportPath) {
    $batchRows = Import-Csv -LiteralPath $batchReportPath -Delimiter ';' | Where-Object status -eq 'imported'
    foreach ($batchGroup in ($batchRows | Group-Object model_key)) {
        if (-not $imported.ContainsKey($batchGroup.Name)) {
            $imported[$batchGroup.Name] = [PSCustomObject]@{ Product = ''; ImportStatus = 'importat'; WooStatus = 'publish'; ProductId = ''; Notes = 'Importat prin batch dupa confirmarea paginii publice Mendola si a imaginilor locale.' }
        }
    }
}
$cachePath = Join-Path $PSScriptRoot '..\docs\PUBLIC-PRODUCT-PAGE-CACHE.json'
$publicModels = @{}
$publicSkus = @{}
if (Test-Path -LiteralPath $cachePath) {
    $cachedPages = Get-Content -Raw -LiteralPath $cachePath | ConvertFrom-Json
    foreach ($cachedPage in @($cachedPages)) {
        if ($cachedPage.url -match '/product/(?<slug>[^/]+)/') {
            $slug = $Matches['slug'].ToUpperInvariant()
            if ($slug -match '^(?<model>(?:14|149)-[A-Z0-9]+)') { $publicModels[$Matches['model']] = $true }
            if ($slug -match '^(?<sku>(?:14|149)-[A-Z0-9]+-[A-Z0-9]+)') { $publicSkus[$Matches['sku']] = $true }
        }
    }
}

$extensions = @('.jpg', '.jpeg', '.png', '.webp')
$files = Get-ChildItem -LiteralPath $root -File | Where-Object { $extensions -contains $_.Extension.ToLowerInvariant() } | Sort-Object Name
$rows = [System.Collections.Generic.List[object]]::new()
$groups = $files | Group-Object {
    if ($_.Name -match '^(?<code>(?:14|149)-[A-Za-z0-9]+)') { $Matches['code'].ToUpperInvariant() } else { 'NEIDENTIFICAT' }
} | Sort-Object Name

foreach ($group in $groups) {
    $code = $group.Name
    $known = $imported.ContainsKey($code)
    $missing = $notFound.ContainsKey($code)
    $details = if ($known) { $imported[$code] } elseif ($missing -or -not $publicModels.ContainsKey($code)) { [PSCustomObject]@{ Product = ''; ImportStatus = 'negasit'; WooStatus = 'nu exista'; ProductId = ''; Notes = 'Modelul sau varianta nu a fost gasita in paginile publice Mendola verificate.' } } else { [PSCustomObject]@{ Product = ''; ImportStatus = 'neimportat / neconfirmat'; WooStatus = ''; ProductId = ''; Notes = 'Exista imagini locale si o pagina de model, dar SKU-ul nu a fost importat.' } }
    $rows.Add([PSCustomObject]@{
            Tip              = 'produs'
            CodModel         = $code
            Produs           = $details.Product
            StareImport      = $details.ImportStatus
            StareWooCommerce = $details.WooStatus
            IDWooCommerce    = $details.ProductId
            Imagine          = ''
            HashSHA256       = ''
            Observatii       = "Imagini locale: $($group.Count). $($details.Notes)"
        })

    foreach ($file in $group.Group) {
        $hash = (Get-FileHash -LiteralPath $file.FullName -Algorithm SHA256).Hash
        $imageStatus = $details.ImportStatus
        $imageNotes = if ($missing) { 'Model negasit in sursa publica verificata.' } elseif ($code -eq '14-AMALFI' -and $file.Name -notmatch '^14-AMALFI-V(5|8|13)(_|\.)') { 'Varianta locala fara pagina publica confirmata; nu a fost importata.' } elseif ($known) { 'Fisier local asociat unui produs importat.' } else { 'Fisier local fara produs importat confirmat.' }
        $fileSku = if ($file.Name -match '^(?<sku>(?:14|149)-[A-Za-z0-9]+-[A-Za-z0-9]+)') { $Matches['sku'].ToUpperInvariant() } else { '' }
        if ($fileSku -and -not $publicSkus.ContainsKey($fileSku)) {
            $imageStatus = 'negasit'
            $imageNotes = 'Varianta nu a fost gasita in paginile publice Mendola verificate.'
        }
        if ($code -eq '14-AMALFI' -and $file.Name -notmatch '^14-AMALFI-V(5|8|13)(_|\.)') {
            $imageStatus = 'negasit'
        }
        if ($missing) {
            $imageStatus = 'negasit'
        }
        $rows.Add([PSCustomObject]@{
                Tip              = 'imagine'
                CodModel         = $code
                Produs           = $details.Product
                StareImport      = $imageStatus
                StareWooCommerce = $details.WooStatus
                IDWooCommerce    = $details.ProductId
                Imagine          = $file.Name
                HashSHA256       = $hash
                Observatii       = $imageNotes
            })
    }
}

$csv = $rows | ConvertTo-Csv -NoTypeInformation -Delimiter ';' | Out-String
Set-Content -LiteralPath $output -Value $csv -Encoding UTF8
Write-Output "Generated $output with $($rows.Count) rows from $($files.Count) images and $($groups.Count) product groups."
