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

---

## [2026-06-06 13:00] Performance: LCP, CLS, render-blocking + WebP

### Font swap CLS (font-display: optional + preload)
- **Problem:** `font-display: swap` caused 0.869 CLS — browser rendered fallback text, then swapped to Muller/Gilroy fonts after download.
- **Fix:** Changed all 5 `@font-face` declarations in `_fonts.scss`: `font-display: swap → optional` (100ms block period, no swap).
- **Added preload** in `performance-optimizations.php` for all 5 woff2 fonts (`<link rel="preload" as="font" ... crossorigin>`).
- **Build:** `yarn dev` — OK.
- **Note:** Preload gives CWV-eligible Chrome priority; `optional` eliminates CLS entirely.

### Admin crash from frontend-only optimizations
- **Problem:** `is_admin()` not guarded — jQuery footer move + `defer` on script tags broke admin panel (`Cannot read properties of undefined (reading 'setLocaleData')`).
- **Fix:** Wrapped ALL hooks in `performance-optimizations.php` with `if (!is_admin()) : ... endif;`.

### Partnership bg-img: CSS `background-image` → `<img>` tag
- Converted `block-partnership.php` and `block-partnership-v2.php` from inline `style="background-image: url(...)"` to absolute-positioned `<img>` with `fetchpriority="high"` + `no-lazy skip-lazy`.
- Added `&__bg-img` SCSS (absolute, object-fit: cover, z-index: 0) in both `_partnership.scss` and `_partnership-v2.scss`.
- Removed mobile `display: none` on bg-img (no CSS fallback needed anymore).
- Same conversion done for `block-donat-section.php` and `block-donat-subscription.php`, then **reverted** — mobile layout broke (image overlapped other blocks).

### Light gray ::after overlay on partnership-v2
- Added `&::after` on `section-direct-dialog__block` — `rgba(200, 200, 200, 0.5)` light gray overlay.

### ACF image fields: `return_format` → `"id"` + `wp_get_attachment_image()`
- **Goal:** Generate `srcset`/`sizes` + enable WordPress 6.9 native WebP delivery.
- **Changed ACF JSON fields:**
  - `group_694a9c528913f.json` — `logo`, `white_logo`, `photo` (team_members)
  - `group_694d59c717fa5.json` — `background_image` (partnership)
  - `group_694d59c717fa6.json` — `background_image` (partnership-v2)
  - `group_694d25333faa0.json` — `image` (about-us block_repeater)
  - `group_695f2a1c4d5e6.json` — `block_image` (how-it-works)
- **Updated PHP templates to `wp_get_attachment_image()`:**
  - `block-partnership.php` — class/skip-lazy/fetchpriority preserved via `$attr` array
  - `block-partnership-v2.php` — same
  - `block-about-us.php` — inside `<figure>`, alt + decoding preserved
  - `block-how-it-works.php` — class/how-it-works__img + loading=lazy
  - `block-our-team.php` — class/our-team__photo + alt from name
  - `custom-header.php` — 2 logo outputs, `[298, 65]` size
  - `custom-footer.php` — white logo, class style-svg preserved
- **JSON corruption fix:** `group_694a9c528913f.json` had duplicate `ps_text` entry from wrong regex match — removed.

### Lighthouse remaining issues (logged for next steps)
- **Render-blocking (~3s):** jQuery (29 KiB), accessibility-onetap plugin (4 files), `white-list/style.css` (0.4 KiB empty), Google Fonts. No changes made.
- **Image compression:** Partnership bg 52.1 KiB, logo 16.1 KiB (592×208 on 240×65 container). WebP Express `Limit: 60` not yet applied; logo needs smaller `srcset` size.
- **WebP Express Alter HTML** — works with W3TC cache but only after page cache primed (first visit). `.htaccess` redirect is the reliable fallback.

### Font fallback with metric overrides (CLS reduction)
- Added `@font-face` declarations for `MullerFallback` and `GilroyFallback` in `_fonts.scss` — use `local('Arial')` with `size-adjust` / `ascent-override`.
- Updated `$primary-font` and `$second-font` in `_global.scss` to include fallback names (`'Muller', 'MullerFallback', ...` / `'Gilroy', 'GilroyFallback', ...`).
- Build: `yarn dev` — OK.

### Font fallback — split by weight (CLS fix)
- After deploy: CLS still 1.124. Cause: fallback had no `font-weight` (defaulted to 400). h1 (700) and `<p>` (500) didn't match → fell to Noto Sans → CLS persisted. Also `size-adjust` was changed to 104% making fallback LARGER.
- Fixed: replaced 2 generic `@font-face` blocks with 5 weight-specific ones (MullerFallback 400/500/700, GilroyFallback 400/700), each with its own `size-adjust` and `ascent-override`.
- `size-adjust` values need Chrome DevTools fine-tuning after deploy.

### CLS root cause found — async CSS loading
- Deployed fallback + `font-display: optional` didn't fix CLS (still 0.9+). Reason: **CSS loaded async** (`media="print"` onload swap).
- First paint happens without frontend.css → `<img class="donat-block__bg-img">` not yet `position: absolute` → takes up intrinsic space → when CSS loads, img snaps to absolute, content jumps up → CLS.
- Fix: removed async CSS loading from `performance-optimizations.php`, added `<link rel="preload" as="style">` to keep it fast but blocking.
- Also added `width="1920" height="800"` to the bg `<img>` for CLS hygiene.
- Build: `yarn dev` — OK.

---

## [2026-06-06] Two-stage (Dual) payment — research & test setup

### What is a two-stage payment (DMS)
- TipTopPay supports two schemes: **Single** (SMS) и **Dual** (DMS).
- **Single** — one command: authorization + debit immediately, money leaves the card right away.
- **Dual** — two separate commands: **authorization** (blocking) and **confirm** (capture/debit).
  - After authorization, the amount is **blocked** on the donor's card (unavailable to them).
  - Merchant has up to **7 days** to confirm (depends on card type).
  - If not confirmed — auto-cancel, funds unblocked.
  - Can confirm **full amount** or **partial amount**.
- Use case: deposits (rental, hotels); for donations — useful when you want to verify the card first and debit later (e.g., after Salesforce confirms).

### How to switch to Dual scheme
- In widget params set `paymentSchema: 'Dual'` (was `'Single'`).

### Confirm API
- **Endpoint:** `POST https://api.tiptoppay.kz/payments/confirm`
- **Auth:** HTTP Basic Auth — `Public ID` (login) + `API Secret` (password), different from Terminal ID (`pk_...`). Obtained from merchant cabinet.
- **Parameters:**
  - `TransactionId` (Long, required) — numeric transaction ID from TipTopPay.
  - `Amount` (Number, required) — amount to confirm, dot separator, 2 decimal places.
  - `JsonData` (JSON, optional) — extra data / online receipt instructions.
- **Response:** `{"Success":true,"Message":null}` on success.
- Can also confirm via merchant cabinet (manual).

### Key data fields — don't confuse
- **TransactionId** (numeric, e.g. `4673194557`) — TipTopPay internal transaction ID. Returned by widget in `result.data.transactionId`. Used for confirm.
- **externalId** (string, e.g. `payment_dual@gmail.com_06062026`) — your own order ID passed to widget. **Not** usable for confirm.
- **metadata** — optional JSON object passed to widget for Salesforce (campaign_id, source_code_id, agent_id, venue_id). Returned in notifications.

### Confirm flow (test setup we made)
1. `paymentSchema: 'Dual'` in `TipTopPaymentWidget.js`.
2. Auto-capture `transactionId` from widget result via monkey-patch on `TipTopPaymentWidget.prototype.launch`.
3. PHP AJAX proxy (`admin-ajax.php` action `tiptop_confirm_payment`) forwards request to TipTopPay API (avoids CORS). Accepts `transaction_id`, `amount`, `api_public_id`, `api_secret_key` from POST.
4. Test button below donation form sends confirm. API keys hardcoded in template JS (test only).

### Salesforce connection (from PDF docs)
- Two integration shapes:
  - **Face2Face** (2 calls): Step 1 — Salesforce OAuth → fetch active agents/venues → populate picklists. Step 2 — payment with `agent_id`, `venue_id`, `campaign_id`, `source_code_id` in metadata.
  - **Standard** (1 call): payment only, no F2F lookups, `campaign_id` + optional `source_code_id` in metadata.
- OAuth: Client Credentials flow, token server-side only (~2h validity). Cache responses 5–15 min.
- Agent validation: free-text input, validated client-side against cached list.
- Venues: picklist (name displayed, id as value).
- Missing keys silently ignored, donation always goes through.
- Salesforce reads metadata from TipTopPay webhook and links records.

### CORS note
- `api.tiptoppay.kz` does **not** allow browser-side requests (no CORS headers). All API calls must go through a server-side proxy (PHP `wp_remote_post`).

---

## [2026-06-09] AGENTS_TODO.md created — animation preservation

- Created `AGENTS_TODO.md` with full GSAP scroll animation specs (blocked state).
- Added `[HARD RULES]` section in `AGENTS.md` — "Animation recovery — vital" rule.
- Animated blocks: `block-about-us.php` (data-anim), `block-donat-section.php` (data-anim-hero).
- All pending blocks (9 more) documented in `AGENTS_TODO.md` for future restore.
- User plans to revert animations now; restore when client approves.

---

## [2026-06-06] Scroll animations with GSAP + ScrollTrigger

### What was done
- Created `src/js/utils/scroll-animations.js` — GSAP + ScrollTrigger utility for scroll-triggered fade-in animations.
- Imported & initialized in `src/js/frontend.js` (`initScrollAnimations()`).
- Added `data-anim` attributes to `block-about-us.php`:
  - Title: `fade-up`, Description: `fade-up` (delayed 0.1s).
  - Each block: `fade-up`. Image slides from left (`fade-left`) or right (`fade-right`) based on `reverse-block` class; inner content slides opposite direction.

### How it works
- `scroll-animations.js` exports `initScrollAnimations()` which queries `[data-anim]` elements and creates `gsap.fromTo()` + `ScrollTrigger` for each.
- Presets: `fade-up` (translateY 60px + opacity), `fade-left`, `fade-right`.
- Adjustable via `data-anim-delay` (delay in seconds) and `data-anim-start` (ScrollTrigger start, default `"top 85%"`).
- Animates only `transform` + `opacity` — no layout changes, no CLS impact.
- Initializes lazily — if no `[data-anim]` elements exist on page, does nothing.

### GSAP state in project
- GSAP v3.14.2 was already in `package.json` as a dependency but was **completely unused** (dead dependency).
- Now imported and used. Bundle size increase: ~30-40 KB (gzip ~10 KB).
- ScrollTrigger is a bundled plugin, imported separately as `import { ScrollTrigger } from 'gsap/ScrollTrigger'`.
- Built with `yarn dev` — OK.

### Usage for other blocks
Add `data-anim="fade-up"` (or `fade-left`/`fade-right`) to any element in any PHP template. Optionally add `data-anim-delay="0.2"`. That's it — no JS changes needed.
