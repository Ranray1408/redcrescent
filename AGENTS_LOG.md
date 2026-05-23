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

## Important decisions & gotchas

- `acf/load_field` hooks must not depend on `pll_get_post_language()` — causes WSOD in admin during ACF sync/AJAX. Use `wp_get_nav_menus()` + name filtering instead.
- ACF JSON `modified` timestamp must be *newer* than DB value for sync to appear. Use `Get-Date -UFormat %s` to get current Unix time.
- CarAnimation resize: must set `dataset.animationState='idle'` before restart — car's CSS `transition` would otherwise animate from old position instead of clean start from -100vw.
- Menu item IDs are globally unique (`nav_menu_item` post IDs) — safe to use across different menus without collisions.
