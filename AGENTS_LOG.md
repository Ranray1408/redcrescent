# AGENTS_LOG.md — Session log & project memory

> Created 2026-05-23 per AGENTS.md [LOG] rules.
> All new log entries go here. AGENTS.md [LOG] section is now deprecated.

---

## [2026-05-23] CarAnimation resize fix + Hide footer menu items feature

### CarAnimation resize — session 1
- **Problem:** After screen rotation/resize, icons flew to wrong positions, height was off.
- **Root cause:** `handleResize` called `getBoundingClientRect()` during resize event — browser hadn't finished layout reflow yet, returned stale coordinates.
- **Iteration 1:** Wrapped recalculation in `requestAnimationFrame` — still had car position issue (transition from old position instead of clean restart).
- **Iteration 2 (final):** On resize, restart animation entirely: `reset()` → `dataset.animationState='idle'` (snaps car to -100vw without transition) → `calcIconsDefaultPosition()` → `start()`. Clean cycle with recalculated positions.
- **Build:** `yarn dev` — OK.

### Hide footer menu items — session 2
- **Goal:** Allow hiding specific WordPress nav menu items on specific pages via admin UI.
- **Architecture:** ACF multi-select field (`hide_footer_menu_items`) on page settings + `wp_nav_menu_objects` filter.
- **ACF JSON:** Added field `select` + `multiple: 1` + `ui: 1` to `group_697f7d1c9e1f4.json`.
- **Bug:** `pll_get_post_language()` inside `acf/load_field` caused white screen in admin — Polylang not always available during ACF field loading (sync, AJAX, new page).
- **Fix:** Removed Polylang dependency; iterate `wp_get_nav_menus()`, filter by `Footer menu` name prefix, build choices from all languages with `(EN)`/`(RU)` labels.
- **ACF sync:** Had wrong `modified` timestamp (set to 2025 instead of 2026) — sync wasn't offered. Fixed to current timestamp.
- **actions.php hooks added:** `acf/load_field/name=hide_footer_menu_items` + `wp_nav_menu_objects` filter.
- **Build:** `yarn dev` — OK.

### Project state
- CarAnimation: stable — resize restarts animation cleanly.
- Hide footer menu items: working — admin selects items on page edit, they're filtered server-side from all nav menus.
- No regressions detected.

---

## [2026-05-23] Custom footer links per page

### Task
- Add toggle "Show custom footer links" + 3 link fields (footer_link1/2/3) to page settings JSON.
- When toggle ON → use page-level footer links; when OFF → fallback to theme options global links.

### ACF JSON edits (`group_697f7d1c9e1f4.json`)
- Added `show_custom_footer_links` (true_false, ui:1, labels "Custom"/"Global").
- Added `footer_link1`, `footer_link2`, `footer_link3` (link type, conditional on toggle ON).
- Updated `modified` timestamp.

### PHP logic (`custom-footer.php`)
- `$show_custom_links = get_field('show_custom_footer_links');`
- `$links_src = $show_custom_links ? get_fields() : $global_options;`
- All three links read from `$links_src` uniformly.
- Build: `yarn dev` — OK.

### Note
- ACF link fields (`return_format: "array"`) return `['title' => ..., 'url' => ..., 'target' => ...]` — same as theme options, so `get_field_value()` works for both sources. No template rendering changes needed.

---

## [2026-05-23] Donation success popup texts → ACF options

### Task
- Move hardcoded title/subtitle from `modal-window-donation-success.php` into ACF theme options as editable fields.
- Add "Share popup" tab in theme options.

### ACF JSON (`group_694a9c528913f.json`)
- Added tab "Share popup" after "Our team" tab.
- Added `donation_share_title` (text, default "Я сделал пожертвование", translate).
- Added `donation_share_subtitle` (text, default "Расскажите об этом друзьям", translate).
- Updated `modified` timestamp.

### PHP template (`modal-window-donation-success.php`)
- Title/subtitle now read from `$global_options`.
- Share URLs (Twitter, WhatsApp, Telegram) also use dynamic `$share_title` instead of hardcoded string.
- Wrapped in `!empty()` guards.
- Build: `yarn dev` — OK.

### Historical note
- This popup was originally created 2026-05-17 alongside donation success handler + Salesforce metadata, but never logged in AGENTS_LOG.md.

---

## [2026-05-23] Donat section: text/hover_text → Swiper text slider per icon

### Task
- Replace static `text` + `hover_text` fields (CSS hover-reveal) with a repeater of texts displayed as Swiper autoplay slider per icon card.
- Each icon has its own set of texts (per-icon repeater `icon_texts`).

### ACF JSON (`group_6909fa496434c.json`)
- Removed sub_fields: `field_692aee07d87ee` (Text), `field_695162fb58e04` (Hover text).
- Added sub_field `icon_texts` (repeater) with `text` (text) inside the "Icons" repeater.
- Updated `modified` timestamp.

### PHP (`block-donat-section.php`)
- Guard changed from `empty($icon['text'])` to just `empty($icon['sum'])`.
- Replaced `<p>text</p><p>hover_text</p>` with Swiper container `.swiper > .swiper-wrapper > .swiper-slide`.
- Each slide renders `$slide['text']`.

### SCSS (`_donat-section.scss`)
- Removed `.donat-block__icon-item:hover` hover rules.
- Removed `.donat-block__icon-item-hover-text` (deleted from template).
- Simplified `.donat-block__icon-item-text` — removed position/transform/opacity/transition.
- Simplified `.donat-block__icon-item-text-wrapper` — removed overflow:hidden (Swiper handles it).

### JS (`helpers.js`)
- Added `import Swiper from 'swiper/bundle'` + `import 'swiper/css/bundle'` at top.
- Added `donationTextSlider()` function — initializes Swiper with slidesPerView:1, autoplay (4s delay, disableOnInteraction:false), horizontal direction, no loop.

### JS (`frontend.js`)
- Replaced `dynamicHight` import with `donationTextSlider`.
- Replaced `dynamicHight('.js-icon-item-text-wrapper')` call with `donationTextSlider()`.
- Build: `yarn dev` — OK (Swiper v12 bundled, CSS extracted).

### Notes
- Each icon card gets its own Swiper instance (currently 3, max 5-6).
- No images in slides — only text, zero performance concern.
- Pagination not included per user request.
- After ACF Sync, admin must re-enter texts in the new `icon_texts` repeater per icon.

---

## [2026-05-14 to 2026-05-22] Previous sessions

### Sessions (migrated from AGENTS.md [LOG])
- [2026-05-14] Analyzed wp-content/ (theme + 7 plugins). Filled IDENTITY and STYLE_NOTES.
- [2026-05-16] Updated STYLE_NOTES with actual project patterns.
- [2026-05-17] Created block-text-section, block-our-representatives. SVG icons.
- [2026-05-17] Created block-our-team + theme options tab + modal-window-our-team.
- [2026-05-17] Created block-faq (accordion) with JS module + CSS transitions.
- [2026-05-17] Cloned block-donat-subscription, added team member select, footer_link3.
- [2026-05-17] Added hide_primary_menu ACF field + body/header class toggling.
- [2026-05-17] Donation form JS handler + Salesforce metadata + donation success popup.
- [2026-05-17] About-us text toggle: ACF field, JS toggle, SCSS truncation.
- [2026-05-17] FormValidator: checkbox validation fix.
- [2026-05-22] CarAnimation: initial resize fix (RAF debounce).

---

## [2026-05-24] Donat section Swiper fixes — overflow, CSS, height, staggering

### Swiper CSS was missing
- **Problem:** Slides stacked vertically, both visible at once, `swiper-wrapper` had no `display: flex`, `swiper` had no `overflow: hidden`.
- **Root cause:** `import 'swiper/css'` was never added to `frontend.js`. Swiper JS was imported but its default CSS wasn't bundled.
- **Fix:** Added `import 'swiper/css'` to `frontend.js` (not in helpers.js — entry point is the canonical place for global CSS deps).

### 3.35544e+07px overflow bug
- **Problem:** Swiper slides got enormous width (~33 million px) inside grid columns.
- **Root cause:** Flex/grid items have `min-width: auto` by default — they can't shrink below their content size. Chain: `grid (1fr) → icon-item → sum-inner (flex-col) → swiper-wrapper (flex-row) → swiper-slide (flex-shrink:0, width:100%)`. Browser in intrinsic pass couldn't resolve the circular width dependency → slide got unbounded width.
- **Fix:** `min-width: 0` on `.donat-block__icon-item`, `.donat-block__icon-item-sum-inner`, `.donat-block__icon-item-text-wrapper`.
- **Gotcha:** Previously worked without `min-width:0` because Swiper CSS wasn't loaded — no `display:flex` on wrapper meant no flex context, no overflow. After adding `swiper/css`, the bug appeared.

### Height jumping on autoplay
- **Problem:** `autoHeight: true` caused Swiper to resize container on slide change (79px → 132px), breaking grid layout.
- **Fix:** Removed `autoHeight: true`. Without it, Swiper keeps container height constant (tallest slide determines it).

### Staggered autoplay delay
- **Problem:** All sliders had same `delay: 3000` — transitioned simultaneously.
- **Fix:** `delay: 3000 + i * 300` (300ms offset per slider index).

### CSS cleanup for swiper slides
- Added `p { margin: 0 }` inside `&__icon-item-text-wrapper .swiper-slide` to remove default `<p>` margins affecting height calculation.

### Build
- `yarn dev` — OK.
- Files touched: `frontend.js`, `helpers.js`, `_donat-section.scss`.

### Applied same changes to Block - Donat subscription
- **ACF JSON** (`group_697f8d2c9e2f5.json`): Replaced old `text`/`hover_text` sub_fields with `icon_texts` repeater (same structure as donat-section). Updated `modified` timestamp.
- **PHP template** (`block-donat-subscription.php`): Replaced static text/hover_text rendering with Swiper slider (`js-donation-text-slider`). Removed `js-icon-item-text-wrapper` and `js-icon-item-text-wrapper` class references.
- **Build:** `yarn dev` — OK.
- Uses same `js-donation-text-slider` class — no new JS needed, `donationTextSlider()` handles instances from both blocks automatically.
- SCSS already shared via `_donat-section.scss` — no changes needed.

---

## Important decisions & gotchas

- `acf/load_field` hooks must not depend on `pll_get_post_language()` — causes WSOD in admin during ACF sync/AJAX. Use `wp_get_nav_menus()` + name filtering instead.
- ACF JSON `modified` timestamp must be *newer* than DB value for sync to appear. Use `Get-Date -UFormat %s` to get current Unix time.
- CarAnimation resize: must set `dataset.animationState='idle'` before restart — car's CSS `transition` would otherwise animate from old position instead of clean start from -100vw.
- Menu item IDs are globally unique (`nav_menu_item` post IDs) — safe to use across different menus without collisions.
- Swiper in flex/grid containers **requires** `min-width: 0` on every ancestor in the chain to prevent `3.35544e+07px` overflow bug — caused by `min-width: auto` intrinsic resolution cycle.
- Swiper v12 CSS (`import 'swiper/css'`) must be imported separately from JS — without it, `.swiper-wrapper` lacks `display: flex` and `.swiper` lacks `overflow: hidden`.
- `autoHeight: true` on Swiper with text sliders inside grid cards causes grid reflow on every slide change — use only when height stability isn't critical.
