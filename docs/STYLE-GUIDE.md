# Ghid de Stil — Brodart

Sursa unică de adevăr pentru identitatea vizuală a site-ului. Orice pagină, componentă
sau bloc nou trebuie să respecte acest ghid, astfel încât întregul site să arate coerent,
indiferent de pagină (Acasă, Colecție/Shop, Produs, Galerie, Contact etc).

Inspirație: **Zara Home**, **H&M Home**, **Westwing** — minimalism premium, mult spațiu
alb, imagini mari, tipografie elegantă. Galeriile foto (lookbook) se inspiră din
**Pixieset** — grid curat, full-bleed, fără distragere vizuală.

## 1. Filosofia de design

- Minimalism de lux ("quiet luxury"), nu maximalism.
- Imaginea este eroul paginii — textul și UI-ul stau în plan secund.
- Foarte mult spațiu alb (whitespace) — nu îngrămădi elemente.
- Culori neutre, naturale. Fără culori stridente/saturate.
- Tranziții și hover-uri subtile, fluide (200-400ms, ease-in-out), niciodată bruște.
- Consistență totală: același header/footer, aceleași fonturi, aceiași butoane pe
  toate paginile (Home, Shop, Product, Blog, Galerie, Contact).

## 2. Paletă de culori

| Rol                  | Cod       | Utilizare                                   |
| -------------------- | --------- | ------------------------------------------- |
| Background principal | `#FFFFFF` | Fundal general al paginii                   |
| Background secundar  | `#F8F7F5` | Secțiuni alternante, carduri, footer        |
| Text principal       | `#1A1A1A` | Titluri, text de bază                       |
| Text muted           | `#6B6B6B` | Subtitluri, descrieri, meta info            |
| Border / divider     | `#E5E3E0` | Linii separatoare, borduri de input/carduri |
| Accent (opțional)    | `#A9A296` | Detalii discrete (badge-uri, linkuri hover) |

Reguli:

- Nu se folosesc culori saturate (roșu, albastru electric, verde neon etc).
- Contrastul text/fundal trebuie să respecte minim AA (WCAG) pentru accesibilitate.
- Aceleași variabile de culoare se aplică global din Customizer → Global Colors (Blocksy),
  nu hardcodat per pagină/bloc.

## 3. Tipografie

- Familie de fonturi: 1 font serif elegant pentru titluri (ex: **Playfair Display**,
  **Cormorant**, sau **Marcellus**) + 1 font sans-serif curat pentru text (ex:
  **Poppins**, **Inter**, **Jost**).
- Scară tipografică (desktop):
  - H1: 48–64px, line-height 1.1, letter-spacing -0.5px
  - H2: 32–40px, line-height 1.2
  - H3: 24–28px, line-height 1.3
  - Body: 16px, line-height 1.6
  - Small / meta: 13–14px, letter-spacing 0.5px, uppercase pentru label-uri (ex:
    "NOU", "COLECȚIE 2026")
- Titlurile pot fi scrise cu spațiere mai mare între litere (letter-spacing) pentru un
  aer premium.
- Nu amesteca mai mult de 2 familii de fonturi în tot site-ul.

## 4. Spațiere & grid

- Container maxim: 1280–1400px, centrat, cu padding lateral 24px (mobil) / 64px (desktop).
- Spațiere verticală între secțiuni: minim 96px pe desktop, 48px pe mobil.
- Grid produse: 3–4 coloane desktop, 2 coloane tabletă, 1 coloană mobil.
- Galerii (Modula): grid fără spații mari între imagini (gap 4–8px) sau full-bleed,
  gen Pixieset — nicio bordură, niciun umbră puternică pe imagini.

## 5. Butoane & elemente interactive

- Border-radius: 0–4px (colțuri drepte sau ușor rotunjite, niciodată "pill").
- Fără gradient. O singură culoare solidă (de obicei text `#1A1A1A` pe fundal alb,
  sau invers pentru butonul primar).
- Umbre minime sau deloc (`box-shadow` foarte discret, ex: `0 1px 3px rgba(0,0,0,0.06)`).
- Stări hover: schimbare de fundal/culoare text, tranziție 250ms ease, fără scalare bruscă.
- Text buton: uppercase, letter-spacing 1px, font-weight 500-600, ex: "ADAUGĂ ÎN COȘ".

## 6. Imagini

- Toate imaginile produs/hero trebuie să fie de rezoluție mare, aspect ratio consistent
  (ex: 4:5 pentru produse, 16:9 sau full-width pentru hero-uri).
- Fără filtre/culori artificiale — imagini naturale, lumină bună, fundal neutru.
- Lazy-loading activat pentru toate imaginile sub fold.
- Hover pe imagine produs: fade discret către a doua imagine (dacă există) sau zoom
  foarte ușor (scale 1.03, tranziție 400ms).

## 7. Header & Footer (identice pe toate paginile)

- Header: logo centrat sau în stânga, meniu orizontal minimalist, iconițe subțiri
  (cont, wishlist, coș). Fundal alb/transparent peste hero, cu scroll → fundal solid.
- Footer: fundal secundar `#F8F7F5`, coloane simple (Despre, Ajutor, Newsletter,
  Social), text muted, fără aglomerare vizuală.
- Header și footer se editează o singură dată (Blocksy → Header/Footer Builder) și
  se moștenesc automat pe toate paginile — nu se recreează per pagină.

## 8. Componente WooCommerce (Shop)

- Galeria de produs: imagine mare, thumbnails mici sub/lateral, zoom la hover.
- Informația despre produs: titlu, preț, descriere scurtă, variații (mărime/culoare)
  afișate curat, fără etichete stridente.
- Checkout simplificat: cât mai puține câmpuri, progres clar (Coș → Livrare → Plată),
  fără elemente de distragere (fără reclame/pop-up-uri în checkout).
- Badge-uri reduceri/stoc: discrete, colț de imagine, text mic uppercase.

## 9. Galerii (Pixieset-style, plugin Modula)

- Layout tip masonry sau grid uniform, fără chenare.
- Click pe imagine → lightbox fullscreen, fundal negru/aproape negru, fără UI excesiv.
- Fiecare galerie/colecție are propriul titlu + descriere scurtă deasupra grid-ului,
  în același stil tipografic ca restul site-ului.

## 10. Reguli de consistență (checklist înainte de a publica o pagină nouă)

- [ ] Folosește doar culorile din paleta de mai sus (nicio culoare hardcodată nouă).
- [ ] Folosește doar fonturile definite global (Customizer → Typography).
- [ ] Header/Footer identice cu restul site-ului (nu suprascrise per pagină).
- [ ] Butoanele respectă stilul definit la secțiunea 5.
- [ ] Spațierea între secțiuni respectă grila de la secțiunea 4.
- [ ] Imaginile sunt optimizate (WebP dacă posibil) și au aspect ratio consistent.
- [ ] Pagina e testată pe mobil, tabletă, desktop.
