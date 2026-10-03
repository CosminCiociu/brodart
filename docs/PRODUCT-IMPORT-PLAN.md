Produsele sunt publicate active după validarea datelor, a prețului și a fluxului de cerere de ofertă.
Produsele sunt publicate active după validarea datelor, a prețului și a fluxului de cumpărare prin coș.

Indexare: produsele draft nu sunt indexabile. Înainte de publicare, dezactivează modul „Coming soon”, verifică să nu fie activă opțiunea WordPress de descurajare a motoarelor de căutare, confirmă sitemap-ul și robots.txt, apoi înregistrează site-ul în Google Search Console și trimite sitemap-ul.
Indexare: produsele draft nu sunt indexabile. Înainte de lansare, dezactivează modul „Coming soon”, verifică să nu fie activă opțiunea WordPress de descurajare a motoarelor de căutare, confirmă sitemap-ul și robots.txt, apoi înregistrează site-ul în Google Search Console și trimite sitemap-ul.

Publicarea activă se face numai după confirmarea monedei și testarea coșului; drafturile nu sunt indexate de Google.
Publicarea activă se face numai după confirmarea monedei și testarea coșului; site-ul rămâne nepublic până la dezactivarea modului „Coming soon”.

Produsul părinte `Perdea Alana Crem T08` există în WooCommerce ca draft (ID 700), tip variabil, în categoria „Perdele”.
Produsul `Perdea Alana Crem T08` există în WooCommerce ca produs simplu publicat (ID 700), în categoria „Perdele”.

Descrierea completă include specificațiile tehnice existente; descrierea scurtă prezintă produsul. Yoast SEO este activ, iar titlul meta, meta-descrierea și expresia-cheie sunt completate.
Prețul solicitat este 100 RON, dar nu a fost introdus: moneda globală WooCommerce este USD, iar setarea afectează și cele 12 produse demo existente. Introducerea valorii `100` acum ar însemna 100 USD, nu 100 RON.
Imaginea principală și cele cinci imagini din galerie provin din folderul local. URL-ul draftului este `/product/perdea-alana-crem-t08/`.
Starea produsului este Draft; nu este publicat.
Descrierea scurtă și meta-descrierea Yoast nu mai conțin text de cerere de ofertă. Yoast SEO este activ, iar titlul meta, meta-descrierea și expresia-cheie sunt completate.
Prețul actual este 100 USD (`$100.00`); prețul solicitat este 100 RON. Moneda globală este USD și afectează cele 12 produse demo existente.
Imaginea principală și cele cinci imagini din galerie provin din folderul local. URL-ul produsului este `/product/perdea-alana-crem-t08/`.
Produsul este publicat; front-end-ul afișează „Add to cart” și nu afișează cerere de ofertă.

**Blocaje înainte de activare:** moneda magazinului este USD, iar „Coming soon” este activ. Variația Alana nu are momentan preț și WooCommerce nu o afișează; prețul solicitat este 100 RON, dar nu trebuie salvat ca 100 USD.
**Blocaj rămas:** magazinul afișează prețul `$100.00`, nu 100 RON, deoarece moneda globală este USD. „Coming soon” este activ pentru întregul site.

YITH Request a Quote for WooCommerce este instalat, dar dezactivat; butonul standard de coș este restaurat.
YITH Request a Quote for WooCommerce este dezactivat, iar formularul de cerere de ofertă a fost eliminat din descrierea scurtă și meta-descriere.

Înainte de a seta prețul pilotului, confirmă schimbarea globală a monedei în RON sau o soluție alternativă care nu afișează `100` ca USD. Schimbarea globală afectează și cele 12 produse demo publicate. Dezactivează „Coming soon” doar când magazinul e pregătit pentru vizitatori.
Confirmă schimbarea globală a monedei în RON sau o soluție alternativă care nu afișează `100` ca USD. Schimbarea globală afectează și cele 12 produse demo publicate. Dezactivează „Coming soon” doar când magazinul e pregătit pentru vizitatori.

Verifică selectorul de variații și fluxul „Adaugă în coș” până la checkout, apoi confirmă afișarea desktop/mobil și indexarea Google Search Console.
Butonul „Add to cart” este prezent; după confirmarea monedei, testează adăugarea în coș și checkout, apoi confirmă afișarea desktop/mobil și indexarea Google Search Console.

# Plan import produse Mendola Fabrics

## Scop și limite

Importul va crea în WooCommerce produse Brodart pornind numai de la codurile prezente în `E:\update-2022\Brod-art\email_photos`. Acordul Mendola Fabrics este confirmat. Se folosesc exclusiv imaginile din folderul local; paginile Mendola servesc la identificarea produselor și colectarea specificațiilor aprobate, nu pentru descărcarea imaginilor. Produsele se cumpără direct prin coșul WooCommerce. După verificarea informațiilor, prețului în RON, filtrelor și imaginilor, produsul se publică activ.

## Inventar inițial

- Folderul conține 1.796 fișiere imagine și 9 arhive ZIP.
- Gruparea preliminară după codul din numele fișierului indică aproximativ 431 de articole; numărul trebuie reconfirmat printr-un manifest, deoarece există nume duplicate, sufixe neuniforme, extensii cu majuscule și fișiere cu variante precum `_alb` sau `(2)`.
- Pentru `14-ALANA-T08` există șase imagini locale. Pagina publică prezintă numele „ALANA CREM T08”, colecția QUADRA, o descriere și specificații precum codul, brandul, culoarea, repetarea modelului și înălțimea repetării.
- Sitemap-ul public WordPress include sitemap-uri de produse și taxonomii. `robots.txt` permite accesul la paginile publice și indică sitemap-ul principal.

## Etape

### 1. Clarificări și permisiuni

- Acordul Mendola Fabrics este confirmat. Păstrează confirmarea împreună cu materialele proiectului; dacă acordul limitează anumite canale sau materiale, respectă acele limite.
- Vânzarea se face prin coșul WooCommerce. Decizie temporară confirmată: toate variațiile gestionate de importer au prețul de 100 RON/metru liniar; regula nu modifică produsele demo sau restul catalogului. Nu importa taxe, stocuri sau disponibilitate neconfirmate.
- Domeniul importului este limitat la codurile reprezentate în folderul local.
- Codurile/culorile aferente aceluiași model vor fi variații ale unui produs părinte. Fiecare cod oficial rămâne SKU unic pentru variația sa.

### 2. Manifest și reconcilierea imaginilor

- Inventariază recursiv numele, extensia, dimensiunea și hash-ul fișierelor; nu modifica și nu șterge originale.
- Normalizează codul de produs din numele fișierului fără a elimina arbitrar cifrele care fac parte din cod. Grupează sufixele de imagine separat, inclusiv `_1`, `_alb`, `2000` și duplicatele `(2)`.
- Produce un manifest CSV cu: cod candidat, fișiere asociate, număr imagini, duplicate/hash-uri, arhive relevante, URL-ul Mendola găsit, stare de potrivire și observații.
- Verifică manual cazurile ambigue și ordinea imaginilor. Nu presupune că numărul din sufix reflectă ordinea galeriei de pe site.
- Extrage arhivele ZIP doar într-o zonă temporară după verificarea conținutului; deduplică pe hash și păstrează numele originale în manifest.

### 3. Inventarierea și colectarea controlată a paginilor

- Citește sitemap-urile publice de produse și selectează paginile ale căror coduri corespund manifestului. Nu parcurge pagini private, conturi, coșuri, comenzi sau alte date fără legătură cu catalogul public.
- Respectă `robots.txt`, folosește cereri rare, secvențiale și cu timeout/retry limitat; oprește colectarea la erori repetate sau dacă site-ul solicită limitarea accesului.
- Pentru fiecare pagină, colectează numai datele necesare: URL și dată colectare, cod, nume, brand, culoare, colecție, descriere, specificații tehnice, categorii/taxonomii relevante și URL-urile imaginilor ca referințe de verificare.
- Salvează rezultatele într-un fișier intermediar versionat, cu sursa fiecărui câmp și stare de validare. Câmpurile lipsă rămân goale și necesită confirmare; nu se deduc compoziția, dimensiunile, prețul, stocul sau performanțele.
- Folosește exclusiv imaginile potrivite și validate din folderul local pentru Media Library; nu face hotlink și nu descarcă imagini de pe site-ul de referință.

### 4. Maparea la WooCommerce

- Creează un produs părinte pentru fiecare model și variații pentru culorile/codurile sale. SKU-ul fiecărei variații este codul oficial; respinge dublurile SKU înainte de încărcare.
- Mapează numele/codul, descrierea aprobată și specificațiile nefiltrabile în descriere. Folosește atribute globale filtrabile pentru Culoare, Repetare model, Repetare înălțime, Dimensiune model și Colecție. Păstrează numai atributul culoare/cod ca selector de variație, dacă este necesar pentru SKU.
- Configurează filtrele vizibile în arhiva Magazin pentru atributele relevante; simpla creare a atributelor nu adaugă automat controale de filtrare.
- Folosește doar fotografii locale potrivite, imagine principală și galerie verificate, cu text alternativ descriptiv în română generat din informațiile confirmate; păstrează URL-ul Mendola doar ca referință internă.
- Folosește modelul de variații confirmat pentru codurile aferente aceluiași model; leagă fiecare variație de imaginile locale corespunzătoare. Cazurile în care asocierea modelului nu este clară rămân în raportul de excepții.
- Folosește cumpărarea WooCommerce standard: preț vizibil, selector de variație și buton „Adaugă în coș”. YITH Request a Quote for WooCommerce trebuie să rămână dezactivat, deoarece ascunde butonul standard când opțiunea este activă.
- Folosește Yoast SEO pentru titlu SEO, meta-descriere unică și expresie-cheie pentru fiecare produs; verifică analiza și previzualizarea înainte de publicare.
- Completează prețul confirmat în moneda WooCommerce pentru produs și variații. Nu introduce o valoare în altă monedă decât cea configurată și nu inventa taxe sau stoc.
- Publică produsul ca activ numai după validarea conținutului, prețului, imaginilor, filtrării și adăugării în coș.
- Folosește importatorul CSV nativ WooCommerce pentru un lot mic; pentru loturi repetabile, alege după pilot un import idempotent prin API-ul WooCommerce sau un mecanism local administrat. Reimportul trebuie să actualizeze după SKU, nu să dubleze produsele.

### Importator local administrat

- Implementarea activă este `wp-content/plugins/brodart-product-importer.php`; instrumentul apare în WooCommerce → Import Brodart. Validarea, respingerea SKU-urilor ocupate și primul import real ca draft au fost testate.
- Importatorul folosește un manifest JSON pentru un părinte variabil și una sau mai multe variații cu SKU oficial. `model_key` este cheia stabilă a părintelui; păstreaz-o neschimbată la reimport.
- Apasă mai întâi „Validează”, apoi „Importă ca draft”. Un părinte cu aceeași cheie este actualizat; variațiile sunt reconciliate după SKU. SKU-urile deja folosite în altă parte sunt respinse, iar variațiile omise dintr-un reimport nu sunt șterse.
- Categoria trebuie să existe. Atributele globale admise sunt Culoare, Repetare model, Repetare înălțime, Dimensiune model și Colecție; taxonomiile globale trebuie create în WooCommerce înainte de import.
- `local_attributes` acceptă atributul local nefiltrabil `Lățime`; folosește-l când valoarea trebuie afișată la Informații suplimentare fără să creezi o taxonomie globală.
- Fiecare variație primește temporar prețul fix de 100 RON/metru liniar, iar moneda magazinului trebuie să fie RON. Dacă manifestul conține un alt preț, importul este respins. Produsul părinte este creat ca draft și nu se publică prin import.
- Imaginile sunt selectate prin numele fișierului din `E:\update-2022\Brod-art\email_photos`, copiate în Media Library fără mutarea originalelor și primesc textul alternativ din manifest. Pentru altă locație, configurează `BRODART_PRODUCT_IMPORT_IMAGE_ROOT` în `wp-config.php`.
- Nu se extrag automat texte sau prețuri din Mendola; completează numai datele verificate. Exemplu de structură (înlocuiește toate valorile `__...__`; elimină atributele neconfirmate):

```json
{
  "model_key": "__CHEIE_MODEL_STABILA__",
  "name": "Perdea __MODEL__",
  "source_url": "https://mendolafabrics.ro/product/__slug-confirmat__/",
  "category": "Perdele",
  "description": "__DESCRIERE_APROBATA__",
  "short_description": "__PREZENTARE_SCURTA__",
  "attributes": {
    "Culoare": ["__CULOARE_CONFIRMATA__"],
    "Repetare model": "__VALOARE_CONFIRMATA__",
    "Repetare înălțime": "__VALOARE_CONFIRMATA__",
    "Dimensiune model": "__VALOARE_CONFIRMATA__",
    "Colecție": "__COLECȚIE_CONFIRMATĂ__"
  },
  "variations": [
    {
      "sku": "__SKU_OFICIAL__",
      "name": "__CULOARE ȘI COD__",
      "color": "__CULOARE_CONFIRMATA__",
      "source_url": "https://mendolafabrics.ro/product/__pagina-variantei__/",
      "image": "__SKU_OFICIAL__.jpg",
      "gallery": ["__SKU_OFICIAL___1.jpg"],
      "alt": "Perdea __MODEL__ __CULOARE__, cod __SKU_OFICIAL__."
    }
  ]
}
```

- Configuratorul existent se activează automat pentru produse simple și variabile din categoriile WooCommerce Perdele/Draperii și subcategoriile lor. La produsele variabile, prețul folosit în sumar și în coș este tariful variației selectate. Limitele și opțiunile se verifică/configurează per produs înainte de publicare; nu se adaugă automat taxe de prindere, stoc sau alte tarife.

### Primul produs importat: Alegria

- La 27.09.2026 a fost creat produsul părinte `Perdea Alegria din Colecția Scandi` (ID 728), variabil, categoria „Perdele”, în starea Draft.
- Variații: Gri T04, SKU `14-ALEGRIA-T04` (ID 729), sursa `https://mendolafabrics.ro/product/14-alegria-t04/`; Bej T05, SKU `14-ALEGRIA-T05` (ID 730), sursa `https://mendolafabrics.ro/product/14-alegria-t05/`.
- Ambele variații au prețul confirmat temporar de 100 RON/metru liniar. Editorul WooCommerce confirmă 100 pentru fiecare SKU; previzualizarea afișează selectorul de culoare și prețul cu unitatea în română.
- Sunt atașate cele 12 imagini locale validate, câte șase pentru fiecare culoare, cu text alternativ descriptiv. Originalele au rămas în folderul-sursă.
- În Informații suplimentare apar Culoare, Colecție și atributul local Lățime=315 cm. Repetare model, Repetare înălțime și Repetare model lățime (2 cm) sunt în descriere, nu în atributele suplimentare; sursa `315 cm / 315 cm` este mapată la Lățime `315 cm`.
- Galeria per variație este salvată în WooCommerce: selectarea Gri T04 sau Bej T05 arată doar cele șase fotografii ale culorii selectate, cu SKU-ul corespunzător. SEO title, meta-descrierea și expresia-cheie sunt completate.
- Selectorul de culoare al produselor variabile din categoriile Perdele/Draperii folosește imaginea principală a fiecărei variații, eticheta culorii și SKU-ul; opțiunile controlează selectorul WooCommerce nativ pentru compatibilitate cu galerie, preț și coș.
- Produsul rămâne Draft. Configuratorul apare și pe Alegria variabilă; înainte de publicare trebuie testat fluxul complet măsurare/adăugare în coș cu tariful variației și limitele comerciale aprobate.

### Al doilea produs importat: Amalfi

- La 27.09.2026 a fost creat produsul părinte `Draperie Amalfi` (ID 747), variabil, în categoria WooCommerce „Perdele”, în starea Draft. Colecția `Jade` este păstrată separat ca atribut global și apare în Informații suplimentare.
- Variații importate și publicate tehnic: Roz V5, SKU `14-AMALFI-V5` (ID 748), Gri închis V8, SKU `14-AMALFI-V8` (ID 749), și Gri V13, SKU `14-AMALFI-V13` (ID 750). Fiecare variație are prețul temporar de 100 RON/metru liniar.
- Sursa publică confirmă colecția Jade, `Repetare model=Nu` și `Dimensiune model=280 cm / 280 cm`. Au fost importate 16 imagini locale: 5 pentru V5, 5 pentru V8 și 6 pentru V13.
- Imaginile locale `14-AMALFI-V6*` și `14-AMALFI-V10*` nu au fost importate, deoarece variantele nu au fost confirmate în paginile publice Amalfi verificate.
- Produsul părinte rămâne Draft, iar variațiile sunt publicate în WooCommerce; fluxul configuratorului și afișarea finală trebuie verificate înainte de publicarea părintelui.

### Model negăsit: Alfama

- La 27.09.2026 modelul `14-ALFAMA` și variantele locale `V1` și `V3` au fost marcate „negăsit”. Nu apar în sitemap-ul, căutarea sau paginile publice Mendola verificate; cele 10 imagini locale nu au fost importate.

### Lot public importat la 27.09.2026

- Au fost procesate 105 pagini publice Mendola care au potrivire cu imaginile locale. Batch-ul a publicat 80 de SKU-uri confirmate, la tariful temporar de 100 RON/metru liniar, în 41 de grupuri de modele.
- Categoria WooCommerce `Draperii` a fost creată; paginile identificate ca draperii folosesc această categorie, iar celelalte folosesc `Perdele`.
- Produsele și variațiile fără pagină publică sau fără potrivire locală nu au fost inventate și rămân în raportul `PRODUCT-IMPORT-BATCH-REPORT.csv` ca neimportate/negăsite.

### 5. Pilot, verificare și extindere

- Pregătește `14-ALANA-T08` ca pilot: confruntă manifestul cu fișa publică, verifică toate cele șase fișiere locale și selectează manual imaginea principală și ordinea galeriei.
- Creează produsul părinte Alana și variația Crem T08 cu SKU `14-ALANA-T08`. Verifică în WordPress atributele, galeria, descrierea, afișarea mobilă, URL-ul și prețul în moneda corectă.
- Verifică selectorul de variații, prețul și fluxul „Adaugă în coș” împreună cu pilotul. După validarea cerințelor tehnice și comerciale, publică produsul activ; produsele noi nu rămân draft implicit.
- Rulează apoi în loturi mici, păstrând raport cu produse create/actualizate, respinse și cu erori. Revizuiește manual eșantioane din fiecare grupă de fișiere.
- Publică numai după verificarea datelor comerciale și a conținutului. Fă backup înaintea fiecărui import mare și păstrează CSV-ul de import și raportul pentru rollback.

## Criterii de acceptare

- Fiecare cod importat apare în folderul local, are SKU unic și o potrivire verificată cu fotografiile locale; culorile/codurile sunt variații ale modelului, nu produse independente.
- Nu există câmpuri tehnice sau comerciale ghicite; valorile neconfirmate nu sunt afișate ca fapte.
- Sunt folosite numai imagini din folderul local, cu metadate descriptive în română și sursă internă documentată.
- Produsul poate fi adăugat în coș cu variația și prețul corecte; nu afișa stoc neconfirmat.
- Pilotul trece verificarea în desktop și mobil, iar reimportul nu creează duplicate.
- Produsele sunt publicate active după validarea datelor, a prețului și a fluxului de cerere de ofertă.

## Minim necesar pentru un produs complet și indexare

- Conținut: titlu descriptiv cu produsul, culoarea/codul și colecția; descriere unică; specificații tehnice confirmate; categorie și SKU-uri unice pentru variații.
- Imagini: numai fișierele locale potrivite, imagine principală și galerie în ordine verificată; generează text alternativ descriptiv pentru fiecare imagine din datele confirmate, fără a inventa detalii vizuale.
- Cumpărare: YITH Request a Quote for WooCommerce este dezactivat. Verifică prețul, selecția variației, butonul „Adaugă în coș” și finalizarea comenzii.
- SEO on-page: Yoast SEO este instalat și activ. Completează titlu meta, meta-descriere și expresie-cheie unice; verifică previzualizarea și analiza. Pentru Alana: titlu `Perdea Alana Crem T08 din Colecția Quadra | Brodart`; descriere `Perdea Alana Crem T08 din Colecția Quadra, cu model abstract și bordură clasică. Vezi specificațiile și imaginile produsului Brodart.`
- Indexare: produsele draft nu sunt indexabile. Înainte de publicare, dezactivează modul „Coming soon”, verifică să nu fie activă opțiunea WordPress de descurajare a motoarelor de căutare, confirmă sitemap-ul și robots.txt, apoi înregistrează site-ul în Google Search Console și trimite sitemap-ul.
- Date structurate: WooCommerce furnizează date de produs, dar produsele fără preț pot să nu fie eligibile pentru rezultate comerciale sau fragmente îmbogățite cu preț. Nu inventa prețuri sau disponibilitate pentru a obține afișări speciale.

Publicarea activă se face numai după confirmarea monedei și testarea coșului; drafturile nu sunt indexate de Google.

## Decizii confirmate

- Acordul Mendola Fabrics este disponibil.
- Se folosesc numai fotografiile din `E:\update-2022\Brod-art\email_photos`, iar domeniul produselor este limitat la codurile din acest folder.
- Site-ul va permite cumpărarea prin coșul WooCommerce; codurile/culorile sunt variații.
- `14-ALANA-T08` este pilot; după verificare, obiectivul este publicarea activă a produselor.

## Stare pilot Alana

- Verificat în admin la 27.09.2026: `Perdea Alana Crem T08` (ID 700) este publicat, tip simplu, categoria „Perdele”, SKU `14-ALANA-T08`.
- Colecția `Quadra` este atribuită prin atributul global „Colecție”; acesta nu este folosit ca opțiune de variație.
- Atributele confirmate în descrierea și pagina produsului: Culoare=Crem, Repetare model=Da, Repetare înălțime=28 cm, Dimensiune model=250 cm / 280 cm și Colecție=Quadra.
- Atributele au arhive globale activate, dar controalele de filtrare pentru Culoare, Repetare model, Repetare înălțime și Dimensiune model nu sunt încă configurate în bara Magazin.
- Descrierea completă include specificațiile tehnice confirmate; descrierea scurtă prezintă produsul. Yoast SEO este activ, iar titlul meta, meta-descrierea și expresia-cheie sunt completate.
- Adminul și pagina locală afișează prețul `100 RON / metru liniar`; pagina Mendola nu furnizează un preț Brodart.
- Imaginea principală și cele cinci imagini din galerie provin din folderul local. URL-ul produsului este `/product/perdea-alana-crem-t08/`.
- Starea produsului este Published, cu vizibilitate publică în catalog.
- Text alternativ propus și aplicat imaginilor: `Perdea Alana crem cu model abstract și bordură clasică, cod 14-ALANA-T08.`
- Calculatorul este activ pe produsul simplu: lățime 0,01–2,50 m, înălțime 1–5 m, pas 0,01 m; nu sunt configurate sisteme de prindere.
- Pagina locală afișează „Adaugă în coș” și configuratorul. Bara de admin indică „Store coming soon”; confirmă accesul vizitatorilor neautentificați înainte de publicarea altor produse.
- YITH Request a Quote for WooCommerce este instalat, dar dezactivat; butonul standard de coș este restaurat.
- Atributele globale au termenii corecți, dar filtrele vizibile în arhiva Magazin nu sunt încă configurate/testate.
- Configuratorul este disponibil automat pe produsele simple/variabile din Perdele/Draperii; testarea fluxului variabil în coș și aprobarea limitelor comerciale rămân necesare înainte de publicare.
- Verifică selectorul de variații și fluxul „Adaugă în coș” până la checkout, apoi confirmă afișarea desktop/mobil și indexarea Google Search Console.
