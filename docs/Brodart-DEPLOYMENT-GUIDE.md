# Brodart --- Ghid de implementare pentru dezvoltare locală și deployment

> **Scop:** configurează un flux sigur prin care Brodart este dezvoltat
> local în WordPress/XAMPP, verificat într-un mediu staging și publicat
> pe hostingul WebTeam cPanel.
>
> **Public țintă:** GitHub Copilot / Claude Code / dezvoltatorul
> proiectului.
>
> **Regulă critică:** nu suprascrie niciodată baza de date de producție
> cu baza de date locală fără o procedură explicită, backup verificat și
> aprobarea utilizatorului. Producția poate conține comenzi, clienți și
> alte date apărute după ultima sincronizare.

------------------------------------------------------------------------

## 1. Contextul proiectului

Proiectul este un magazin WordPress numit **Brodart**, dezvoltat local
pe Windows cu XAMPP.

Context cunoscut:

-   URL local: `http://localhost/brodart/`
-   Hosting public: WebTeam Concept, administrat prin cPanel, File
    Manager și phpMyAdmin.
-   Temă: Blocksy + Blocksy Companion.
-   E-commerce: WooCommerce.
-   Galerie: Modula.
-   Dezvoltare asistată de AI: GitHub Copilot / Claude Code.
-   Există documentație de proiect în `docs/`, inclusiv
    `STYLE-GUIDE.md`.
-   Designul urmărește un stil premium minimalist („quiet luxury"), cu
    imagini mari, spațiu alb, tipografie elegantă și interfețe discrete.
-   Proiectul poate include personalizări WooCommerce, precum
    configurarea produselor după dimensiuni și calcularea prețului.

**Nu presupune** că accesul SSH, Git Version Control în cPanel, WP-CLI
sau un anumit mecanism de deployment sunt disponibile. Verifică întâi și
propune o alternativă compatibilă cu File Manager, SFTP și phpMyAdmin.

## 2. Obiective

Construiește un flux cu trei medii:

1.  **Local / Development** --- mediul principal de lucru. Toate
    modificările se fac aici.
2.  **Staging** --- copie de test, protejată, folosită pentru validarea
    publicării.
3.  **Production** --- site-ul public. Nu se dezvoltă direct aici.

Fluxul normal:

``` text
Local development
       |
       | Git push / pregătire release
       v
Git repository
       |
       | Deploy cod + migrare selectivă de conținut
       v
Staging
       |
       | verificări și aprobare umană
       v
Production
```

Git versionnează codul și fișierele de configurare sigure. Git **nu**
este mecanismul de sincronizare pentru baza de date WordPress.

## 3. Principii obligatorii de siguranță

Respectă aceste reguli în toate scripturile, comenzile și recomandările:

1.  Nu modifica direct producția în timpul dezvoltării.
2.  Nu executa un import complet al bazei de date locale peste producție
    ca operațiune obișnuită.
3.  Nu șterge și nu suprascrie comenzile, clienții, conturile, sesiunile
    sau datele operaționale WooCommerce.
4.  Nu presupune că o copie staging poate fi promovată integral în
    producție: staging poate avea date mai vechi decât producția.
5.  Nu executa operațiuni distructive fără confirmare explicită a
    utilizatorului.
6.  Înainte de orice deployment, creează backup pentru fișierele care
    vor fi schimbate și pentru baza de date relevantă.
7.  Verifică existența și integritatea backupului înainte să continui.
8.  Nu păstra parole, tokenuri, chei API sau credențiale în Git,
    documentație publică ori loguri.
9.  Nu publica `wp-config.php`, `.env`, dump-uri SQL, arhive de backup,
    loguri sau fișiere temporare.
10. Nu dezactiva mecanismele de securitate ale site-ului fără motiv
    documentat și aprobare.
11. Dacă nu poți determina sigur ce date vor fi afectate, oprește
    operațiunea și cere clarificări.
12. Orice script de deployment trebuie să aibă mod de simulare
    (`dry-run`) unde este posibil.
13. Nu afirma că deploymentul a reușit până când verificările
    post-deployment nu au trecut.
14. Nu copia date reale ale clienților în medii de test fără minimizare,
    anonimizare și aprobare.
15. Nu trimite emailuri reale, nu procesa plăți reale și nu crea comenzi
    reale în staging.

## 4. Inspecție inițială --- înainte de modificări

Înainte să implementezi ceva:

1.  Inspectează structura reală a instalării XAMPP și identifică
    `DocumentRoot`, `wp-config.php`, `wp-content`, tema activă și
    pluginurile custom.
2.  Identifică versiunea WordPress, PHP, MySQL/MariaDB, WooCommerce,
    Blocksy și pluginurilor relevante.
3.  Identifică numele bazei de date locale și prefixul tabelelor din
    WordPress. Nu presupune că prefixul este `wp_`.
4.  Identifică dacă tema activă este Blocksy sau o temă child.
5.  Identifică toate personalizările existente și unde sunt
    implementate.
6.  Identifică pluginurile care gestionează filtrele, configuratorul de
    produse, prețurile și galeriile.
7.  Verifică ce funcționalități oferă planul WebTeam: SSH/Terminal,
    SFTP, Git Version Control, cron, WP-CLI, versiuni PHP și limite de
    upload.
8.  Nu afișa credențiale în terminal, rapoarte sau fișiere de
    documentație.
9.  Prezintă utilizatorului un raport de inspecție și lista de riscuri
    înainte de schimbări structurale.

Dacă o informație nu este cunoscută, marcheaz-o `NEVERIFICAT`; nu
inventa valori.

## 5. Arhitectura fișierelor

Nu muta sau reorganiza instalarea WordPress existentă doar pentru a
obține structura ideală dintr-un exemplu. Păstrează structura XAMPP
funcțională și versionnează selectiv componentele dezvoltate.

Structură logică recomandată pentru repository:

``` text
brodart/
├── docs/
│   ├── STYLE-GUIDE.md
│   ├── ARCHITECTURE.md
│   ├── DEPLOYMENT.md
│   ├── DATABASE-SYNC.md
│   └── RELEASE-CHECKLIST.md
├── deployment/
│   ├── config/
│   │   ├── local.example.json
│   │   ├── staging.example.json
│   │   └── production.example.json
│   ├── scripts/
│   │   ├── backup-local.ps1
│   │   ├── backup-remote.sh
│   │   ├── deploy-code.ps1
│   │   ├── deploy-code.sh
│   │   ├── export-content.ps1
│   │   ├── import-content.sh
│   │   ├── sync-media.ps1
│   │   └── verify-deployment.sh
│   └── manifests/
│       └── release.example.json
├── wp-content/
│   ├── themes/
│   │   └── brodart-child/
│   └── plugins/
│       └── brodart-custom/
├── .gitignore
└── README.md
```

Aceasta este structura repository-ului, nu o cerință de a duplica
WordPress Core. Dacă instalarea actuală are `wp-content` direct în
rădăcina `C:\xampp\htdocs\brodart`, folosește acea structură și
configurează Git să urmărească doar directoarele necesare.

### Reguli pentru cod

-   Preferă o temă child `brodart-child` pentru personalizările Blocksy.
-   Nu modifica direct fișierele temei Blocksy sau ale pluginurilor
    terțe; actualizările le pot suprascrie.
-   Pune funcționalitățile specifice Brodart într-un plugin propriu
    `brodart-custom`, dacă sunt independente de prezentare.
-   Nu copia pluginuri comerciale/licențiate în repository fără să
    verifici licența și politica de distribuție.
-   Nu versiona WordPress Core.
-   Nu versiona `uploads` în Git în mod implicit.
-   Nu versiona cache, loguri, arhive, dump-uri SQL sau fișiere
    generate.

## 6. Git și branch-uri

Configurează un repository Git privat.

Branch-uri propuse:

-   `develop` --- dezvoltare curentă.
-   `staging` --- versiunea candidat pentru testare.
-   `main` --- versiunea aprobată pentru producție.

Reguli:

1.  Lucrează în `develop` sau într-un feature branch.
2.  Fă commit-uri mici, cu mesaje clare.
3.  Promovează în `staging` doar modificări pregătite pentru testare.
4.  Promovează în `main` doar după aprobarea utilizatorului.
5.  Nu face deploy automat în producție doar pentru că s-a făcut
    `git push`.
6.  Folosește tag-uri pentru release-uri, de exemplu `v1.0.0`.
7.  Păstrează un istoric al release-urilor și al commit-urilor
    publicate.

### `.gitignore` minim orientativ

Adaptează-l la structura reală înainte de utilizare:

``` gitignore
# Secrets and environment configuration
wp-config.php
.env
.env.*
!.env.example
*.pem
*.key

# WordPress core
/wp-admin/
/wp-includes/

# Runtime and generated files
/wp-content/cache/
/wp-content/upgrade/
/wp-content/uploads/
/wp-content/backup*/
/wp-content/ai1wm-backups/
/wp-content/updraft/

# Logs and temporary files
*.log
*.tmp
*.sql
*.sql.gz
*.zip
.DS_Store
Thumbs.db

# Dependencies/build artifacts unless explicitly required
node_modules/
vendor/
```

Atenție: dacă un plugin sau tema custom necesită fișiere build (`dist`,
`build`) ori dependențe livrate, decide explicit dacă acestea trebuie
incluse. Nu exclude automat fișiere necesare funcționării pe hosting.

Înainte de primul commit, verifică:

``` bash
git status --short
git check-ignore -v wp-config.php
```

Nu adăuga niciodată credențiale sau dump-uri în repository.

## 7. Configurații pe medii

Local, staging și production trebuie să aibă:

-   baze de date separate;
-   URL-uri proprii;
-   credențiale proprii;
-   chei API și integrații proprii;
-   setări de email și plăți potrivite mediului.

Nu crea un singur fișier cu toate parolele. Folosește variabile de
mediu, configurații locale ignorate de Git sau mecanismul de configurare
disponibil pe hosting.

### Local

-   URL: `http://localhost/brodart/`
-   Debug: activ, doar local.
-   Plăți: sandbox/test.
-   Emailuri: capturate local sau dezactivate.
-   Indexare: dezactivată.

### Staging

-   URL: subdomeniu dedicat, de exemplu `staging.example.ro` (numele
    real trebuie confirmat).
-   Acces protejat prin autentificare HTTP sau alt mecanism sigur.
-   `noindex` și blocare acces public, unde este posibil.
-   Plăți sandbox.
-   Emailuri reale dezactivate sau redirecționate către o adresă de
    test.
-   Nu utiliza cheile API de producție.
-   Datele personale trebuie anonimizate/minimizate înainte de copierea
    datelor de producție în staging.

### Production

-   HTTPS obligatoriu.
-   Debug dezactivat.
-   Indexare permisă.
-   Plăți și emailuri reale doar cu credențiale de producție.
-   Backup automat și backup manual înainte de release.

Nu presupune că `WP_DEBUG` sau `DISALLOW_FILE_MODS` trebuie setate
printr-o anumită metodă până nu inspectezi configurația curentă.

## 8. Strategie pentru baza de date

Baza de date WordPress conține atât conținut și configurări, cât și date
operaționale. Nu toate tabelele pot fi sincronizate în același mod.

### 8.1 Clasificare

**Conținut care poate fi promovat selectiv:**

-   pagini și articole;
-   meniuri și elemente de navigare;
-   anumite tipuri de conținut;
-   produse WooCommerce, numai dacă utilizatorul solicită explicit și se
    verifică impactul;
-   setări de design exportabile;
-   opțiuni ale pluginurilor custom, dacă sunt identificate și exportate
    în mod sigur.

**Date operaționale care nu trebuie suprascrise din local:**

-   comenzi WooCommerce;
-   clienți și conturi;
-   adrese și date de facturare/livrare;
-   sesiuni și coșuri;
-   stocuri și modificări de inventar;
-   tranzacții și date de plată;
-   webhook-uri și cozi de procesare;
-   loguri operaționale;
-   date generate de extensii de facturare, curierat sau ERP.

Nu presupune că toate datele sunt în aceleași tabele. Versiunile
WooCommerce și extensiile pot folosi tabele custom, inclusiv tabele HPOS
pentru comenzi.

### 8.2 Prima migrare

Prima migrare completă este permisă doar când producția este nouă sau
când utilizatorul confirmă că poate fi înlocuită.

Pași:

1.  Creează backup complet al fișierelor și bazei de date locale.
2.  Creează backup al hostingului, chiar dacă pare gol.
3.  Confirmă baza de date și directorul de destinație.
4.  Transferă fișierele WordPress și `uploads`.
5.  Importă baza de date locală în baza de date de destinație.
6.  Configurează `wp-config.php` cu credențiale specifice serverului.
7.  Actualizează URL-urile cu WP-CLI `search-replace`, dacă este
    disponibil.
8.  Folosește `--dry-run` înainte de schimbarea URL-urilor.
9.  Verifică URL-urile, imaginile, linkurile, permalinks și paginile.
10. Testează autentificarea, produsele, filtrele, configuratorul și
    checkout-ul în mod test.
11. Verifică logurile PHP și WordPress.

Exemplu orientativ WP-CLI:

``` bash
wp search-replace \
  'http://localhost/brodart' \
  'https://example.ro' \
  --all-tables-with-prefix \
  --dry-run
```

După verificarea raportului, execută fără `--dry-run`. Înlocuiește
domeniul exemplu cu URL-ul real confirmat. Nu folosi SQL `REPLACE()`
simplu asupra tuturor câmpurilor: datele serializate pot fi corupte.

### Procedură manuală pentru staging prin cPanel și FTP

Context raportat de utilizator: staging este `http://brod-art.ro/wp`,
protejat, accesibil prin FTP și cPanel/phpMyAdmin, cu PHP 8.4; baza sa
este o clonă separată a bazei live. Aceste informații nu au fost
verificate independent. Procedura de mai jos nu a fost executată.

**Blocaj actual:** baza locală nu trece `wp db check` (47 de tabele au
erori InnoDB), iar dump-urile examinate nu sunt complete. Nu importa
niciunul dintre acele fișiere. Continuă numai cu un dump complet,
cunoscut ca bun și verificat într-o copie locală; backupurile păstrate
de utilizator nu au fost inspectate aici.

1. În cPanel, confirmă URL-ul HTTPS și document root-ul exact al
   staging-ului. Păstrează protecția cu parolă și `noindex`; nu folosi
   staging-ul dacă URL-ul rămâne HTTP pentru autentificare sau pentru
   date sensibile.
2. Confirmă versiunile WordPress, WooCommerce și MySQL/MariaDB remote.
   Compară-le cu sursa locală (WordPress 7.1.2, WooCommerce 11.1.2,
   PHP 8.2.12, HPOS activ) și validează compatibilitatea pluginurilor
   cu PHP 8.4.
3. Păstrează backupul verificat al staging-ului. Nu modifica baza clonă
   existentă; creează în cPanel o bază nouă, goală, și un utilizator
   dedicat cu privilegii numai pentru acea bază. Dacă limita contului nu
   permite o bază separată, oprește-te înainte să ștergi sau să imporți
   tabele.
4. Validează dump-ul local înainte de transfer: trebuie să conțină
   toate tabelele necesare cu prefixul sursei (`wp_`) și să poată fi
   importat într-o bază locală izolată fără erori. Nu folosi exportul
   local curent cât timp `wp db check` raportează erori.
5. Transferă prin cPanel File Manager HTTPS sau FTPS, nu FTP necriptat.
   În document root-ul confirmat, transferă fișierele WordPress și
   `wp-content`/`uploads`. Nu încărca `.git`, `.env`, dump-uri SQL,
   backupuri, loguri sau `wp-cli.phar`. Nu suprascrie `wp-config.php`
   remote cu cel local; păstrează configurația remote și schimbă numai
   valorile DB necesare pentru baza staging nouă.
6. În phpMyAdmin, selectează explicit baza staging nouă și goală și
   importă dump-ul complet validat. Nu importa într-o bază cu tabele
   existente și nu executa `DROP DATABASE`/ștergeri pentru a forța
   importul. Dacă fișierul depășește limita phpMyAdmin, folosește
   facilitatea Restore din cPanel sau suportul hostingului; nu împărți
   SQL-ul manual.
7. În `wp-config.php` remote, configurează credențialele bazei noi prin
   cPanel File Manager și păstrează `$table_prefix` care corespunde
   dump-ului. Nu copia credențialele în Git, documentație sau chat.
8. Înainte de login, setează `siteurl` și `home` în tabela
   `<prefix>_options` la URL-ul HTTPS staging confirmat. Apoi folosește
   un instrument compatibil cu serializarea WordPress pentru
   înlocuirea `http://localhost/brodart` cu URL-ul staging: rulează
   întâi simularea, inspectează rezultatele și abia apoi aplică
   înlocuirea. Nu folosi SQL `REPLACE()` brut.
9. Verifică protecția staging, `noindex`, `WP_ENVIRONMENT_TYPE=staging`,
   plățile în mod test și emailurile reale dezactivate. Testează paginile,
   produsele, variațiile, media, coșul și checkout-ul fără plată sau
   comandă reală.
10. Pentru rollback, păstrează baza clonă neatinsă și backupul fișierelor.
    Revino la document root/fișierele salvate și reconectează staging-ul
    la baza clonă numai dacă verificările eșuează. Nu restaura sau
    modifica baza live.

### 8.3 Promovare selectivă după lansare

Pentru schimbările obișnuite:

-   pagini/articole: export/import WXR sau o metodă de migrare selectivă
    care păstrează relațiile și metadatele;
-   produse: CSV WooCommerce sau API, cu mapare verificată;
-   media: transfer selectiv al fișierelor noi și verificarea
    atașamentelor;
-   meniuri: export/import sau migrare explicită;
-   setări Blocksy: folosește funcționalitatea de export/import
    disponibilă în versiunea instalată;
-   setări custom: creează export/import versionat pentru opțiunile
    strict identificate;
-   cod: Git deployment.

Nu presupune că un export WXR transferă toate setările pluginurilor,
toate opțiunile temei sau toate relațiile custom. Verifică separat
fiecare tip de conținut.

### 8.4 Conflict între local și producție

Dacă aceeași pagină sau aceeași setare a fost modificată și local, și pe
producție:

1.  Detectează conflictul, dacă mecanismul permite.
2.  Nu suprascrie automat.
3.  Prezintă diferențele.
4.  Cere utilizatorului să aleagă versiunea sau să combine manual
    modificările.
5.  Înregistrează rezultatul în manifestul release-ului.

### 8.5 Sincronizare producție → local

Această operațiune este pentru actualizarea mediului local cu datele
curente din producție, nu pentru publicarea localului.

Înainte de a copia date reale:

-   creează backup local;
-   folosește un dump controlat;
-   anonimizează datele personale și datele de contact;
-   înlocuiește cheile API și endpoint-urile de producție;
-   dezactivează emailurile și plățile reale;
-   nu aduce parole sau tokenuri de producție în fișierele locale.

Dacă nu există un proces sigur de anonimizare, nu importa datele reale
ale clienților în local.

## 9. Media și uploads

`wp-content/uploads` nu se versionnează în Git.

Implementează sincronizare media selectivă:

1.  Identifică fișierele noi/modificate.
2.  Compară dimensiunea și, preferabil, hash-ul.
3.  Transferă fișierele lipsă sau modificate.
4.  Nu șterge fișiere de pe producție doar pentru că lipsesc local.
5.  Nu suprascrie un fișier public fără backup sau verificarea
    referințelor.
6.  Verifică atașamentele și URL-urile din WordPress.

Pentru un magazin activ, producția poate primi imagini noi prin
administrare. Sincronizarea trebuie să fie controlată și să nu șteargă
media încă utilizată.

## 10. Deployment de cod

Verifică întâi dacă WebTeam cPanel oferă Git Version Control și
deployment prin `.cpanel.yml`.

### Dacă Git Deployment este disponibil

-   configurează repository-ul și deployment target;
-   publică doar directoarele aprobate;
-   exclude `wp-config.php`, uploads, cache și date;
-   folosește un script de deployment care creează backup;
-   nu executa automat migrații de bază de date în producție;
-   cere confirmare înainte de release-ul production.

### Dacă Git Deployment nu este disponibil

Folosește SFTP sau File Manager:

-   construiește o arhivă doar cu fișierele aprobate;
-   transferă arhiva;
-   verifică integritatea;
-   extrage în locația corectă;
-   păstrează backupul versiunii anterioare;
-   verifică permisiunile și funcționarea site-ului.

Nu publica întregul folder local și nu suprascrie fișierele de
configurare ale serverului.

## 11. Manifest de release

Fiecare release trebuie să aibă un manifest. Exemplu:

``` json
{
  "release": "1.0.0",
  "git_commit": "COMMIT_SHA",
  "target": "staging",
  "code": {
    "enabled": true,
    "paths": [
      "wp-content/themes/brodart-child",
      "wp-content/plugins/brodart-custom"
    ]
  },
  "content": {
    "enabled": false,
    "items": []
  },
  "settings": {
    "enabled": false,
    "items": []
  },
  "media": {
    "enabled": false,
    "items": []
  },
  "database_full_sync": false,
  "backup_required": true,
  "human_approval_required": true
}
```

Manifestul trebuie validat înainte de deployment. `target` trebuie să
fie `staging` sau `production`. Un release pentru production necesită
aprobare explicită.

Nu include secrete sau date personale în manifest.

## 12. Scripturi și comenzi

Implementează gradual scripturile de mai jos. Nu crea comenzi care
pretind că funcționează înainte să fie testate în mediul real.

  Script / comandă         Rol
  ------------------------ ----------------------------------------------
  `backup-local.ps1`       Backup local pentru fișiere și DB
  `backup-remote.sh`       Backup remote, dacă SSH/CLI este disponibil
  `deploy-code.ps1`        Publică fișierele aprobate din Windows
  `deploy-code.sh`         Publică fișierele aprobate pe un mediu Linux
  `export-content.ps1`     Exportă conținut selectat
  `import-content.sh`      Importă conținutul selectat, după validare
  `sync-media.ps1`         Transfer media selectiv
  `verify-deployment.sh`   Verificări post-deployment

Dacă SSH/WP-CLI nu sunt disponibile, oferă pași manuali prin cPanel,
SFTP și phpMyAdmin. Nu inventa capabilități.

Fiecare script trebuie să aibă:

-   validarea parametrilor;
-   verificarea mediului țintă;
-   jurnalizare fără secrete;
-   verificarea existenței fișierelor;
-   mod `dry-run`, unde este posibil;
-   oprire la erori;
-   cod de ieșire non-zero la eșec;
-   instrucțiuni de rollback.

Nu scrie scripturi care șterg recursiv directoare remote sau execută
importuri SQL destructive fără confirmare explicită și verificări.

## 13. Backup și rollback

Înainte de fiecare release:

-   backup al bazei de date production;
-   backup al directoarelor care vor fi modificate;
-   identificator unic al release-ului;
-   verificarea faptului că arhiva/dump-ul există și are dimensiune
    plauzibilă;
-   păstrarea unei copii în afara directorului public al site-ului, dacă
    hostingul permite.

Rollback:

1.  Oprește deploymentul dacă verificările eșuează.
2.  Restaurează fișierele din backup.
3.  Restaurează baza de date numai dacă release-ul a modificat-o și
    restaurarea este aprobată.
4.  Verifică site-ul.
5.  Înregistrează motivul și rezultatul rollback-ului.

Atenție: restaurarea unui backup vechi de bază de date peste un magazin
activ poate șterge comenzile și modificările recente. Pentru incidente
de cod, preferă rollback doar la cod, fără restaurarea DB.

## 14. Verificări post-deployment

După fiecare release, verifică:

### Tehnic

-   homepage răspunde cu HTTP 200;
-   paginile principale se încarcă;
-   nu există erori PHP fatale;
-   nu există erori JavaScript critice;
-   CSS și imaginile se încarcă;
-   HTTPS și redirecturile sunt corecte;
-   permalinks funcționează;
-   nu există linkuri către `localhost`.

### Brodart / WooCommerce

-   pagina Magazin;
-   filtrarea categoriilor și URL-urile filtrelor;
-   pagina de produs;
-   selectorul de dimensiuni;
-   calculul prețului;
-   variațiile și atributele;
-   coșul;
-   checkout în mod test;
-   emailurile de test;
-   paginile Contact și Galerie;
-   meniul desktop și mobil;
-   header, footer și responsive layout.

Nu efectua o comandă reală sau o plată reală în producție pentru testare
fără instrucțiunea explicită a utilizatorului.

## 15. Checklist de release

Înainte de staging:

-   [ ] Git working tree verificat.
-   [ ] Commit/tag identificat.
-   [ ] Manifest valid.
-   [ ] Nicio credențială în Git.
-   [ ] Backup disponibil.
-   [ ] Căile de deployment verificate.
-   [ ] Modificările DB/conținut sunt documentate.

Înainte de production:

-   [ ] Staging testat.
-   [ ] Aprobarea utilizatorului obținută.
-   [ ] Backup production verificat.
-   [ ] Datele operaționale sunt excluse din sincronizare.
-   [ ] Nu există migrare completă DB neaprobată.
-   [ ] Integrările sunt configurate pentru production.
-   [ ] Plan de rollback pregătit.

După production:

-   [ ] Homepage și paginile cheie verificate.
-   [ ] Magazinul și filtrele verificate.
-   [ ] Produsul și configuratorul verificate.
-   [ ] Checkout verificat fără plată reală neautorizată.
-   [ ] Logurile verificate.
-   [ ] Release-ul înregistrat.

## 16. Etape de implementare recomandate

Nu încerca să automatizezi totul într-un singur pas. Implementează în
ordinea următoare:

### Faza 1 --- Audit și backup

-   inspectează instalarea actuală;
-   documentează versiunile și pluginurile;
-   identifică personalizările;
-   creează backup local;
-   verifică restaurarea backupului într-o copie de test.

**Rezultat:** raport de audit și backup validat.

### Faza 2 --- Git

-   creează repository privat;
-   adaugă `.gitignore`;
-   versionează tema child, pluginurile custom și documentația;
-   verifică să nu existe secrete;
-   creează branch-urile.

**Rezultat:** codul custom este versionat în siguranță.

### Faza 3 --- Staging

-   verifică posibilitățile hostingului;
-   creează subdomeniu și bază de date separate;
-   instalează o copie de staging;
-   protejează staging și dezactivează emailurile/plățile reale;
-   verifică URL-urile și funcționalitățile.

**Rezultat:** mediu de test funcțional.

### Faza 4 --- Deployment de cod

-   configurează Git Deployment sau SFTP;
-   publică doar tema child și pluginurile custom;
-   implementează backup și rollback pentru fișiere;
-   testează mai întâi pe staging.

**Rezultat:** release de cod repetabil.

### Faza 5 --- Conținut și setări

-   testează export/import pentru o pagină simplă;
-   testează separat meniurile;
-   testează setările Blocksy;
-   testează un produs demonstrativ;
-   testează media;
-   documentează limitările și conflictele.

**Rezultat:** migrare selectivă, fără suprascrierea datelor
operaționale.

### Faza 6 --- Release production

-   rulează checklist-ul;
-   creează backup;
-   cere aprobarea utilizatorului;
-   publică;
-   verifică site-ul;
-   păstrează istoricul release-ului.

**Rezultat:** proces de publicare sigur și repetabil.

## 17. Cum trebuie să lucreze Copilot / Claude Code

Când primești această documentație:

1.  Începe cu auditul proiectului. Nu modifica nimic înainte de a
    înțelege structura existentă.
2.  Citește și respectă `docs/STYLE-GUIDE.md` și documentația existentă.
3.  Prezintă un plan de implementare adaptat structurii reale.
4.  Marchează toate necunoscutele despre hosting și acces.
5.  Implementează o singură fază odată.
6.  După fiecare fază, rulează verificările și prezintă fișierele
    schimbate.
7.  Nu inventa rezultate de test sau capabilități ale hostingului.
8.  Nu executa operațiuni distructive fără aprobare.
9.  Nu publica pe production fără confirmare explicită.
10. Actualizează această documentație când arhitectura reală diferă de
    plan.

### Formatul raportului după fiecare fază

``` text
FAZA: 1 — Audit local pentru migrare
STATUS: PARȚIALĂ — auditul local este complet; migrarea completă este blocată de erorile bazei locale și de preflight-ul remote neefectuat

CE AM INSPECTAT:
- Instalarea locală XAMPP, WordPress, WooCommerce, tabelele bazei de date și instrumentele/documentele de import existente.
- WordPress 7.1.2, PHP 8.2.12, WP-CLI 2.12.0, WooCommerce 11.1.2 și tema Blocksy 2.1.57.
- URL-ul local `http://localhost/brodart`, prefixul tabelelor `wp_`, moneda magazinului `RON` și HPOS activ.
- Snapshot-ul local al catalogului: 41 produse publicate, 12 produse draft, 79 variații publicate și 475 atașamente media.
- Nu există comenzi sau retururi în tabelele HPOS ori în tabelele clasice de comenzi ale bazei locale la momentul auditului.
- Importurile de catalog existente: raportul batch conține 80 SKU-uri importate în 41 grupuri de modele.
- Utilizatorul a indicat staging-ul `http://brod-art.ro/wp`, protecție activă, FTP și cPanel/phpMyAdmin disponibile, baza staging separată dar clonată din live și PHP 8.4. Aceste informații nu au fost verificate independent; versiunile WordPress, WooCommerce și MySQL remote sunt necunoscute.
- Utilizatorul a verificat în phpMyAdmin că tabelele reprezentative din clona staging sunt OK, a confirmat existența unui backup cPanel mai vechi și a confirmat că backupul complet staging a fost creat și verificat. Fișierele și datele de backup nu au fost accesate aici.
- DNS pentru `brod-art.ro` rezolvă la `81.196.152.62`; conexiunile TCP către porturile 21, 22 și 443 sunt accesibile, iar 990 nu răspunde. Hostname-ul FTPS `server1.webteamconcept.com` validează certificatul și rezolvă la același IP; confirmă cu hostingul că acesta este hostname-ul recomandat pentru FTP înainte să îl salvezi permanent.
- Utilizatorul a indicat inițial calea remote `/home/brodartr/public_html/wp` ca root WordPress staging; listarea FTPS a demonstrat că această cale nu este accesibilă ca atare din rădăcina virtuală a contului FTP.
- Inspecția TLS fără credențiale a explicat eroarea: `ftp.brod-art.ro` rezolvă la `81.196.152.62`, dar certificatul prezentat are subject `CN=autoconfig.server1.webteamconcept.com` și SAN-uri pentru hosturile `server1.webteamconcept.com`/`server11.webteamconcept.com`; eroarea este `RemoteCertificateNameMismatch`, fără eroare de chain. Certificatul este valabil între 29 octombrie 2026 și 27 ianuarie 2027.
- Același endpoint a trecut verificarea numelui certificatului prin `server1.webteamconcept.com`, care rezolvă la același IP. FTPS explicit cu TLS 1.2 și validarea normală a certificatului a reușit folosind credențialele din `.env`; comanda `PWD` a răspuns `257 "/" is your current location`.
- Captura cPanel arată `public_html` cu o instalare WordPress la rădăcină și un subdirector `wp`. În rădăcina virtuală FTPS `/` se văd markerii WordPress ai instalării de la rădăcină; subdirectorul `/wp/` este și el accesibil și conține `index.php`, `wp-admin`, `wp-content`, `wp-includes`, `wp-load.php`, `wp-config.php` și `.htaccess`. Directorul `/wp/wp-content/` conține `plugins`, `themes` și `uploads`, dar nu `mu-plugins`. Calea absolută `/home/brodartr/public_html/wp/` nu este validă în namespace-ul virtual FTP; calea relativă observată este `/wp/`. Numele listate nu dovedesc singure care instalare servește stagingul.

CE AM MODIFICAT:
- Am completat acest raport și am adăugat procedura manuală pentru staging prin cPanel/FTP. Am creat, cu acordul utilizatorului, o copie rece de siguranță a directorului MariaDB. Nu am modificat codul aplicației sau baza de date și nu am transferat fișiere.

TESTE EXECUTATE:
- Interogări WP-CLI read-only pentru versiuni, opțiuni, tabele și numărul de înregistrări, fără afișarea datelor personale.
- Verificarea rapoartelor CSV și a istoricului/documentației de import.
- `wp db check`: 125 tabele verificate, 78 OK și 47 cu eroarea `doesn't exist in engine` în grupurile Complianz (4), Imagify (2), MailPoet (40) și YayMail (1). Interogarea tabelei MailPoet de abonați eșuează din același motiv; datele sale nu au putut fi inspectate.
- MariaDB este 10.4.32, cu `innodb_file_per_table=ON`. Fișierele `.frm`/`.ibd` există pentru tabelele testate, dar tabelele afectate nu au intrări în dicționarul `INNODB_SYS_TABLES`; `wp_posts` și `wp_options` au intrări. Logul curent nu indică o cauză pentru lipsa metadatelor.
- Copie rece creată cu MariaDB oprit: `C:\xampp\mysql\backups\brodart-before-repair-20261031-0947` — 804 fișiere, 290.338.912 bytes, zero diferențe SHA-256. MariaDB a fost repornit și conexiunea locală a fost verificată.
- `innochecksum` a citit fișierul `.ibd` MailPoet din copia rece fără să raporteze erori de pagină. Pluginul MailPoet și utilitarul `mysqlfrm` nu sunt instalate local; schema exactă a tabelelor nu poate fi recreată sigur doar din proiectul curent.
- Dump-ul SQL indicat de utilizator, creat astăzi, are 4.185.890 bytes, 66 instrucțiuni `CREATE TABLE`, `INSERT` pentru 21 de tabele și nu are marcaj de final. Tabelele afectate verificate, inclusiv `wp_mailpoet_subscribers`, lipsesc; dump-ul este incomplet și nu este sursă de restaurare.
- Dump-ul cPanel indicat ulterior are 634.407 bytes, 13 instrucțiuni `CREATE TABLE` cu prefixul `wpnw_`, `INSERT` pentru 9 tabele și nu are marcaj de final; nu conține `mailpoet_subscribers` și nu este un dump complet al bazei locale `wp_`.
- `npm run deploy:test` nu a stabilit sesiune SFTP: prima probă a indicat câmpuri de configurare locale lipsă; după confirmarea utilizatorului, a rămas lipsă doar `SFTP_HOST_KEY_SHA256`. Fișierul `.env` este ignorat de Git; valorile sale nu au fost afișate.
- Au fost citite fingerprint-urile cheilor SSH publice servite de domeniu, fără autentificare; nu au fost validate cu WebTeam și nu au fost folosite pentru conectare.
- Testul FTPS nu a ajuns la autentificare sau la citirea directorului; certificatul TLS nu a trecut validarea de încredere.
- Retestarea FTPS a confirmat că certificatul rămâne invalid; nu s-a dezactivat verificarea TLS.
- Inspecția certificatului a stabilit că hostname-ul `ftp.brod-art.ro` nu corespunde certificatului prezentat; hostul SAN `server1.webteamconcept.com` se potrivește, fără erori de validare.
- Test FTPS explicit/TLS 1.2 reușit la `server1.webteamconcept.com`: autentificare și `PWD` doar, răspuns `257 "/" is your current location`. Fără listare, upload sau download; credentialele nu au fost afișate.
- Listare FTPS read-only: `/wp/wp-content/`, `/wp/wp-content/plugins/`, `/wp/wp-content/themes/` și `/wp/wp-content/uploads/` sunt accesibile. Pluginurile listate sunt `akismet`, `hello.php` și `wp-letsencrypt-ssl`; temele listate sunt `twentytwentyfive`, `twentytwentyfour` și `twentytwentythree`; uploads conține `2026` și `wp_encryption`. Nu s-au găsit directorul `mu-plugins` sau pluginurile Brodart în aceste căi. Comenzile FTP `SIZE` au confirmat accesul de citire la `/wp/index.php`, `/wp/wp-content/index.php` și `/wp/wp-content/themes/index.php`, fără descărcarea conținutului. `/public_html/wp/` nu este accesibil prin namespace-ul virtual FTP. Utilizatorul a acordat ulterior permisiuni de scriere; acestea nu au fost încă verificate prin upload.
- Test cu watcherul FTPS și TLS verificat reușit: autentificarea și listarea `/wp` au găsit 21 de intrări și markerii WordPress. Configurația FTPS a fost furnizată tranzitoriu din credențialele locale de transfer; valorile nu au fost afișate și `.env` nu a fost modificat.
- Simularea `npm run deploy:ftps:sync` a enumerat 8 fișiere custom aprobate (inclusiv assets-ul calculatorului); nu a făcut conexiune în modul dry-run și nu a schimbat fișiere remote.
- Nu s-au rulat comenzi de upload, watch activ sau modificări remote; permisiunea de scriere nu a fost verificată prin transfer. Auditul npm după upgrade-ul `basic-ftp` la 6.2.4 raportează zero vulnerabilități.
- Nu s-a rulat export, import, search-replace sau deployment.

REZULTATE:
- Există deja două mecanisme selective pentru catalog: importatorul administrat JSON `wp-content/plugins/brodart-product-importer.php` (import ca draft, reconciliere după `model_key`/SKU și fără ștergerea variațiilor omise) și scriptul batch `tools/import-all-public-local-products.php` (import de produse și imagini în WooCommerce). Acestea nu migrează site-ul, paginile, meniurile, setările sau fișierele către hosting.
- CSV-urile de raport confirmă importuri anterioare de produse. `docs/homepage-626-backup.sql` și `docs/homepage-626-before-slider.sql` sunt snapshot-uri SQL punctuale pentru pagina de start, ignorate de Git; nu sunt backup-uri complete și nu trebuie folosite ca migrare a magazinului.
- Nu am găsit în structura curentă scripturile de backup/deployment enumerate în acest ghid sau o configurație de staging/deployment verificată.
- Au fost numărate local 1 utilizator, 5 comentarii, 39 rânduri de log WPForms, o sesiune WooCommerce și zero comenzi/plăți WooCommerce; aceste numere nu exclud date personale în tabelele inaccesibile sau în alte opțiuni/pluginuri.
- Baza staging este, conform utilizatorului, o clonă a live-ului. Importul complet ar înlocui datele acestei clone. Utilizatorul a confirmat că backupul complet staging a fost creat și verificat, dar fișierul nu a fost verificat aici.
- Dump-ul din Downloads furnizat ca backup local complet nu este complet și omite tabelele afectate; nu trebuie importat ca restaurare sau folosit pentru migrarea completă.
- Al doilea dump furnizat nu corespunde bazei locale: conține numai 13 tabele cu alt prefix și omite tabelele defecte. Nu îl importa în WordPress.
- Utilizatorul declară că are backupuri locale și că există un backup cPanel mai vechi; fișierele SQL furnizate până acum nu reprezintă o sursă completă de recuperare, iar backupurile declarate nu au fost accesate/verificate aici.
- Intrările InnoDB lipsă, împreună cu fișierele tablespace existente și lizibile offline, indică o nealiniere între metadate și fișiere; cauza nu este confirmată. Copia rece oferă rollback local, dar nu este un dump logic verificat și nu repară metadatele.
- Erorile de integritate fac nesigură pregătirea unui dump complet. Restaurarea necesită o sursă compatibilă (dump cunoscut ca bun sau tablespace/schema potrivite); `REPAIR TABLE`, ștergerea fișierelor ori recrearea tabelelor fără sursă ar putea pierde date.

RISCURI / NECUNOSCUTE:
- URL-ul staging furnizat folosește HTTP; confirmă HTTPS înainte de a expune staging-ul sau a transfera date. URL-ul, protecția și accesul au fost declarate de utilizator, nu testate de aici.
- Calea document root, identificarea exactă a bazei staging în cPanel, versiunea MySQL și versiunile WordPress/WooCommerce remote sunt NEVERIFICATE.
- Dump-urile locale disponibile sunt parțiale/nepotrivite. Backupul cPanel mai vechi și backupul staging curent sunt raportate de utilizator, dar nu au fost validate aici. Tabelele MailPoet inaccesibile pot include date personale și starea newsletterelor; nu le elimina și nu recrea tabelele fără backup și diagnostic.
- HPOS este activ local; trebuie verificată configurația remote și compatibilitatea versiunilor WooCommerce înainte de orice migrare DB.

ACȚIUNI CARE NECESITĂ APROBARE:
- Utilizatorul a ales o migrare completă către staging și a confirmat că staging-ul este protejat și separat de live. Aceasta nu autorizează modificarea bazei live.
- Pentru uploadul de cod, utilizatorul a confirmat că `/wp/` este stagingul și a acordat permisiuni de scriere. FTPS a confirmat doar markerii WordPress și accesul de citire; ținta declarată și permisiunea de scriere nu au fost verificate printr-un upload. Scriptul existent `tools/sftp-deploy-watcher.js` rămâne pentru SFTP/SSH; watcherul FTPS separat publică numai componentele custom Brodart aprobate.
- Watcherul FTPS este configurat pentru root-ul remote exact `/wp`, TLS explicit cu verificarea certificatului activă și numai fișierele celor patru componente custom: pluginul de măsurători (inclusiv assets), pluginul de imagini meniu, importatorul de produse și mu-pluginul Brodart. Nu sincronizează WordPress core, `wp-config.php`, `.env`, baza de date, uploads, fișiere șterse local sau alte pluginuri.
- Configurare: completează local `.env` cu `FTPS_HOST=server1.webteamconcept.com`, `FTPS_PORT=21`, credențialele FTP și `FTPS_REMOTE_PATH=/wp`. Nu reutiliza parola cPanel dacă hostingul oferă cont FTP separat. Nu salva credențialele în Git și nu dezactiva validarea TLS. Testează cu `npm run deploy:ftps:test`, apoi inspectează lista de fișiere cu `npm run deploy:ftps:sync` (simulare fără scrieri).
- Înainte de orice publicare, fă backup remote al fișierelor staging schimbate. Pentru a publica prima copie, setează în `.env` `FTPS_STAGING_CONFIRM=I_CONFIRM_STAGING` după ce ai confirmat stagingul și backupul, apoi rulează `npm run deploy:ftps:sync -- --apply`. Pentru publicări continue, rulează `npm run deploy:ftps:watch`; acesta nu urcă fișierele existente la pornire, deci rulează întâi sincronizarea inițială. Oprește watcherul cu Ctrl+C. Erorile de transfer sunt afișate; nu se fac ștergeri remote.
- Separă complet baza de date de acest flux de cod: păstrează baza locală și credențialele locale în `wp-config.php` local; stagingul trebuie să aibă propria bază și propriul utilizator cu privilegii minime, configurate numai în `wp-config.php` de pe staging sau în secret managerul hostingului. Watcherul FTPS nu citește și nu transferă `wp-config.php`, `.env` ori dump-uri SQL. Nu copia credențialele DB live în `.env` sau în cod; dacă actualizezi staging DB, folosește un backup separat și verifică `home`/`siteurl`, emailurile și plățile test înainte de utilizare.
- Nu accepta FTPS cu certificat invalid și nu folosi FTP necriptat. Hostingul trebuie să furnizeze/repereze certificatul TLS sau să confirme fingerprint-ul SSH SFTP printr-un canal de încredere.
- Backupul complet staging a fost confirmat de utilizator ca realizat/verificat; verificarea fișierului și restaurabilității nu a fost făcută aici. Preferă o bază nouă de staging, păstrând baza-clonă intactă pentru rollback. Dacă nu se poate crea o bază nouă, oprește-te înainte de a șterge tabele.
- Înainte de restaurarea tabelelor locale: identifică o sursă compatibilă și decide explicit dacă datele newsletter/Complianz/YayMail trebuie păstrate. Backupul rece local există, dar nu conține un export SQL verificat.
- Nu trimite parole, tokenuri sau date de abonați în chat; accesul și orice dump trebuie gestionate printr-un canal securizat.

URMĂTORUL PAS:
- Folosește unul dintre backupurile complete păstrate de utilizator numai după ce este validat într-o bază locală izolată și `wp db check` trece fără erori. Dump-urile examinate nu sunt surse valide. Dacă niciun backup nu trece verificarea, cere intervenția unui administrator MariaDB sau limitează migrarea la cod/catalog selectiv. După validarea sursei, continuă preflight-ul și migrarea în baza staging nouă. Nu modifica producția.
```

------------------------------------------------------------------------

## 18. Criterii de acceptare

Implementarea este considerată gata numai când:

-   codul custom Brodart este versionat în Git;
-   se poate publica o schimbare de cod pe staging fără a copia întregul
    WordPress;
-   există backup înainte de publicare;
-   există o metodă documentată de rollback pentru cod;
-   o pagină poate fi promovată selectiv din local în staging;
-   setările Blocksy și meniurile au o procedură verificată de migrare;
-   media poate fi transferată fără ștergeri accidentale;
-   comenzile și clienții din producție nu sunt suprascrise de
    sincronizarea locală;
-   staging nu trimite emailuri sau plăți reale;
-   production se publică numai după aprobare umană;
-   toate procedurile sunt documentate și testate pe hostingul real.

**Prioritatea absolută:** protejarea datelor magazinului live. Dacă o
operațiune de sincronizare nu poate garanta ce date modifică, nu o
executa automat.

## 19. Publicare FTPS selectivă către staging

Watcherul adăugat în `tools/ftps-deploy-watcher.js` este separat de
watcherul SFTP existent. Înainte de utilizare:

1. Completează în `.env` numai credențialele contului FTPS dedicat și
   hostname-ul care corespunde certificatului TLS (`server1.webteamconcept.com`).
2. Confirmă că root-ul FTP `/` corespunde instalării staging de la
   cPanel `public_html`, unde directorul țintă este `/wp`; configurarea
   refuză orice altă valoare `FTPS_REMOTE_PATH`.
3. Rulează `npm run deploy:ftps:test`, apoi
   `npm run deploy:ftps:sync` pentru simulare. Simularea nu deschide
   conexiuni și nu schimbă fișiere remote.
4. Fă backup remote al fișierelor țintă. Setează
   `FTPS_STAGING_CONFIRM=I_CONFIRM_STAGING` în `.env`, apoi rulează
   `npm run deploy:ftps:sync -- --apply` pentru prima publicare. Pentru
   schimbările următoare, `npm run deploy:ftps:watch`.

Publicarea este limitată la fișierele pluginurilor Brodart de măsurători
și imagini meniu (inclusiv assets), importatorul Brodart și mu-pluginul
Brodart. Nu șterge fișiere remote și nu publică WordPress core, alte
pluginuri, uploads, `wp-config.php`, `.env` sau baza de date. Watcherul
ignoră fișierele existente la pornire; rulează prima sincronizare înainte
de `watch`. Permisiunea de scriere nu este considerată verificată până
când nu se face prima publicare deliberată după backup.

Păstrează bazele de date și credențialele locale și staging distincte.
Datele de conectare DB rămân în `wp-config.php` al fiecărui mediu sau în
secret managerul hostingului și nu sunt necesare pentru transferul de
cod. Pentru o clonă staging, folosește un DB/user separat cu privilegii
minime și verifică URL-urile, emailurile și setările de plată în mod test;
nu sincroniza automat date personale ori tranzacții din producție.
