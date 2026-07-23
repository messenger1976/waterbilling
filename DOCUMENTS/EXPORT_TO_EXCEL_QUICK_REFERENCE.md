# Export to Excel - Quick Reference Guide

## Quick Start

### For Users
1. Go to **Daily Collection Report** page
2. Fill in required fields:
   - Transaction Date
   - Zone (or "All")
   - Prepared by, Verified by, Approved by
3. Click **"Export to Excel"** button (green button)
4. File downloads automatically as CSV

### File Name Format
```
Daily_Collection_Report_DD-MM-YYYY.csv
```

## Technical Quick Reference

### Controller Method
**File:** `application/modules/master/controllers/adddailyreport.php`  
**Method:** `exporttoexcel($trans_date, $zone, $preparedby, $verifiedby, $approvedby)`

### URL Format
```
/master/adddailyreport/exporttoexcel/{date}/{zone}/{preparedby}/{verifiedby}/{approvedby}
```

### Key Dependencies
- CSV Helper: `application/helpers/csv_helper.php`
- Models: `adddailyreport_model`, `leakingentry_model`

### Data Structure
1. Header (Title + Date)
2. Column Headers (13 columns)
3. Zone Sections (with subtotals)
4. Leaking A/R Section
5. Grand Totals
6. Signature Section

### Columns Exported
1. OR #
2. Concessionaires
3. Total Amount Collected
4. Current
5. Arrears
6. Previous Year
7. WMMF
8. Penalty
9. SC Disc
10. Leaking Disc
11. A/R-Leaking
12. A/R-Leaking Balance
13. VAT

## Common Code Snippets

### Date Conversion
```php
$date_parts = explode('-', $trans_date);
$trans_date_mysql = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
```

### Export Call
```php
array_to_csv($export_data, $filename);
```

### Number Formatting
```php
number_format($value, 2)
```

## Files Modified
- ✅ `application/modules/master/views/adddailyreport_add.php` (Button + JS)
- ✅ `application/modules/master/controllers/adddailyreport.php` (Export method)

## Notes
- Uses same query as Print preview
- CSV format (Excel-compatible)
- Includes all zones, transactions, and totals
- Includes signature section
