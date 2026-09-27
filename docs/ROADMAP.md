# Roadmap — Brodart (site tip Zara Home / H&M Home / Westwing)

Roadmap-ul e împărțit pe milestone-uri. Bifează fiecare milestone înainte de a trece
la următorul, ca să te asiguri că fundația (stil, structură) e solidă înainte de a
adăuga conținut.

## Milestone 0 — Fundație tehnică ✅ (făcut)

- [x] WordPress instalat local (XAMPP) în `brodart/`
- [x] Temă **Blocksy** instalată și activată
- [x] Plugin **Blocksy Companion** activat (header/footer builder, extensii)
- [x] **WooCommerce** instalat și activat
- [x] Plugin galerie **Modula** instalat și activat
- [x] Permalinkuri SEO-friendly configurate

## Milestone 1 — Design system global

- [x] Configurează **Global Colors** în Customizer conform [STYLE-GUIDE.md](STYLE-GUIDE.md#2-paletă-de-culori)
- [x] Configurează **Global Typography** (font titluri + font body) conform secțiunii 3
- [x] Setează container width, spacing, border-radius global (Customizer → Layout)
- [x] Alege/instalează fonturile (Google Fonts prin Blocksy) și verifică încărcarea rapidă
- [x] Creează un buton "primar" și unul "secundar" ca stiluri reutilizabile (Blocksy Content Blocks sau Global Blocks)

## Milestone 2 — Header & Footer (o singură dată, moștenite peste tot)

- [x] Creează meniul principal (Acasă, Magazin) și atribuie-l headerului desktop și mobil
- [ ] Configurează header-ul în Blocksy Header Builder (logo, cont, coș, wishlist)
- [ ] Comportament header: transparent peste hero → solid la scroll
- [ ] Construiește footer-ul (coloane: Despre, Ajutor, Newsletter, Social)
- [ ] Testează header/footer pe mobil (meniu hamburger elegant)

## Milestone 3 — Pagina de start (Home)

- [ ] Hero full-width cu imagine mare + titlu + CTA discret
- [ ] Secțiune "Colecții" (carduri mari, 2-3 categorii cheie, gen Zara Home)
- [ ] Secțiune produse recomandate (grid WooCommerce, 4 coloane)
- [ ] Secțiune editorială/lookbook (imagine + text, stil revistă)
- [ ] Secțiune newsletter (fundal secundar, minimalist)

## Milestone 4 — Shop & Produs (WooCommerce)

- [ ] Configurează WooCommerce Setup Wizard (monedă, livrare, plăți)
- [ ] Stilizează pagina de arhivă produse (grid, filtre discrete, sortare)
- [ ] Stilizează pagina de produs individual (galerie mare, info curată, variații)
- [ ] Simplifică pagina de coș și checkout (mai puține câmpuri, pași clari)
- [ ] Verifică emailuri de comandă (stil simplu, coerent cu brandul)

## Milestone 5 — Galerie / Lookbook (Pixieset-style)

- [ ] Creează pagina "Galerie" folosind Modula (grid/masonry, fără chenare)
- [ ] Configurează lightbox fullscreen la click pe imagine
- [ ] Organizează galerii pe colecții/sezon (ex: Toamnă 2026, Living, Dormitor)
- [ ] Optimizează imaginile (WebP, dimensiuni corecte) pentru încărcare rapidă

## Milestone 6 — Pagini secundare

- [ ] Pagina "Despre noi" (poveste brand, imagini editoriale)
- [ ] Pagina "Contact" (formular simplu + hartă/detalii, minimalist)
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
