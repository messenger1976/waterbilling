# Export to Excel Feature Documentation

## Overview
This document describes the Export to Excel functionality added to the Daily Collection Report module. The feature allows users to export the same data and layout that appears in the Print preview to an Excel-compatible CSV file.

## Feature Description
The Export to Excel button has been added to the Daily Collection Report page (`/master/adddailyreport`). It uses the same query and layout structure as the Print button preview, ensuring data consistency between the two export methods.

## Files Modified

### 1. View File: `application/modules/master/views/adddailyreport_add.php`
**Changes:**
- Added "Export to Excel" button next to the Print button
- Added JavaScript event handler for the export button click

**Location:** Line 108-109
```php
<a id="printtopdf" class="btn btn-sm btn-warning" style="margin-bottom: 5px;">Print</a>
<a id="exporttoexcel" class="btn btn-sm btn-success" style="margin-bottom: 5px;">Export to Excel</a>
```

**JavaScript Handler:** Lines 473-485
- Validates that a transaction date is selected
- Redirects to the export URL with all required parameters

### 2. Controller File: `application/modules/master/controllers/adddailyreport.php`
**New Method:** `exporttoexcel()`
- **Location:** Lines 48-310
- **Parameters:** 
  - `$trans_date` - Transaction date (dd-mm-yyyy format)
  - `$zone` - Zone ID (optional, defaults to all zones)
  - `$preparedby` - Employee ID who prepared the report
  - `$verifiedby` - Employee ID who verified the report
  - `$approvedby` - Employee ID who approved the report

## Functionality

### Data Structure
The export includes the following sections, matching the Print preview layout:

1. **Header Section**
   - Report Title: "DAILY COLLECTION REPORT"
   - Transaction Date (formatted as "M d, Y")

2. **Column Headers**
   - OR #
   - Concessionaires
   - Total Amount Collected
   - Current
   - Arrears
   - Previous Year
   - WMMF
   - Penalty
   - SC Disc
   - Leaking Disc
   - A/R-Leaking
   - A/R-Leaking Balance
   - VAT

3. **Zone-wise Data**
   - Zone header row
   - Individual transaction rows for each customer
   - Zone subtotal row

4. **Leaking A/R Payment Report Section**
   - Individual leaking A/R transaction rows
   - Leaking A/R total row

5. **Grand Total Row**
   - Summary of all zones and leaking A/R payments

6. **Signature Section**
   - Prepared by (Employee name and job title)
   - Verified by (Employee name and job title)
   - Approved by (Employee name and job title)
   - Date/Time printed

### Data Processing Logic

The export method follows the same logic as the Print preview:

1. **Date Format Conversion**
   - Converts input date from `dd-mm-yyyy` to MySQL format `Y-m-d`
   - Handles date parsing for database queries

2. **Zone Processing**
   - Retrieves zones based on the selected zone filter
   - Processes each zone separately
   - Calculates zone subtotals

3. **Transaction Processing**
   - Retrieves meter customer records for each zone
   - Calculates current vs arrears amounts
   - Handles leaking amount calculations
   - Processes A/R leaking balances

4. **Leaking A/R Processing**
   - Retrieves leaking A/R transactions for the transaction date
   - Includes leaking ledger details
   - Calculates leaking totals

5. **Totals Calculation**
   - Zone subtotals
   - Leaking A/R totals
   - Grand totals across all sections

## Usage Instructions

### For End Users

1. Navigate to the Daily Collection Report page
2. Select the following required fields:
   - **Transaction Date** (dd-mm-yyyy format)
   - **Zone** (or select "All" for all zones)
   - **Prepared by** (select employee)
   - **Checked/Verified by** (select employee)
   - **Approved by** (select employee)

3. Click the **"Export to Excel"** button (green button next to Print button)

4. The system will:
   - Validate that a transaction date is selected
   - Generate a CSV file with the report data
   - Automatically download the file

5. The downloaded file will be named: `Daily_Collection_Report_DD-MM-YYYY.csv`

6. Open the CSV file in Microsoft Excel or any spreadsheet application

### For Developers

#### URL Structure
```
/master/adddailyreport/exporttoexcel/{trans_date}/{zone}/{preparedby}/{verifiedby}/{approvedby}
```

**Example:**
```
/master/adddailyreport/exporttoexcel/25-12-2023/0/1/2/3
```

#### Parameters
- `trans_date`: Transaction date in dd-mm-yyyy format (e.g., "25-12-2023")
- `zone`: Zone ID (0 for all zones)
- `preparedby`: Employee ID
- `verifiedby`: Employee ID
- `approvedby`: Employee ID

#### Dependencies
- **CSV Helper:** `application/helpers/csv_helper.php`
  - Uses `array_to_csv()` function for CSV generation

- **Models Used:**
  - `adddailyreport_model` - For zone and employee data, meter customer records
  - `leakingentry_model` - For leaking A/R transactions

## Technical Details

### Date Handling
The export method handles date conversion from the URL parameter format (dd-mm-yyyy) to MySQL format (Y-m-d):

```php
$date_parts = explode('-', $trans_date);
if(count($date_parts) == 3){
    $trans_date_mysql = $date_parts[2] . '-' . $date_parts[1] . '-' . $date_parts[0];
} else {
    $trans_date_mysql = date('Y-m-d', strtotime($trans_date));
}
```

### Number Formatting
All monetary values are formatted using PHP's `number_format()` function with 2 decimal places:
```php
number_format($value, 2)
```

### File Output
The CSV file is generated using the `array_to_csv()` helper function:
```php
array_to_csv($export_data, $filename);
```

This function:
- Sets appropriate HTTP headers for CSV download
- Outputs the data array as CSV format
- Handles file download automatically

## Data Query Details

### Main Query
The export uses the same query as the Print preview:
- **Model Method:** `adddailyreport_model->get_metercustomer_records($date, $zone)`
- **Purpose:** Retrieves meter customer records for a specific date and zone
- **Returns:** Array of customer transaction records with calculated amounts

### Leaking A/R Query
- **Model Method:** `leakingentry_model->get_soa_statement_transdate($date)`
- **Purpose:** Retrieves leaking A/R transactions for a specific date
- **Returns:** Array of leaking ledger details

## Error Handling

### Client-Side Validation
- JavaScript validates that transaction date is selected before allowing export
- Shows alert message if date is missing

### Server-Side
- Date format conversion handles multiple input formats
- Empty data arrays are handled gracefully
- Missing employee data is checked with `isset()` before use

## File Format

### CSV Structure
- **Delimiter:** Comma (,)
- **Encoding:** UTF-8
- **Line Endings:** Platform-specific (handled by PHP)

### Column Alignment
- Text columns: Left-aligned
- Numeric columns: Right-aligned (when opened in Excel)
- Headers: Bold formatting (when opened in Excel)

## Comparison with Print Preview

| Feature | Print Preview | Export to Excel |
|---------|--------------|-----------------|
| Data Source | Same query | Same query |
| Layout Structure | HTML table | CSV rows |
| Zone Grouping | Yes | Yes |
| Subtotals | Yes | Yes |
| Leaking A/R Section | Yes | Yes |
| Grand Totals | Yes | Yes |
| Signature Section | Yes | Yes |
| Date/Time Stamp | Yes | Yes |
| Format | PDF/HTML | CSV (Excel) |

## Maintenance Notes

### Adding New Columns
If new columns need to be added to the export:

1. Update the column headers array in `exporttoexcel()` method
2. Add the corresponding data field in the transaction processing loop
3. Update zone total and grand total calculations
4. Ensure the Print preview is updated similarly for consistency

### Modifying Calculations
Any changes to calculation logic should be made in both:
- `exporttoexcel()` method (controller)
- `adddailyreport_printtopdf.php` view file

This ensures consistency between Print and Export outputs.

## Troubleshooting

### Common Issues

1. **Date Format Errors**
   - Ensure date is in dd-mm-yyyy format
   - Check date conversion logic if dates appear incorrect

2. **Missing Data**
   - Verify zone selection includes data for the selected date
   - Check database records for the transaction date

3. **File Not Downloading**
   - Check browser popup blocker settings
   - Verify CSV helper is loaded correctly
   - Check PHP output buffering settings

4. **Excel Formatting Issues**
   - CSV files may need manual formatting in Excel
   - Consider using Excel-specific library (PhpSpreadsheet) for better formatting

## Future Enhancements

Potential improvements:
1. Use PhpSpreadsheet library for native Excel (.xlsx) format
2. Add formatting (bold headers, number formats, column widths)
3. Add multiple worksheet support (one per zone)
4. Add charts and graphs
5. Add password protection option
6. Add email export functionality

## Version History

- **Version 1.0** (Current)
  - Initial implementation
  - Basic CSV export functionality
  - Matches Print preview layout and data

## Support

For issues or questions regarding this feature:
1. Check this documentation
2. Review the code comments in the controller method
3. Compare with Print preview functionality
4. Check server error logs for PHP errors

---

**Last Updated:** December 2023
**Author:** System Development Team
