# Reguli SEO — Brodart

Aceste reguli se aplică **pe orice pagină sau componentă** pe care o modifici, indiferent de task. SEO nu este un task separat — este o verificare obligatorie la fiecare atingere de cod sau conținut.

**Plugin SEO: Yoast SEO** (activ pe site). Meta title, meta description, sitemap XML, schema.org și social tags se gestionează prin Yoast — nu hardcoda meta tags în temă sau mu-plugin. Când modifici o pagină, verifică/completează câmpurile Yoast din editor sau prin meta keys (`_yoast_wpseo_title`, `_yoast_wpseo_metadesc`).

## 1. Titluri și meta

- **Title tag**: 50–60 caractere, include brandul și cuvântul cheie principal. Format: `Nume pagină | Brodart`. Se setează prin Yoast (snippet editor) sau șabloanele Yoast (Yoast → Settings → Content types).
- **Meta description**: 150–160 caractere, în română, cu apel la acțiune discret. Se setează prin Yoast; dacă lipsește, Yoast generează automat din conținut — nu lăsa automat pe pagini importante.
- **H1 unic**: o singură dată pe pagină, descriptiv, nu generic („Acasă" → „Perdele și draperii Brodart").
- **Ierarhie heading-uri**: H1 → H2 → H3, fără sărire de nivel (H1 → H3).

## 2. URL-uri și linkuri

- **Permalinkuri**: `/nume-pagina/` (fără `?p=`, fără `/2021/04/` pentru pagini).
- **Linkuri interne**: folosește texte descriptive, nu „click aici".
- **Linkuri externe**: `rel="noopener"` pe `_blank`.
- **Canonical**: Yoast setează automat canonical-ul; la pagini cu conținut similar, verifică că canonical-ul corect este setat în meta box-ul Yoast (tab Advanced).
- **Breadcrumbs**: Yoast are breadcrumbs integrate; dacă tema nu le afișează, folosește blocul/shortcode-ul Yoast, nu un breadcrumb custom.

## 3. Imagini

- **Alt text**: obligatoriu, descriptiv, în română. Nu „image1" sau gol.
- **Nume fișiere**: descriptive, cu cratime, în română sau engleză tehnică: `perdea-voile-alb-brodart.jpg`.
- **Dimensiuni**: specifică `width` și `height` pentru a preveni CLS (Cumulative Layout Shift).
- **Lazy loading**: pe toate imaginile sub fold.

## 4. Conținut

- **Lungime minimă**: pagini principale ≥ 300 cuvinte, produse ≥ 150 cuvinte.
- **Cuvinte cheie naturale**: „perdele", „draperii", „sisteme prindere", „Brodart" — integrate natural, nu forțat.
- **Duplicate**: evită conținut identic pe pagini diferite; folosește canonical dacă e necesar.

## 5. Tehnic

- **Mobile-first**: orice modificare trebuie să funcționeze pe 390px.
- **Core Web Vitals**: LCP < 2.5s, FID < 100ms, CLS < 0.1.
- **Schema.org**: Yoast generează automat `Organization`, `WebSite`, `BreadcrumbList` și `Article`; WooCommerce + Yoast generează `Product`. Nu adăuga schema duplicată în cod custom — extinde doar prin filtrele Yoast (`wpseo_schema_*`) dacă e nevoie.
- **Sitemap**: Yoast generează automat `sitemap_index.xml`; verifică că paginile/produsele noi apar în sitemap (nu sunt `noindex`).
- **Indexare**: paginile în lucru se setează `noindex` din meta box-ul Yoast; la publicare, revizuiește că sunt pe `index`.

## 6. Verificare rapidă înainte de finalizare

- [ ] Title și meta description completate în Yoast (nu lăsate pe automat pentru pagini importante)
- [ ] Analiza Yoast din editor e verde sau galben (fără erori roșii la lizibilitate/SEO)
- [ ] H1 unic și descriptiv
- [ ] Toate imaginile au alt text
- [ ] URL-ul este curat și descriptiv
- [ ] Pagina se încarcă în < 3s pe mobil
- [ ] Nu există erori 404 pe linkuri interne
- [ ] Pagina este `index` (nu `noindex`) dacă trebuie să apară în căutări
- [ ] Textul este în română, cu diacritice

## 7. Instrumente

- **Yoast SEO** (instalat): snippet editor, analiză conținut, sitemap, schema — primul punct de control la fiecare pagină.
- **Google Search Console**: verifică indexarea după modificări majore.
- **PageSpeed Insights**: testează paginile principale lunar.
