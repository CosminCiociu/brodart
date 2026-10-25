# Audit mobil și recomandări — Brodart

Document de lucru pentru alegerea îmbunătățirilor mobile. Observațiile descriu
comportamentul verificat; recomandările sunt propuneri și nu au fost aplicate
site-ului.

## Audit efectuat

- Data: 25 octombrie 2026.
- Mediu: site local `http://localhost/brodart/`, browser integrat.
- Lățime cerută: 390 px; browserul a raportat `documentElement.clientWidth` de
  375 px. Rezultatele de mai jos sunt, așadar, pentru 375 px, nu pentru o
  verificare exactă la 390 px.
- Au fost parcurse cele 111 URL-uri unice din sitemapurile Yoast: 14 pagini,
  41 produse, 8 articole/arhive de jurnal, 8 arhive de colecție, 2 arhive de
  categorii de produse și 38 de arhive de categorii, atribute și autor.
- Toate URL-urile verificate au răspuns cu HTTP 200 și nu au prezentat overflow
  orizontal la lățimea testată.
- Paginile și produsele au fost verificate pentru structura H1; homepage-ul este
  singura pagină verificată fără H1.
- Pagina pilot de produs a fost verificată și vizual. Pe celelalte produse și
  arhive verificarea a urmărit răspunsul, H1 și overflow, nu toate interacțiunile
  individuale.
- Nu au fost testate la această verificare tableta, dispozitive iOS/Android
  reale, viteza pe rețele mobile sau fluxul complet de cumpărare.

## Ce funcționează deja bine

- Arhiva Magazin folosește o coloană pe mobil, conform [STYLE-GUIDE.md](STYLE-GUIDE.md).
- Cardurile de produs observate au raportul 4:5, iar imaginile galeriei produselor
  verificate au text alternativ completat.
- Headerul mobil păstrează logo-ul, accesul la cont și coș și meniul hamburger
  fără să depășească lățimea ecranului.
- Paginile și arhivele verificate nu au overflow orizontal la 375 px.

## Probleme observate — de triat înainte de rafinări

Pentru fiecare punct, bifează o singură opțiune după ce alegi direcția.

### M1 — Imaginea principală a produsului pilot nu apare la prima afișare

Pe pagina „Perdea Alana Crem T08”, fotografia principală vizibilă a rămas goală
după încărcarea paginii și în captura mobilă. Elementul are `loading="lazy"`,
dar `currentSrc` este gol și `naturalWidth` este 0; fișierul media local răspunde
cu HTTP 200. Recomandarea este să se verifice inițializarea încărcării imaginii
principale și să nu fie amânată imaginea aflată în primul ecran. Lazy-loading-ul
se păstrează pentru imaginile de sub fold.

- [ ] Adoptă
- [ ] Amână / respinge

### M2 — Homepage-ul nu are un H1

Homepage-ul nu are niciun element H1, deși ghidul SEO cere un singur H1
descriptiv. Recomandarea este ca titlul principal deja existent să fie H1,
fără a adăuga o secțiune nouă sau un titlu duplicat. Formularea poate urma
direcția „Perdele și draperii Brodart”.

- [ ] Adoptă
- [ ] Amână / respinge

### M3 — Sitemapul include pagini comerciale duplicate în engleză

Sitemapul conține atât paginile românești `/cos/`, `/finalizare-comanda/` și
`/contul-meu/`, cât și rutele `/cart-2/`, `/checkout-2/` și `/my-account-2/`,
care se afișează în engleză. `/request-quote/` este, de asemenea, publică și are
titlu în engleză. Recomandarea este să se confirme mai întâi paginile atribuite
în WooCommerce și dacă formularul de ofertă mai este necesar; apoi să rămână
publică o singură rută română pentru fiecare funcție. Paginile vechi pot fi
traduse, redirecționate sau scoase din indexare prin fluxurile WordPress/Yoast,
după confirmarea rolului lor.

- [ ] Adoptă
- [ ] Amână / respinge

### M4 — Jurnalul publică articole demo cu text de tip Lorem ipsum

Sitemapul include articole datate 2021, iar un articol verificat afișează text
demonstrativ în latină. Recomandarea este să se decidă pentru fiecare articol
dacă va fi rescris integral în română sau retras din publicare și din indexare.
Nu păstra text de umplutură doar pentru a completa arhiva.

- [ ] Adoptă
- [ ] Amână / respinge

### M5 — Dialogul de consimțământ și un element din footer sunt în engleză

La prima deschidere a dialogului de consimțământ au fost afișate în engleză
acțiunile și explicația. În footer există și un text alternativ în engleză
pentru imaginea legată de soluționarea online a litigiilor. Recomandarea este
traducerea textelor vizibile și a textelor alternative în română, folosind
setările pluginului sau Loco Translate unde este potrivit, fără a adăuga un
selector de limbă.

- [ ] Adoptă
- [ ] Amână / respinge

### M6 — Datele de contact nu sunt consecvente, iar formularul nu funcționează

Pagina Contact menționează adresa Strada Carpaților 93A și program de luni până
vineri; footerul afișează alt număr de telefon și program de weekend. Formularul
este înlocuit cu un mesaj că nu este disponibil. Informațiile proiectului
confirmă telefonul `0723 137 399`, dar adresa showroomului trebuie confirmată
înainte de publicare. Recomandarea este alinierea datelor după confirmare și
alegerea uneia dintre variante: repararea formularului sau afișarea clară a
canalelor directe de contact, cu linkuri telefon/e-mail ușor de folosit pe mobil.

- [ ] Adoptă
- [ ] Amână / respinge

### M7 — O imagine externă din footer raportează o eroare de certificat în mediul local

Browserul local a raportat `ERR_CERT_COMMON_NAME_INVALID` pentru o imagine
încărcată de pe `brod-art.ro`. Recomandarea este verificarea adresei și a
certificatului pe mediul live; dacă imaginea poate fi găzduită local în mod
autorizat, aceasta evită dependența de resursa externă. Nu dezactiva verificarea
certificatului SSL.

- [ ] Adoptă
- [ ] Amână / respinge

## Îmbunătățiri opționale de experiență mobilă

### O1 — Păstrează layout-ul aerisit și controalele ușor de atins

Păstrează o coloană pentru produsele din magazin, spațiere verticală de cel puțin
48 px între secțiuni și zona de conținut cu margini confortabile. Ca țintă de
design, urmărește controale de aproximativ 44 × 44 px, cu spațiu între ele,
stări de focus vizibile și etichete clare. Aceste ajustări pot folosi stilurile
Blocksy existente, fără un nou sistem de componente.

- [ ] Adoptă
- [ ] Amână / respinge

### O2 — Simplifică alegerea variantelor pe ecrane înguste

Verifică dacă toate opțiunile de culoare și dimensiune rămân lizibile și ușor de
selectat pe ecran mic. Dacă lista devine prea lungă, testează gruparea sau
restrângerea progresivă a opțiunilor, păstrând vizibile denumirea, selecția
curentă și efectul asupra prețului. Nu ascunde informațiile necesare comenzii.

- [ ] Adoptă
- [ ] Amână / respinge

### O3 — Optimizează imaginile mobile fără a sacrifica imaginea principală

Păstrează imaginile de produs cu raport 4:5, folosește dimensiuni responsive și
formate optimizate unde fișierele autorizate permit acest lucru. Încarcă
prioritar imaginea principală vizibilă și amână imaginile de sub fold. Păstrează
aspectul natural și tonurile calde/neutre din [IMAGE-GUIDELINES.md](IMAGE-GUIDELINES.md);
nu folosi filtre sau overlay-uri artificiale.

- [ ] Adoptă
- [ ] Amână / respinge

### O4 — Clarifică pe mobil pașii coșului și ai finalizării comenzii

După configurarea corectă a paginilor WooCommerce, păstrează un traseu simplu
„Coș → Livrare → Plată”, cu câmpurile strict necesare, sumarul comenzii ușor de
revizuit și mesaje de eroare în română. Evită bannerele promoționale și
pop-up-urile în checkout.

- [ ] Adoptă
- [ ] Amână / respinge

### O5 — Menține consistența vizuală fără accente noi

Folosește exclusiv paleta existentă: `#FFFFFF`, `#F8F7F5`, `#1A1A1A`,
`#6B6B6B`, `#E5E3E0` și, discret, taupe `#A9A296` / hover `#8B8175`.
Păstrează fonturile globale, butoanele cu colțuri drepte sau discret rotunjite și
tranzițiile subtile. Verifică contrastul AA, inclusiv pe textele mici și stările
dezactivate; nu introduce culori saturate sau stiluri „pill”.

- [ ] Adoptă
- [ ] Amână / respinge

### O6 — Refolosește comportamentele deja existente

Pentru filtre, păstrează dialogul mobil nativ al blocului WooCommerce, fără un
drawer duplicat. Pentru galerii, menține imaginile drept elementul central,
gridul curat și lightbox-ul discret prevăzut de ghid. Nu adăuga un buton fix
„Adaugă în coș” pentru configurator înainte de validarea fluxului și a regulilor
comerciale din Milestone 4A.

- [ ] Adoptă
- [ ] Amână / respinge

## Decizii după parcurgerea listei

- Recomandări adoptate:
- Recomandări amânate:
- Confirmări necesare înainte de implementare:
- Verificare următoare: 390 px, tabletă, desktop și un dispozitiv mobil real.

Orice implementare ulterioară trebuie să respecte [STYLE-GUIDE.md](STYLE-GUIDE.md),
[DESIGN-SYSTEM.md](DESIGN-SYSTEM.md), [BLOCKSY-RULES.md](BLOCKSY-RULES.md) și
[SEO-RULES.md](SEO-RULES.md). Headerul și footerul global nu se modifică fără
aprobare explicită.
