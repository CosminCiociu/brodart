Existing Components:

- Hero Large
- Hero Split
- Product Card
- Gallery Card
- CTA Banner
- Testimonial Card

Shop archive (magazin, sidebar WooCommerce):

- Filter panel — bloc `woocommerce/product-filters` (widget block 11, sidebar-woocommerce)
  cu titluri în română: Filtre / Categorie / Culoare / Colecție + „Șterge filtrele".
  Filtrul Culoare e legat explicit de atributul global `pa_culoare` (`attributeId: 2`),
  filtrul Colecție de `pa_colectie` (`attributeId: 1`) — nu se bazează pe fallback-ul
  automat WooCommerce. Filtrul de preț a fost eliminat (toate textilele au același
  tarif pe metru liniar).
- „brodart-shop-tweaks" (mu-plugin) — traduceri ro_RO pentru șirurile de blocuri
  WooCommerce lipsă din pachetul oficial, sufixul „/ metru liniar" la prețurile
  produselor Mendola, badge „NOU" pentru produsele publicate în ultimele 30 de zile,
  hover cu a doua imagine pe cardul de produs, stiluri pentru badge-uri/chip-uri
  pe paleta din STYLE-GUIDE. Tot el ascunde titlul „Magazin" de pe pagina de magazin
  (layer-ul `custom_title` din hero e dezactivat doar când `is_shop()`; breadcrumbs
  și titlurile arhivelor de categorie rămân intacte — Blocksy ignoră opțiunile
  hero per-pagină pentru pagina de magazin, de aceea filtrul e în mu-plugin).

Before creating a component:

1. Search this file.
2. Reuse existing component.
3. Extend existing component.
4. Create new only if absolutely necessary.
