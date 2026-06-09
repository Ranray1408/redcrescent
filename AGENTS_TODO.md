# AGENTS_TODO.md — GSAP Scroll Animations (blocked / reverted / pending)

> Read this BEFORE reverting or restoring ANY animation.
> Exact specs, file paths, attribute lists, and JS code preserved here.

---

## Status: BLOCKED (awaiting client approval)

---

## 1. Animation Engine

### File: `wp-content/themes/white-list/src/js/utils/scroll-animations.js`

Two exported functions:

#### `initScrollAnimations()`
- Targets: `[data-anim]` elements
- Presets: `fade-up` (y:60, opacity:0), `fade-left` (x:60), `fade-right` (x:-60)
- Skips elements already in viewport on load (protects LCP)
- ScrollTrigger start: `"top 85%"` (overridable via `data-anim-start`)
- Delay: `data-anim-delay` (seconds, default 0)
- Duration: `data-anim-duration` (seconds, default 0.6)
- Ease: `power2.out`
- Animates only `transform` + `opacity` — no CLS

#### `initHeroAnimations()`
- Targets: `[data-anim-hero]` elements
- Uses timeline `gsap.timeline({ defaults: { duration: 0.55 } })`
- Class-specific effects:
  - `.donat-block__title` — scale:0.9, opacity:0.8, ease:`back.out(1.7)`
  - `.donat-block__right-wrapper` — x:30, opacity:0.85, ease:`power3.out`
  - All others — y:15, scale:0.97, opacity:0.88, ease:`power3.out`
- Staggers:
  - `.donat-block__icons-wrapper > .donat-block__icon-item` — stagger:0.07, y:20, scale:0.9, opacity:0
  - Form fields (`.donat-block__form-title`, `__form-radio-period-wrapper`, `__form-fields-title`, `__form-radio-sum-wrapper`, `__input-wrapper`, `__checkbox`, `__submit-btn`) — stagger:0.04, y:18, opacity:0

### Import in `wp-content/themes/white-list/src/js/frontend.js`
```js
import { initScrollAnimations, initHeroAnimations } from "./utils/scroll-animations";
```
Called inside `onLoad()`:
```js
initScrollAnimations();
initHeroAnimations();
```

---

## 2. Animated Blocks — data-anim attributes

### `block-about-us.php` (DONE)
| Element | data-anim | data-anim-delay | Notes |
|---|---|---|---|
| `.about-us__title` (h2) | `fade-up` | — | |
| `.about-us__description` | `fade-up` | `0.1` | |
| `.about-us__block` | `fade-up` | — | Per repeater item |
| `.about-us__block-image` (figure) | `fade-left` / `fade-right` | — | `fade-left` on even, `fade-right` on odd (reverse-block) |
| `.about-us__block-inner` | `fade-right` / `fade-left` | — | Opposite direction from image |

### `block-donat-section.php` (DONE) — Hero block
| Element | data-anim | data-anim-delay | Notes |
|---|---|---|---|
| `.donat-block__title` | `data-anim-hero` | — | Title pops (back.out) |
| `.donat-block__description` | `data-anim-hero` | `0.1` | |
| `.donat-block__right-wrapper` | `data-anim-hero` | `0.15` | Slides right |
| `.donat-block__icons-wrapper > .donat-block__icon-item` | — | — | Staggered via JS (0.07s) |
| Form fields (list above) | — | — | Staggered via JS (0.04s) |

---

## 3. Blocks Pending Animation (if client approves)

For each block: add `data-anim` attributes to PHP templates. No JS changes needed.

### `block-how-it-works.php`
- `h2.how-it-works__title` → `data-anim="fade-up"`
- `.how-it-works__description` → `data-anim="fade-up"` + delay 0.1
- `.how-it-works__card` → `data-anim="fade-up"` (stagger via delay: 0.1 per card)
- `.how-it-works__card-icon-circle` → `data-anim="fade-left"` (or scale-in)
- `.how-it-works__card-inner` → `data-anim="fade-right"`
- `.how-it-works__col-image` → `data-anim="fade-right"`

### `block-our-team.php`
- `h2.our-team__title` → `data-anim="fade-up"`
- `.our-team__card` → `data-anim="fade-up"` (stagger)
- `.our-team__photo-wrapper` → `data-anim="fade-up"` (scale-in)
- `.our-team__name` → `data-anim="fade-up"` (per card)
- `.our-team__id` → `data-anim="fade-up"` (per card)
- `.our-team__button-wrapper` → `data-anim="fade-up"`

### `block-partnership-v2.php`
- `.section-direct-dialog__content` → `data-anim="fade-up"`

### `block-partnership.php`
- `.section-partnership__content` → `data-anim="fade-up"`
- First `.section-partnership__button` → `data-anim="fade-left"`
- Second `.section-partnership__button` → `data-anim="fade-right"`

### `block-donat-subscription.php`
- Clone of `block-donat-section.php` — apply same `data-anim-hero` attributes
- Title, description, icons-wrapper stagger, form fields stagger
- Note: uses `js-donation-subscription-form` class, not `js-donation-form`

### `block-faq.php`
- `h2.faq__title` → `data-anim="fade-up"`
- `.faq__item` → `data-anim="fade-up"` (stagger)

### `block-our-representatives.php`
- `h2.our-representatives__title` → `data-anim="fade-up"`
- `.our-representatives__card` → `data-anim="fade-up"` (stagger)
- `.our-representatives__card-icon-wrapper` → `data-anim="fade-up"` (or scale-in)

### `block-text-section.php`
- `h2.text-section__title` → `data-anim="fade-up"`
- `.text-section__content` → `data-anim="fade-up"` (delay 0.1)

### `block-results.php`
- `.block-results__title` → `data-anim="fade-up"`
- `.block-results__description` → `data-anim="fade-up"` (delay 0.1)
- `.block-results__report-button` (first) → `data-anim="fade-left"`
- `.block-results__report-button` (second) → `data-anim="fade-right"`
- `.block-results__report-item` → `data-anim="fade-up"` (stagger)
- `.block-results__achievements-item` → `data-anim="fade-up"` (stagger)

### `block-car-animation.php`
- **DO NOT TOUCH** — already has custom GSAP logic (CarAnimation class)

---

## 4. Build

```bash
yarn dev
# or
yarn prod
```

No new npm packages needed. GSAP v3.14.2 already in `package.json`.

---

## 5. Revert Instructions (for current session)

To remove animations and return to clean state:

1. Revert `block-about-us.php` — remove all `data-anim` attributes
2. Revert `block-donat-section.php` — remove all `data-anim-hero` attributes
3. Revert `frontend.js` — remove imports and calls for `initScrollAnimations()` / `initHeroAnimations()`
4. Delete or comment out `scroll-animations.js` (or keep — it's inert without calls)

To restore later: read this file, apply everything in reverse.

---

## 6. Notes

- GSAP was dead dependency (never imported) before this task. Now it's live.
- ScrollTrigger is registered via `gsap.registerPlugin(ScrollTrigger)` inside `scroll-animations.js`.
- Bundle increase: ~30-40 KB (gzip ~10 KB).
- All animations use only `transform` + `opacity` — zero CLS impact.
- LCP protection: elements in initial viewport skip `initScrollAnimations()`.
- Created 2026-06-09.
