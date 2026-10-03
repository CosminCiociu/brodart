# Roadmap — Brodart (site tip Zara Home / H&M Home / Westwing)

Roadmap-ul e împărțit pe milestone-uri. Bifează fiecare milestone înainte de a trece
la următorul, ca să te asiguri că fundația (stil, structură) e solidă înainte de a
adăuga conținut.

## Milestone 0 — Fundație tehnică ✅ (făcut)

- [x] Fișierele temei **Blocksy 2.1.57** instalate; activarea rămâne de făcut după instalarea WordPress
- [ ] Configurează permalinkuri SEO-friendly după instalarea WordPress

## Milestone 1 — Design system global

- [x] Configurează **Global Colors** în Customizer conform [STYLE-GUIDE.md](STYLE-GUIDE.md#2-paletă-de-culori)
- [x] Configurează **Global Typography** (font titluri + font body) conform secțiunii 3
- [x] Setează container width, spacing, border-radius global (Customizer → Layout)
- [x] Alege/instalează fonturile (Google Fonts prin Blocksy) și verifică încărcarea rapidă
- [x] Creează un buton "primar" și unul "secundar" ca stiluri reutilizabile (Blocksy Content Blocks sau Global Blocks)

## Milestone 2 — Header & Footer (o singură dată, moștenite peste tot)

- [x] Creează meniul principal și atribuie-l headerului desktop și mobil (meniul conține Acasă, Magazin, Colecții → Toate colecțiile + cele 11 subcolecții, Despre noi și Contact și este atribuit ambelor locații; dropdown-ul Colecții afișează imagini pentru 10 subcolecții, iar Destination Kitchen așteaptă fotografia dedicată; link-urile subcolecțiilor duc la magazinul cu filtrul Colecție preselectat, ex: /shop/?filter_colectie=scandi, iar părintele Colecții duce la /shop/)
- [ ] Configurează header-ul în Blocksy Header Builder (logo, cont, coș, wishlist) (Logo, Account și Cart sunt active; wishlist nu este instalat)
- [x] Comportament header: transparent peste hero → solid la scroll (Transparent este activ pe Home; Sticky este activ pe desktop și mobil în Blocksy)
- [x] Construiește footer-ul (coloane: Despre, Ajutor, Explorează și Contact) (datele oficiale Carmiram Brodart sunt afișate în română, cu adresa din Strada Zorilor și telefonul 0723 137 399; fundalul este #F8F7F5)
- [x] Testează header/footer pe mobil (meniu hamburger elegant) (testat la 390 px: drawer-ul afișează Acasă și Magazin, coloanele se așază vertical și nu există overflow; lipsește încă wishlist-ul)

## Milestone 3 — Pagina de start (Home)

- [x] Hero full-width cu imagine mare + titlu + CTA discret
- [x] Secțiune "Colecții" (carduri mari, 2-3 categorii cheie, gen Zara Home)
- [x] Secțiune produse recomandate (grid WooCommerce, 4 coloane)
- [x] Secțiune editorială/lookbook (imagine + text, stil revistă)
- [x] Secțiune newsletter (fundal secundar, minimalist)
- [x] Înlocuiește conținutul demo rămas (produse, articole și texte în engleză) și verifică traducerea integrală în română (site-ul e setat pe ro_RO, WooCommerce are pachetul oficial ro_RO, iar mu-plugin-ul brodart-shop-tweaks acoperă șirurile de blocuri netraduse; cele 12 produse demo au fost depublicate, rămânând cele 41 de produse reale Mendola + pilotul Alana)

## Milestone 4 — Shop & Produs (WooCommerce)

- [ ] Importă produsele reale după pașii și aprobările din [PRODUCT-IMPORT-PLAN.md](PRODUCT-IMPORT-PLAN.md)
- Importatorul administrat JSON și batch-ul local din `tools/import-all-public-local-products.php` sunt active; produsele cu imagini locale și pagini publice Mendola au fost importate și publicate la 100 RON/metru liniar. Alana a fost păstrată ca produs existent, iar Alfama și variantele fără pagini publice au fost marcate negăsite/neimportate. Milestone-ul rămâne deschis până la validarea compatibilității configuratorului și a fluxului de cumpărare.
- [ ] Configurează WooCommerce Setup Wizard (monedă, livrare, plăți)
- [x] Creează atributul global WooCommerce „Colecție” și arhivele filtrabile pentru Axioma, Scandi, Jade, Quadra, Monograma, Archiline, Saronga, Riviera, Basic, Joyeusse și Destination Kitchen
- [ ] Asociază produsele reale cu una sau mai multe colecții; produsele actuale sunt demo și nu au fost etichetate arbitrar
- [x] Stilizează pagina de arhivă produse (grid, filtre discrete, sortare) (filtrele sunt legate explicit de atribute — Culoare `attributeId:2`, Colecție `attributeId:1`, traduse în română, fără filtru de preț; sortarea implicită e pe noutăți; badge-urile sunt discrete pe paleta din ghid; mu-plugin-ul brodart-shop-tweaks adaugă badge NOU pentru produsele <30 zile și hover cu a doua imagine)
- [ ] Stilizează pagina de produs individual (galerie mare, info curată, variații)
- [ ] Simplifică pagina de coș și checkout (mai puține câmpuri, pași clari)
- [ ] Verifică emailuri de comandă (stil simplu, coerent cu brandul)

### Milestone 4A — Configurator perdele înainte de adăugarea în coș

Scopul este extinderea pluginului existent `brodart-measurement-calculator` într-un configurator WooCommerce pentru produse textile la metru liniar. `Perdea Alana Crem T08` rămâne pilotul comercial; interfața se activează automat pentru produsele simple și variabile din categoriile Perdele/Draperii, iar fiecare produs se publică numai după validarea prețului, limitelor și fluxului de cumpărare.

- [x] Confirmă disponibilitatea publică a produsului pilot; pagina este accesibilă, moneda globală WooCommerce este RON, iar modul „Coming soon” nu mai ascunde produsul pentru utilizatorul autentificat
- Configuratorul existent se activează automat pentru produsele simple și variabile din categoriile WooCommerce Perdele/Draperii (inclusiv subcategorii); pe produse variabile tariful live și cel salvat în coș folosesc variația selectată. Limitele și opțiunile rămân configurabile per produs.
- [ ] Definește contractul de configurare și meta-câmpurile produsului: înălțime, lățime, tip de prindere, număr de bucăți, încrețire și tarife separate pentru material, rejansă și manoperă
- [ ] Extinde panoul WooCommerce al pluginului pentru opțiuni configurabile per produs, cu valori implicite, limite, pași și ordinea afișării
- [ ] Construiește interfața de produs în ordinea: dimensiuni, sistem de prindere, număr de bucăți, sumar de cost și „Adaugă în coș”; păstrează stilul Blocksy și textele în română
- [ ] Calculează live și server-side: material = lățime × coeficient de încrețire × tarif material; accesorii = lățime × tarif opțiune; manoperă = tarif configurat; total = suma componentelor × număr bucăți, conform regulii comerciale confirmate
- [ ] Validează nonce, limitele, pașii, combinațiile disponibile și prețul pe server înainte de adăugarea în coș; JavaScript-ul este doar feedback vizual
- [ ] Salvează configurația completă în coș, comandă și emailuri: dimensiuni, opțiuni, cantitate, componente de preț și totalul calculat
- [ ] Verifică separat afișarea pe produs, coș, checkout și comandă, inclusiv desktop și mobil; confirmă că reîncărcarea coșului nu modifică prețul
- [ ] Rulează testul pilot cu exemple documentate: 1 bucată, lățime 1 m, înălțime 2,7 m și fiecare tip de prindere disponibil
- [ ] Documentează deciziile comerciale rămase deschise înainte de activare: unitatea pentru înălțime, aplicarea încrețirii, dacă manopera este per bucată sau comandă și dacă numărul de bucăți multiplică materialul și accesoriile

**Decizii confirmate pentru pilot:** înălțime maximă `70 m`, selectorul pentru 1/2 bucăți folosește imaginile din `docs/references`, iar sistemul de prindere/rejansa este amânat pentru o etapă ulterioară. Moneda și formatul de afișare WooCommerce sunt RON, cu virgulă zecimală și simbolul după valoare.

**Criteriu de acceptare:** un client poate configura produsul pilot înainte de adăugarea în coș, vede prețul componentelor și totalul actualizat, iar aceeași configurație și sumă sunt păstrate corect în coș, checkout și comandă. Nu se publică configuratorul pentru alte produse până când pilotul nu trece validarea comercială și tehnică.

## Milestone 5 — Galerie / Lookbook (Pixieset-style)

- [ ] Creează pagina "Galerie" folosind Modula (grid/masonry, fără chenare)
- [ ] Configurează lightbox fullscreen la click pe imagine
- [ ] Organizează galerii pe colecții/sezon (ex: Toamnă 2026, Living, Dormitor)
- [ ] Optimizează imaginile (WebP, dimensiuni corecte) pentru încărcare rapidă

## Milestone 6 — Pagini secundare

- [ ] Pagina "Despre noi" (poveste brand, imagini editoriale)
- [ ] Pagina "Contact" (formular simplu + hartă/detalii, minimalist)
- [ ] Completează paginile existente cu datele Brodart confirmate din [STORE-INFORMATION.md](STORE-INFORMATION.md) (telefon 0723 137 399 și adresa verificată înainte de publicare)
- [ ] Pagina "Întrebări frecvente" / Livrare & Retur
- [ ] Pagini legale (Termeni, Confidențialitate, Cookies)

## Milestone 7 — Rafinament & performanță

- [ ] Verifică consistența vizuală pe toate paginile (folosește checklist-ul din
      [STYLE-GUIDE.md](STYLE-GUIDE.md#10-reguli-de-consistență-checklist-înainte-de-a-publica-o-pagină-nouă))
- [ ] Testează site-ul pe mobil, tabletă, desktop
- [ ] Optimizează viteza (caching, imagini, minificare CSS/JS)
- [ ] Verifică SEO de bază (titluri, meta descrieri, alt text imagini)
- [ ] Testează fluxul complet de cumpărare (de la produs la comandă finalizată)

## Milestone 8 — Lansare

- [ ] Migrare pe hosting live + domeniu
- [ ] Configurare SSL (HTTPS)
- [ ] Configurare backup automat
- [ ] Test final end-to-end pe mediul live
