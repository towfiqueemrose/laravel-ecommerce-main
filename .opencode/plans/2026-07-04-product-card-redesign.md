# Product Card Redesign

## Goal
Modernize product cards with a Flipkart-inspired look. Extract duplicated HTML into a reusable partial, switch to AJAX add-to-cart, eliminate invalid HTML. No dummy data — only real database fields.

## Current Problems
1. Card HTML copy-pasted across 7+ Blade templates (view, slugview, subview, maincontent ×3, details ×2, brandproduct, mainsearch)
2. Duplicate `id` attributes (`product_colorold`, `qtyor`) create invalid HTML
3. `<form>` POST add-to-cart causes full page reloads
4. Visual design is dated

## Solution

### 1. Reusable Partial
Create `resources/views/webview/partials/product-card.blade.php` accepting a `$product` variable with optional parameters:

| Parameter | Default | Purpose |
|-----------|---------|---------|
| `$product` | required | Product model instance |
| `$action` | `'addtocart'` | JS function to call on button click (`addtocart` or `buynow`) |
| `$buttonText` | `'Add to Cart'` | Button label text |

- **Image** — clickable, uses `$product->ViewProductImage ?? $product->ProductImage`
- **Badge** — `-{Discount}%` top-left, solid red
- **Actions overlay** — button revealed on hover (always visible on mobile), calls the configured JS function
- **Name** — max 2 lines, links to product page
- **Price block** — sale price (bold, theme color), old price (strikethrough), calculated %-off tag

### 2. CSS (BEM naming)
Replace `.product-card` block in `style.css`:

| Class | Purpose |
|-------|---------|
| `.product-card` | Card container, white, subtle border, flex column, 12px radius |
| `.product-card:hover` | Elevated shadow, translateY(-5px) |
| `.product-card__image` | Relative, overflow hidden, bg #fafafa |
| `.product-card__image img` | 100% width, 200px height, object-fit cover, hover zoom |
| `.product-card__badge` | Absolute top-left, solid red bg, rounded |
| `.product-card__actions` | Absolute at bottom of image, hidden, shown on card hover, flat dark bg |
| `.product-card__add-cart` | Full-width button, theme-color bg, white text |
| `.product-card__info` | Padding 12px, flex 1 |
| `.product-card__name` | 14px semibold, 2-line clamp |
| `.product-card__price` | Flex row, gap 6px, align center |
| `.product-card__price-sale` | 17px bold, theme-color |
| `.product-card__price-old` | 13px gray, line-through |
| `.product-card__price-off` | Orange/red tag, 11px bold |

### 3. Files to Create
- `resources/views/webview/partials/product-card.blade.php`

### 4. Files to Modify
1. `public/webview/assets/css/style.css` — replace `.product-card` block (lines 1807–1942)
2. `resources/views/webview/content/maincontent.blade.php` — 3 instances
3. `resources/views/webview/content/product/view.blade.php`
4. `resources/views/webview/content/product/slugview.blade.php`
5. `resources/views/webview/content/product/subview.blade.php`
6. `resources/views/webview/content/product/details.blade.php` — 2 instances
7. `resources/views/webview/content/product/brandproduct.blade.php`
8. `resources/views/webview/content/product/mainsearch.blade.php`

### 5. Usage Pattern
Each template replaces the duplicated card block with:
```blade
@include('webview.partials.product-card', ['product' => $loopVariable])
```

For `mainsearch.blade.php` (uses buynow/checkout flow):
```blade
@include('webview.partials.product-card', [
    'product' => $categoryproduct,
    'action' => 'buynow',
    'buttonText' => 'Order Now'
])
```

### 6. Mobile
- No hover on touch — button always visible
- Image height: 180px mobile, 200px desktop
- Smaller padding/font on mobile

## Implementation Order
1. Create the partial file
2. Update CSS in style.css
3. Update each template (8 files) to use `@include`
4. Verify
