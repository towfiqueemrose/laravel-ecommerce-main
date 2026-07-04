# Checkout Redesign Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Modernise the checkout page (two‑column layout) and apply Roboto font globally.

**Architecture:** Inject Google Fonts link in the master Blade layout; set `font-family` on `body`. Update the checkout view's inline `<style>` block with modern card/input/button styles. Keep all colours unchanged.

**Tech Stack:** Laravel Blade, Bootstrap 4/5, Google Fonts (Roboto)

---

### Task 1: Add Roboto font globally

**Files:**
- Modify: `resources/views/webview/master.blade.php`

- [ ] **Step 1: Add Google Fonts `@import` in `<head>`**

Insert the following line right after the favicon `<link>` (line 11):

```html
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
```

**Expected:** The `<head>` now contains the Google Fonts stylesheet.

- [ ] **Step 2: Set `font-family` on `body`**

Inside the existing `<style>` block (lines 16-57), add this rule after any existing body‑level styles:

```css
body {
    font-family: 'Roboto', sans-serif;
}
```

**Expected:** Every page on the site renders text in Roboto.

---

### Task 2: Modernise checkout inline styles

**Files:**
- Modify: `resources/views/webview/content/cart/checkout.blade.php`

Replace the entire `<style>` block (lines 207-301) with the following CSS. This retains spinner behaviour, improves card/input/button appearance, and keeps responsive adjustments for smaller screens.

**Step 1: Replace `<style>` content**

From line 207 to 301, replace with:

```html
    <style>
        .spinner {
            display: none;
        }

        #orderConfirm {
            background: #00b09b;
            color: #fff;
            font-size: 20px;
            font-weight: 600;
            padding: 14px 28px;
            border: none;
            border-radius: 8px;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 176, 155, 0.3);
            width: 100%;
            text-transform: uppercase;
        }

        #orderConfirm:hover {
            background: #009688;
            box-shadow: 0 6px 20px rgba(0, 176, 155, 0.45);
            transform: translateY(-2px);
        }

        #orderConfirm:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(0, 176, 155, 0.3);
        }

        #orderConfirm:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .card.mb-4,
        .orderDetails .card {
            border: none;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.3rem;
        }

        .form-control {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 10px 14px;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            border-color: #00b09b;
            box-shadow: 0 0 0 3px rgba(0, 176, 155, 0.2);
        }

        table.table.border-bottom tr {
            border-bottom: 1px solid #eee;
            transition: background 0.15s;
        }

        table.table.border-bottom tr:hover {
            background: #f8f9fa;
        }

        @media only screen and (min-width: 768px) {
            #proName {
                font-size: 18px;
            }

            #proPrice {
                font-size: 18px;
                padding: 6px;
                padding-left: 0;
            }

            .input-number {
                height: 39px;
            }

            #proDelCart {
                width: 30px;
                padding-top: 2px;
                font-size: 20px;
            }

            #proImgDiv {
                max-width: 110px;
            }

            #proImg {
                max-width: 100px;
            }
        }

        @media only screen and (max-width: 767px) {
            .input-group--style-2 .input-group-btn>.btn {
                background: 0 0;
                border-color: #e6e6e6;
                color: #818a91;
                font-size: 8px;
                padding-top: 6px;
                padding-bottom: 6px;
                cursor: pointer;
            }

            .input-number {
                height: 26px;
            }

            #proDelCart {
                width: 30px;
                font-size: 18px;
            }

            #proImg {
                max-width: 50px;
            }
        }
    </style>
```

- [ ] **Step 2: Verify no JS breakage**

Open `http://127.0.0.1:8000/checkout` with a product in the cart.
- The form fields should have a green focus ring.
- The order button should be solid green (not gradient).
- Card blocks should have a light shadow.
- Quantity increment/decrement and delete should still work.

---

### Task 3: Final verification

- [ ] **Step 1: Run a quick lint**

If a Blade linter or style linter is configured, run it to catch syntax issues.

- [ ] **Step 2: Visual check**

Load the homepage (to confirm Roboto is applied globally) and the checkout page (to confirm all new styles render correctly).

---

**Done.** The checkout page now has a more professional, modern look while keeping the original colour scheme.
