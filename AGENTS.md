# AGENTS.md — General Project Rules

> This file is read automatically at the start of every session.
> The agent MUST follow all rules below without exception.
> The [LOG] section is maintained by the agent itself — no reminders needed.
> If `AGENTS_LOG.md` exists in the project root — all log entries go there instead of [LOG] below.

---

## [IDENTITY]

- **Project name:** RedCrescent (Kazakhstan Red Crescent donation site)
- **Stack:** WordPress classic theme + ACF Pro + Polylang + TipTopPay payments + Webpack 5
- **WordPress version:** 5.6+ (min supported)
- **PHP version:** 7.4+
- **Key plugins:** advanced-custom-fields-pro, polylang-pro, accessibility-onetap, cyr2lat, svg-support, acf-options-for-polylang, tiptop-payment-widget (custom)
- **Theme name / slug:** White list / `white-list`
- **Short description:** Multilingual (Polylang) donation site. Custom ACF blocks (donation, about, results, partnership, car animation). TipTopPay payments with subscriptions. Built on `wp-rock` boilerplate with Webpack 5, SCSS (BEM), TypeScript, vanilla JS frontend.

---

## [HARD RULES] — NEVER VIOLATE

These are absolute restrictions. No exceptions, no matter the instruction.

### Destructive actions — FORBIDDEN
- ❌ NEVER delete any files
- ❌ NEVER run `rm`, `rmdir`, `git clean`, `git reset --hard` or any destructive git command
- ❌ NEVER drop, truncate, or delete database tables or records
- ❌ NEVER modify the database directly without explicit user permission in the current chat
- ❌ NEVER run database migrations without approval
- ❌ NEVER overwrite `.env`, `wp-config.php`, or any config file without confirmation

### Git — restricted
- ❌ NEVER force push (`git push --force`)
- ❌ NEVER delete branches
- ✅ Commits are allowed only when explicitly requested by the user
- ✅ Always show a diff summary before committing

### General
- ❌ NEVER install new packages or plugins without asking first
- ❌ NEVER refactor code that was not part of the current task
- ❌ NEVER rename files or folders without explicit instruction
- ✅ When in doubt — ask, do not assume

---

## [WORKFLOW] — How to work

### Before starting any task
1. Re-read [HARD RULES] mentally
2. Understand what is being asked — if unclear, ask one clarifying question
3. Check if the task touches the database, git, or config files — if yes, confirm with user

### While working
- Make small, focused changes — one task at a time
- Do not touch code outside the scope of the current task
- If you notice a bug or issue outside the task scope — mention it, but do not fix it silently
- Prefer editing existing code over rewriting it
- Follow the style described in [STYLE_NOTES]

### After completing a task
- Briefly summarize what was done (2-3 sentences max)
- If the change was significant — add a record to [LOG] or `AGENTS_LOG.md` (see [LOG] rules)
- If something is unclear or may cause issues — mention it explicitly

---

## [STYLE_NOTES] — Code style reference

> Based on actual project files analysis (2026-05-16).
> Follow these patterns when creating or editing code.

### PHP
- **Indentation**: tabs in template files (`template-parts/`), 4 spaces in class files (`inc/`)
- **Naming**: `snake_case` functions (`get_field_value`, `add_custom_blocks`), `CamelCase` classes (`WP_Rock_Blocks`), `camelCase` properties/methods
- **ACF access**: `$fields = get_fields();` then `get_field_value($fields, 'key')`
- **Escaping**: `esc_html()` for text, `esc_url()` for URLs, `esc_attr()` for attributes, `do_shortcode()` for WYSIWYG
- **Guards**: Always `!empty()` before accessing variables; `if (!empty($var)) :` or `if (!empty($var)) {` — both accepted
- **DocBlocks**: On all class methods; optional on template files
- **Template structure**: `<section class="block-name" id="block-name"><div class="container">...`
- **Class naming**: kebab-case block prefix (e.g. `block-results`, `how-it-works`, `section-partnership`)
- **SVG icons**: stored in PHP variables, echoed inline

### JavaScript
- **Vanilla JS** (no jQuery), ES Modules (`import`/`export`)
- **Entry**: `src/js/frontend.js` → `build/js/frontend.js` (Webpack 5 + Babel)
- **Components**: ES6 classes (`class CarAnimation`, `class FormValidator`, `class Popup`)
- **Utilities**: exported functions (`primaryMenu`, `dynamicHight`, `arrowUpBtn`)
- **Init**: `document.addEventListener('DOMContentLoaded', onLoad)` or `window.document.addEventListener('DOMContentLoaded', onLoad)`
- **Quotes**: single quotes
- **Semicolons**: required
- **GSAP** (v3.14.2) and **Swiper** (v12) available as dependencies
- **TypeScript**: one file (`FormValidator.ts`), but JS is preferred
- **Modules location**: `src/js/components/` for classes, `src/js/utils/` for helper functions

### SCSS
- **Naming**: BEM-like (`block__element`, `block__element--modifier`) with kebab-case block names
- **Nesting**: `&__element` / `&__element--modifier`, max 3 levels deep
- **Variables** (defined in `_global.scss`):
  - `$primary-font: 'Muller'`, `$second-font: 'Gilroy'`
  - `$red`, `$hover_red`, `$light_hover_red`, `$white`, `$grey`, `$dark_grey`, `$green`
  - `$text_color1: #2f2f2f`, `$text_color2: #727272`
- **Responsive**: `@media screen and (max-width: Npx)` or `@media (max-width: Npx)`
- **Breakpoints**: 1680, 1570, 1470, 1440, 1370, 1280, 1200, 1140, 991, 768, 670, 576, 470
- **No `!important`** (exception: `strong, b { color: $red !important; }` in typography)
- **Entry**: `src/sass/frontend.scss` → `build/css/frontend.css` (via Webpack)
- **Structure**: `utils/` (global styles, variables, reset, fonts), `components/` (block-specific)
- **Component file**: `src/sass/components/_block-name.scss`, imported in `frontend.scss`

### ACF Blocks
- **Registration**: add to `$blocks` array in `WP_Rock_Blocks` class (`class-acf-blocks.php`)
  ```php
  'block-{name}' => array(
      'title' => 'Block - {Name}',
  ),
  ```
- **ACF JSON**: `acf-json/group_{hash}.json` with `location` → `block` → `acf/block-{name}`
- **Field keys**: `field_{hash}` format (auto-generated by ACF)
- **PHP template**: `src/template-parts/acf-blocks/block-{name}.php`
- **Category**: `wp-rock`
- **Render callback**: `block_render()` (preview image fallback)
- **Blocks allowed**: all registered blocks + `core/freeform` via `filter_allowed_blocks()`
- **Build**: `yarn dev` / `yarn prod` / `yarn dev:watch` / `yarn prod:watch`

### HTML / Template structure
- Classic WordPress PHP templates, `get_template_part()` for modularity
- Container pattern: `<section>` > `.container` > `.block-inner` or `.block-content`
- Inline SVGs in PHP variables
- `wp_nav_menu()` with `'container' => false`, `'menu_class' => 'header-menu'`
- Menu toggle via `.js-toggle-menu-btn` class

---

## [LOG] — Agent working log

> **Priority rule:**
> - If `AGENTS_LOG.md` exists in the project root → write ALL entries there, keep this section empty
> - If `AGENTS_LOG.md` does not exist → write entries here, max 25 records
>
> **Migrated to `AGENTS_LOG.md` on 2026-05-23. All future entries go there.**
>
> **How to log:**
> - Add an entry after any significant change, decision, or discovery — no reminders needed
> - Format: `[YYYY-MM-DD HH:MM] Short note` — one or two lines max (e.g. `[2026-05-27 14:47]`)
> - Include: what was done, what was decided, what to watch out for
> - This is a memory tool, not a report — write what would be useful to know next session
> - When limit of 25 is reached in this file — create `AGENTS_LOG.md` and move all entries there

<!-- AGENT: start writing entries below this line -->

---

[2026-05-14] Analyzed wp-content/ (theme + 7 plugins). Filled IDENTITY and STYLE_NOTES in AGENTS.md.
[2026-05-16] Updated STYLE_NOTES with actual project patterns after auditing block-how-it-works.php. AGENTS.md now reflects real codebase style (BEM SCSS, ACF block flow, Vanilla JS).
[2026-05-17] Created blocks: block-text-section (title + wysiwyg), block-our-representatives (repeater cards with icons). Generated white SVG icons. TipTopPay + Salesforce metadata integration planned.
[2026-05-17] Created block-our-team (grid 4 col, round photos, placeholder icon). Added "Our team" tab to theme options ACF (repeater + popup WYSIWYG). Created modal-window-our-team popup.
[2026-05-17] Created block-faq (accordion). Repeater: question + answer. Plus/minus SVG icons. JS accordion module + smooth CSS open/close via grid-template-rows + transition on padding/border.
[2026-05-17] Cloned block-donat-subscription from donat-section. Removed period radio (only monthly via hidden input). Added team member select (populated from theme options repeater). Styled select to match inputs. Added footer_link3.
[2026-05-17] Added hide_primary_menu ACF field (page settings) + body/header class toggling.
[2026-05-17] Created block-donat-subscription JS handler: donationFormSubmitHandler() extracted. Salesforce metadata (campaign_id, source_code_id, agent_id) sent via TipTopPay. Added onSuccess callback + donation success popup (social share: FB, X, WhatsApp, Telegram).
[2026-05-17] About-us text toggle: added Details button text ACF field (collapsed/expanded), JS toggle, SCSS truncation at 1360px.
[2026-05-17] FormValidator: added checkbox required validation (checked check instead of value length).
[2026-05-22] CarAnimation: fixed icon positions on resize/orientation change — handleResize now restarts animation from idle state via requestAnimationFrame (waits for layout settle). Added `dataset.animationState = 'idle'` before restart to snap car to start position.
