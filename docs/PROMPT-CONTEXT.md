# Context proiect: Brodart

Site WordPress cu o direcție vizuală premium, minimalistă, inspirată de Zara Home, H&M Home și Westwing. Galeriile urmăresc o experiență curată, de tip Pixieset.

- Local: `C:\xampp\htdocs\brodart\`
- URL: `http://localhost/brodart/`
- Stack: WordPress, Blocksy, Blocksy Companion, WooCommerce, Modula Gallery, Gutenberg, **Yoast SEO** (activ — meta title/description, sitemap XML, schema.org și breadcrumb-urile se configurează prin Yoast) și **Loco Translate** pentru șirurile gettext netraduse ale temei și pluginurilor. Site-ul rămâne într-o singură limbă (`ro_RO`), fără selector; conținutul și slugurile URL se traduc direct în WordPress, deoarece Loco nu modifică pagini sau URL-uri.
- Tipografie globală: **Arial**, familia folosită de meniul de referință, se aplică uniform textului, titlurilor, butoanelor și formularelor; detaliile sunt în [STYLE-GUIDE.md](STYLE-GUIDE.md#3-tipografie).

## Documentație

Fișierele din `docs/` sunt sursa de referință a proiectului. Consultă documentele relevante pentru task; nu duplica în acest fișier regulile detaliate definite în ghiduri.

- [STYLE-GUIDE.md](STYLE-GUIDE.md): identitate vizuală și consistență.
- [DESIGN-SYSTEM.md](DESIGN-SYSTEM.md): specificații pentru componente.
- [COMPONENT-INVENTORY.md](COMPONENT-INVENTORY.md): componente deja existente.
- [PAGE-TEMPLATES.md](PAGE-TEMPLATES.md): structura paginilor.
- [UI-PATTERNS.md](UI-PATTERNS.md): tipare de interacțiune și layout.
- [CONTENT-GUIDELINES.md](CONTENT-GUIDELINES.md): vocea brandului și redactare.
- [IMAGE-GUIDELINES.md](IMAGE-GUIDELINES.md): imagini și utilizarea lor.
- [STORE-INFORMATION.md](STORE-INFORMATION.md): informații despre magazin, surse de conținut și referințe externe.
- [BLOCKSY-RULES.md](BLOCKSY-RULES.md): responsabilitățile Blocksy, Gutenberg și codului custom.
- [ROADMAP.md](ROADMAP.md): milestone-uri și progresul proiectului.
- [SEO-RULES.md](SEO-RULES.md): reguli SEO aplicate pe orice pagină sau componentă modificată.
- [MOBILE-AUDIT-AND-RECOMMENDATIONS.md](MOBILE-AUDIT-AND-RECOMMENDATIONS.md): audit mobil și recomandări de evaluat înainte de implementare.

Documentul specific domeniului taskului guvernează detaliile acelui domeniu. Dacă două reguli par să intre în conflict, verifică ghidurile implicate și păstrează intenția lor; nu inventa reguli noi.

## Reguli de lucru

- Verifică milestone-ul și starea taskurilor din `ROADMAP.md` înainte de a începe.
- Inspectează implementarea existentă și reutilizează sau extinde componentele înainte de a crea altele.
- Respectă ghidurile relevante pentru design, structură, conținut, imagini și platformă.
- Folosește un plugin de traduceri (recomandat: **TranslatePress**) configurat să blocheze schimbarea limbii și să mențină întregul site exclusiv în limba română.
- Scrie implicit în română, cu diacritice, tot textul destinat vizitatorilor; orice text nou sau modificat trebuie tradus în română și nu trebuie lăsat conținut în engleză, franceză sau alte limbi.
- Site-ul trebuie să fie 100% tradus în română, inclusiv textul din pagini, butoane, formulare, banner-uri, căsuțe informative, metadate, mesaje de eroare, căsuțe de cookie și traduceri de plugin-uri, fără opțiune de schimbare a limbii.
- Aplică regulile din `SEO-RULES.md` pe orice pagină sau componentă pe care o modifici, indiferent de task.
- Nu modifica header-ul sau footer-ul global fără o cerere explicită.
- Păstrează schimbările la nivelul minim necesar și actualizează documentația când taskul schimbă decizii sau progresul proiectului.
- Daca ai nelamuriri intreaba-ma inainte sa faci modificariile
