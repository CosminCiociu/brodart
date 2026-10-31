# Instrucțiuni Claude Code --- Header magazin Brodart

## Obiectiv

Construiește și configurează headerul magazinului WordPress **Brodart**,
folosind tema **Blocksy** și WooCommerce.

Headerul trebuie să transmită imaginea unui brand premium de perdele și
draperii, cu o estetică minimalistă, editorială și elegantă. Ca direcție
vizuală, inspiră-te din Zara Home, H&M Home și Westwing, fără a copia
exact interfețele sau identitatea lor.

Prioritatea este claritatea navigării și prezentarea produselor.
Headerul trebuie să fie discret și să nu concureze vizual cu
fotografiile.

## Implementarea actuală și referințele noi

Headerul folosește Blocksy Header Builder, fără un header custom. Compoziția
din referința desktop este implementată: logo centrat și controale în dreapta
pe rândul principal, „Meniu” în stânga și navigația existentă centrată pe
rândul separat de dedesubt. Pe mobil, rândul unic păstrează hamburgerul,
logo-ul centrat, căutarea și coșul; contul este accesibil din drawer.
Logo-ul are o înălțime maximă de 44 px, rândul principal desktop de 76 px,
iar rândul navigației de 50 px.

Headerul transparent pe pagina principală și sticky-ul cu micșorare la
scroll sunt păstrate. Meniul „Colecții” are deja un dropdown cu imagini
pentru subcolecții; nu crea un al doilea meniu sau un dropdown duplicat.

Referințele din `docs/references/NewDesignHeaderPhotos/` rămân sursă de
inspirație pentru compoziție, nu pentru etichete, categorii sau conținut de
copiat:

- Desktop: rândul cu meniul în stânga, logo centrat și căutare, cont și coș
  în dreapta, urmat de navigația centrată pe rând separat; această compoziție
  este aplicată. Banda informativă opțională nu este afișată fără o ofertă
  comercială confirmată.
- Mobil: un singur rând cu hamburger, logo centrat, căutare și coș; această
  compoziție este aplicată, iar contul rămâne accesibil din drawer.
- Imaginile cu mega-menu sunt referințe de compoziție editorială, nu
  surse pentru etichete, categorii sau conținut de copiat. Păstrează
  structura reală a magazinului și dropdown-ul cu imagini existent.

Textul „Livrare gratuită pentru comenzi de peste 500 lei” apare într-o
machetă, dar nu este confirmat ca informație comercială. Nu îl publica și
nu deduce un prag de livrare din imagine; bara rămâne ascunsă până când
magazinul confirmă un mesaj real.

Bara promoțională rămâne ascunsă până la confirmarea unui mesaj comercial
real. Nu modifica etichetele și destinațiile meniului real pentru a le
potrivi cu macheta; păstrează paginile și categoriile existente.

## Înainte de modificări

1.  Inspectează implementarea actuală: tema activă, child theme (dacă
    există), Header Builder Blocksy, meniurile WordPress și pluginurile
    care influențează headerul.
2.  Verifică dacă există deja CSS, PHP hooks sau setări Blocksy
    personalizate pentru header.
3.  Identifică sursa fiecărui element înainte să modifici ceva. Nu crea
    elemente duplicate.
4.  Păstrează funcționalitatea WooCommerce și compatibilitatea cu
    actualizările temei.
5.  Nu modifica fișierele core ale WordPress, WooCommerce sau Blocksy.
6.  Înainte de schimbări ample, salvează configurația relevantă și
    explică pe scurt ce urmează să modifici.

## Structura desktop recomandată

Headerul are maximum trei zone:

### 1. Bara informativă --- opțională

-   O bandă foarte discretă, cu fundal ivory sau bej foarte deschis.
-   Poate afișa un mesaj real, de exemplu: „Livrare gratuită pentru
    comenzi de peste X lei".
-   Nu inventa praguri, reduceri sau condiții comerciale. Folosește doar
    informații confirmate.
-   Pragul de 500 lei din macheta nouă nu este confirmat și nu trebuie
    folosit ca text publicitar.
-   Dacă nu există un mesaj comercial relevant, ascunde complet această
    bandă.

### 2. Rândul principal

Ordinea recomandată:

-   Stânga: meniu hamburger sau un acces rapid către meniu, dacă este
    necesar.
-   Centru: logo-ul BRODART.
-   Dreapta: căutare, contul clientului și coșul WooCommerce.

Cerințe:

-   Logo-ul trebuie să fie elementul central vizual.
-   Folosește logo-ul existent. Nu recrea logo-ul prin text dacă există
    deja un fișier de identitate vizuală.
-   Păstrează proporțiile logo-ului și nu îl deforma.
-   Iconurile trebuie să aibă aceeași greutate vizuală și dimensiuni
    coerente.
-   Coșul trebuie să păstreze indicatorul de număr de produse, dacă
    funcționalitatea există deja.
-   Căutarea trebuie să poată fi folosită ușor; la activare poate
    deschide un câmp de căutare sau overlay.
-   Evită elementele decorative fără funcție.

### 3. Navigația principală

Meniu recomandat, în funcție de categoriile care există efectiv în
magazin:

-   Acasă
-   Perdele
-   Draperii
-   Șine și galerii
-   Accesorii
-   Galerie
-   Contact

Nu crea pagini sau categorii noi doar pentru a completa meniul. Verifică
mai întâi structura existentă și adaptează etichetele la aceasta.

Navigația trebuie să fie aerisită, aliniată și ușor de parcurs. Pentru
„Perdele" și „Draperii" se poate folosi un mega-menu numai dacă există
suficiente subcategorii și imagini relevante. Altfel, preferă un
dropdown simplu.

## Direcție vizuală

### Paletă

-   Fundal principal: alb cald sau ivory, de exemplu `#FAF9F6`.
-   Text principal: gri foarte închis, de exemplu `#292929`.
-   Text secundar: gri neutru.
-   Separatoare: bej-gri foarte deschis, de exemplu `#EAE7E2`.
-   Accente: folosește cu moderație culorile deja definite în stilul
    Brodart.

Nu introduce gradienturi decorative, culori saturate sau umbre
pronunțate.

### Tipografie

-   Logo: folosește fișierul original.
-   Navigație și controale: font sans-serif lizibil, cu greutate normală
    sau medium.
-   Păstrează ierarhia tipografică discretă.
-   Nu folosi majuscule excesive sau spațiere exagerată între litere.

### Dimensiuni orientative

-   Înălțime totală header desktop, fără bara promoțională: aproximativ
    110--135 px.
-   Păstrează suficient spațiu alb în jurul logo-ului și al iconurilor.
-   Evită ca headerul să ocupe prea mult din primul ecran.

Aceste valori sunt orientative. Ajustează-le în funcție de logo, font și
layout-ul real.

## Comportament sticky

-   Activează un sticky header numai dacă experiența rămâne
    confortabilă.
-   La scroll, headerul poate deveni mai compact.
-   Nu permite suprapunerea peste conținut sau peste notificările
    WooCommerce.
-   Verifică dacă sticky headerul Blocksy este deja activ înainte de a
    implementa o soluție proprie.
-   Evită două mecanisme sticky simultane.

## Varianta mobilă

Pe mobil, păstrează o singură linie principală:

-   Stânga: meniu hamburger.
-   Centru: logo Brodart.
-   Dreapta: căutare și coș.

Cerințe:

-   Dacă accesul la cont nu este afișat ca icon în rândul mobil, păstrează-l
    accesibil din drawer; nu elimina funcționalitatea existentă. În
    configurația actuală, legătura „Autentificare” apare în drawer.
-   Păstrează logo-ul lizibil, fără să împingă iconurile în afara
    ecranului.
-   Meniul trebuie să se deschidă într-un drawer sau panou ușor de
    utilizat.
-   Linkurile și controalele trebuie să aibă zone de atingere suficient
    de mari.
-   Navigația desktop nu trebuie să se comprime într-un rând aglomerat.
-   Verifică la lățimi de aproximativ 360 px, 390 px și 430 px.
-   Previne scroll-ul orizontal.

## Accesibilitate și funcționalitate

-   Folosește elemente HTML semantice: `header`, `nav`, liste și
    linkuri.
-   Iconurile interactive trebuie să aibă etichete accesibile
    (`aria-label`) și stări de focus vizibile.
-   Toate elementele trebuie să poată fi utilizate cu tastatura.
-   Contrastul textului trebuie să fie suficient.
-   Căutarea trebuie să trimită către funcționalitatea reală de căutare
    WordPress/WooCommerce.
-   Contul trebuie să folosească pagina reală de cont WooCommerce, dacă
    este activată.
-   Coșul trebuie să folosească funcționalitatea WooCommerce existentă.
-   Nu înlocui linkurile reale cu `#` și nu lăsa iconuri decorative
    prezentate ca acțiuni.

## Reguli de implementare în Blocksy

1.  Folosește prioritar **Blocksy Header Builder** pentru structură și
    poziționare.
2.  Folosește setările responsive native Blocksy pentru desktop, tabletă
    și mobil.
3.  Folosește CSS personalizat doar pentru detalii pe care Header
    Builder nu le poate controla suficient.
4.  Dacă este necesar cod PHP, folosește child theme sau un mecanism
    sigur de snippets.
5.  Nu edita direct tema Blocksy părinte.
6.  Nu adăuga biblioteci JavaScript sau frameworkuri CSS suplimentare
    pentru un header simplu.
7.  Respectă stilurile globale și convențiile deja folosite în proiect.
8.  Nu suprascrie global stilurile WooCommerce sau ale altor componente
    fără un motiv clar.

## Proces de lucru

Lucrează incremental:

1.  Inspectează headerul actual și raportează ce elemente sunt deja
    configurate.
2.  Propune structura finală și identifică elementele care trebuie
    păstrate.
3.  Implementează structura desktop.
4.  Implementează varianta mobilă.
5.  Configurează comportamentul sticky, dacă este justificat.
6.  Verifică funcționalitatea căutării, contului și coșului.
7.  Testează paginile Acasă, Magazin, categorie de produse, produs și
    Coș.
8.  Verifică aspectul la desktop, tabletă și mobil.
9.  Elimină CSS-ul redundant și verifică să nu existe conflicte.

Nu face modificări masive într-un singur pas. După fiecare etapă,
verifică rezultatul înainte de a continua.

## Criterii de acceptare

Headerul este gata numai dacă:

-   [ ] Are un aspect minimalist, premium și coerent cu identitatea
    Brodart.
-   [ ] Logo-ul este centrat și afișat corect.
-   [ ] Navigația este clară și nu este aglomerată.
-   [ ] Căutarea, contul și coșul funcționează.
-   [ ] Indicatorul coșului reflectă datele WooCommerce.
-   [ ] Varianta mobilă este aerisită și funcțională.
-   [ ] Sticky headerul nu acoperă conținutul.
-   [ ] Nu există scroll orizontal pe mobil.
-   [ ] Nu există erori JavaScript sau PHP generate de modificări.
-   [ ] Nu au fost modificate fișiere core ale pluginurilor sau temei.
-   [ ] Headerul respectă stilurile globale ale site-ului.

## Ce trebuie să raportezi la final

Prezintă concis:

1.  Ce elemente ai modificat și unde sunt configurate.
2.  Ce setări au fost făcute în Blocksy Header Builder.
3.  Ce CSS sau PHP personalizat ai adăugat și în ce fișiere.
4.  Ce funcționalități ai testat.
5.  Ce aspecte au rămas neimplementate sau necesită confirmare.

**Principiu final:** headerul trebuie să susțină experiența de cumpărare
și identitatea premium Brodart. Simplitatea, spațiul alb, fotografiile
de calitate și navigarea intuitivă sunt mai importante decât efectele
vizuale.
