# Checkout Page Redesign – Modern & Professional

## Goal
Make the checkout page more beautiful and professional while preserving the existing colour palette. Apply the **Roboto** font globally via Google Fonts.

## Layout
- **Two‑column layout (Option A):** customer‑info form on the left, order summary on the right.
- Responsive: side‑by‑side ≥768 px, stacked on mobile.

## Visual Changes (colours unchanged)
| Element | Style |
|---------|-------|
| Cards | `border: none; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.08); padding: 1.5rem;` |
| Form inputs | `border: 1px solid #ced4da; border-radius: 6px; padding: 10px 14px;` focus → green ring |
| Order button | Single solid `#00b09b`, `border-radius: 8px`, hover darken |
| Table rows | `border-bottom: 1px solid #eee;` hover → `#f8f9fa` |
| Typography | `font-family: 'Roboto', sans-serif;` set globally in `<body>` |
| Spacing | Card margin → `1.5rem`, form groups → `gap: 0.5rem` |

## Files Affected
1. `resources/views/webview/master.blade.php` – Google Fonts link + body font-family
2. `resources/views/webview/content/cart/checkout.blade.php` – inline CSS block updated (or new `public/css/checkout.css`)

## JavaScript
All existing AJAX calls untouched. No new scripts needed.

## Implementation Steps
1. Add Google Fonts link and body font‑family in `master.blade.php`.
2. Add/update the `<style>` block in `checkout.blade.php` with the modern card/input/button styles.
3. Remove/replace old gradient on `#orderConfirm`.
4. Verify form submit, quantity update, and delivery‑charge recalculation.
5. Test responsive layout.
