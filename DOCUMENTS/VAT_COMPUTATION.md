# VAT Computation Guide (waterbilling1)

This document describes how VAT (Value Added Tax) is computed in the waterbilling1 payment flow (Add Payment form).

---

## Table of Contents

1. [Where VAT Is Used](#1-where-vat-is-used)
2. [VAT Computation Logic](#2-vat-computation-logic)
3. [Base Amount for VAT](#3-base-amount-for-vat)
4. [VAT Amount and Grand Total](#4-vat-amount-and-grand-total)
5. [When There Is Already a Penalty](#5-when-there-is-already-a-penalty)
6. [Difference from labasonsandbox](#6-difference-from-labasonsandbox)
7. [Code Reference](#7-code-reference)
8. [Note on Leaking Percent Handler](#8-note-on-leaking-percent-handler)

---

## 1. Where VAT Is Used

- **Module:** Add Payment (customer payment entry).
- **View:** `application/modules/master/views/addpaymentcustomer_add.php`
- **Form fields:** `#vat_percent` (user enters %, e.g. 12), `#vat_amount` (readonly, computed), `#grand_total` (readonly, computed).
- **Model:** `addpaymentcustomer_model.php` saves `vat_percent` and `vat_amount` from the form; it does not compute them. All computation is in the view’s JavaScript.

VAT is **not** used in meter-reading or billing computation; it is only applied when recording a payment.

---

## 2. VAT Computation Logic

The only place that **computes** VAT is the **`#vat_percent` blur** handler in `addpaymentcustomer_add.php` (around lines 900–928).

When the user enters or changes the VAT % and leaves the field:

1. The script reads the chosen **base amount** (see below).
2. It subtracts **leaking amount** from the base if present.
3. It computes **VAT amount** = base × (vat_percent / 100).
4. It computes **grand total** = base − VAT amount + leaking_balance.
5. It updates `#vat_amount` and `#grand_total`.

---

## 3. Base Amount for VAT

The base for VAT is **either** `total_total_amount` **or** `paid_total_amount`:

| Condition | Base used | If leaking_amount > 0 |
|-----------|-----------|------------------------|
| `total_total_amount != 0` | `total_total_amount` | Base = total_total_amount − leaking_amount |
| `total_total_amount == 0` | `paid_total_amount`   | Base = paid_total_amount − leaking_amount |

- **Single-bill payment:** `total_total_amount` is set to 0 and `paid_total_amount` is set to the row’s amount (line 756–757).
- **Total pay (multiple bills):** `total_total_amount` is set to 0 and `paid_total_amount` is set to the combined total (line 854–855).

So in the usual single-bill case, the VAT base is **`paid_total_amount`** (minus leaking amount when applicable).

---

## 4. VAT Amount and Grand Total

### VAT amount

- **Formula:** `vat_amount = base × (vat_percent / 100)`
- In code: `taxdeduct = (base * taxpercent) / 100`, then `#vat_amount` is set to `taxdeduct`.

### Grand total

- **When using total_total_amount:**  
  `grand_total = total_total_amount - taxdeduct + parseFloat(leaking_balance)`
- **When using paid_total_amount:**  
  `grand_total = paid_total_amount - taxdeduct + parseFloat(leaking_balance)`

So VAT is applied as a **deduction** from the base; leaking balance is **added** to get the amount due.

---

## 5. When There Is Already a Penalty

- In waterbilling1 there is **no** `recalculatePenaltyIfNeeded()` or any logic that updates `paid_total_amount` when the user changes the transaction date.
- **`paid_total_amount` is whatever was set when the payment modal was opened** (from the list/row).
- So:
  - If the row sends **“amt after due date”** (amount that already includes penalty), then VAT is computed on that penalized amount.
  - If the row sends **“amt before due date”**, then VAT is computed on that amount.
- Whether “there is already a penalty” in the amount is entirely determined by **what value the list passes** when opening the Add Payment form, not by transaction date or any recalculation in this view.

---

## 6. Difference from labasonsandbox

| Aspect | waterbilling1 | labasonsandbox |
|--------|----------------|----------------|
| Penalty on payment | No automatic recalculation when transaction date or VAT % changes. | `recalculatePenaltyIfNeeded()` runs (e.g. on trans date and before VAT); it can set `paid_total_amount` = base + 10% penalty when trans date > due date and no special privilege. |
| VAT base when penalty applies | Whatever amount was loaded into `paid_total_amount` when the modal opened (may already be “amt after due date” if the list sends it). | VAT base is the **updated** `paid_total_amount`, which may have been just recalculated to include penalty. So VAT is explicitly computed on the penalized amount when applicable. |

So in waterbilling1, “VAT when there is already a penalty” simply means: VAT is computed on whatever `paid_total_amount` is at the time the user blurs VAT % — and that value may or may not already include penalty, depending on how the list/row populates the modal.

---

## 7. Code Reference

| Item | File | Location |
|------|------|----------|
| VAT % blur handler | `application/modules/master/views/addpaymentcustomer_add.php` | ~lines 900–928 |
| Saving vat_percent, vat_amount | `application/modules/master/models/addpaymentcustomer_model.php` | e.g. lines 442–443, 603–604 (from POST) |
| paid_total_amount set (single bill) | `addpaymentcustomer_add.php` | ~line 756 |
| paid_total_amount set (total pay) | `addpaymentcustomer_add.php` | ~line 855 |
| total_total_amount set | `addpaymentcustomer_add.php` | ~lines 757, 854 |

---

## 8. Note on Leaking Percent Handler

In the **`#leaking_percent` blur** handler (around lines 930–948), the **else** branch sets:

```javascript
var grand_total = paid_total_amount - taxdeduct;
```

`taxdeduct` is only set inside the **`#vat_percent` blur** handler. It is not declared or set in the leaking_percent handler, so it may be **undefined or stale** when this branch runs. For consistent behavior, this branch may need to use the current `#vat_amount` value (or recompute VAT) instead of relying on `taxdeduct`.

---

*Document based on the waterbilling1 codebase. Last updated: February 2025.*
