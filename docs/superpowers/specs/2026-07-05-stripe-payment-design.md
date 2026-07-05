# Stripe Payment Gateway — Design Document

## Overview
Add Stripe as an online payment gateway to the Laravel e-commerce application, alongside existing Cash on Delivery. The checkout flow is restructured so the customer fills shipping info first, then chooses payment method on a dedicated payment page.

## Flow

```
Cart → Checkout (/checkout)
         → Fill name, address, phone, delivery area
         → POST /checkout/store → saves info in session
         → Redirect to /payment

Payment Page (/payment)
         ├── Cash on Delivery
         │     → POST /order/confirm-cod
         │     → Creates Order (status: "Processing", Payment: "Cash On Delivery")
         │     → Redirect to /order/complete
         │
         └── Pay with Stripe
               → POST /stripe/checkout
               → Creates Stripe Checkout Session
               → Redirect to Stripe hosted page

               ├── Success → Stripe redirects to /stripe/success?session_id=xxx
               │     → Verify session, create Order (status: "Paid", Payment: "Stripe")
               │     → Store stripe_payment_intent ID on order
               │     → Redirect to /order/complete
               │
               └── Cancelled → Stripe redirects to /stripe/cancel
                     → Redirect back to /payment with error message
```

## Routes (new/modified)

| Method | Path | Controller | Purpose |
|--------|------|-----------|---------|
| POST | `/checkout/store` | `CartController@storeCheckout` | Validate & store shipping in session |
| GET | `/payment` | `CartController@showPayment` | Show payment selection page |
| POST | `/order/confirm-cod` | `OrderController@confirmCod` | Create unpaid COD order |
| POST | `/stripe/checkout` | `StripeController@createCheckoutSession` | Create Stripe session, redirect |
| GET | `/stripe/success` | `StripeController@handleSuccess` | Verify payment, create paid order |
| GET | `/stripe/cancel` | `StripeController@handleCancel` | Redirect back to payment with error |
| POST | `/stripe/webhook` | `StripeController@handleWebhook` | (optional) Stripe webhook |

## Backend

### New Controller: `StripeController`
- `createCheckoutSession()` — create Stripe Checkout Session using cart data from session, set `success_url` and `cancel_url`
- `handleSuccess()` — verify Stripe session is `complete`, create order with `Payment = "Stripe"`, `status = "Paid"`, store `stripe_payment_intent`, clear cart
- `handleCancel()` — redirect back to `/payment` with `error=payment_cancelled`
- `handleWebhook()` — (optional) verify webhook signature, confirm order on `checkout.session.completed` event

### New Controller Method: `CartController@storeCheckout`
- Validate name, address, phone, delivery area
- Store in session as `checkout_info`
- Redirect to `/payment`

### New Controller Method: `CartController@showPayment`
- Pull cart from session, `checkout_info` from session
- Calculate totals (subtotal, delivery charge, grand total)
- Return `payment.blade.php` view

### Modified Controller Method: `OrderController@pressorder`
- Keep for backward compatibility / admin use
- (Frontend no longer calls this directly from checkout)

### New Controller Method: `OrderController@confirmCod`
- Pull `checkout_info` from session, cart contents
- Create `Order`, `Customer`, `Orderproduct` records (same logic as `pressorder`)
- Set `Payment = "Cash On Delivery"`, `status = "Processing"`
- Clear cart and session
- Redirect to `/order/complete`

### Order Model
- No schema changes needed — `Payment` and `status` columns already exist
- Add a new column `stripe_payment_intent` (nullable string) via migration (optional, for reference)
- Or just use the existing `payment_id` column to store the Stripe PaymentIntent ID

### Dependencies
```bash
composer require stripe/stripe-php
```

### Config
`.env` additions:
```
STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxxxxxxxxxx
```

`config/services.php` addition:
```php
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
```

## Frontend Views

### Modified: `checkout.blade.php`
- Replace form action from `/press/order` to `/checkout/store`
- Change submit button from "Confirm Order" to "Proceed to Payment"
- Keep all existing fields unchanged

### New/Replaced: `payment.blade.php`
- Order summary (cart items, quantities, prices, delivery charge, total)
- Customer info summary (name, address, phone, delivery area)
- Two payment option cards side by side:
  - **Cash on Delivery** card with "Confirm Order" button → POST `/order/confirm-cod`
  - **Stripe (Card)** card with "Pay with Stripe" button → POST `/stripe/checkout`
- Error/success flash messages
- Clean, minimal design matching existing theme

### Existing: `complete.blade.php`
- Update to show actual order details (invoice ID, items, payment method, total, status)
- Works for both COD and Stripe flows

## Order Status Mapping

| Payment Method | `Payment` column | `status` column |
|---------------|-----------------|-----------------|
| Cash on Delivery | `"Cash On Delivery"` | `"Processing"` |
| Stripe (paid) | `"Stripe"` | `"Paid"` |
| Stripe (cancelled/failed) | Order not created | N/A |

## Edge Cases & Error Handling

- **Empty cart at payment time** — redirect back to cart with message
- **Missing checkout info in session** — redirect back to checkout
- **Stripe payment fails / expires** — redirect to `/payment` with flash error
- **Stripe webhook idempotency** — check if order already exists for this session ID
- **Concurrent checkout** — cart is in session, natural per-user isolation
