
 <script type="text/javascript">
function getPrint(){
	window.print();
}
</script>	

<!DOCTYPE html>
<html>
<head>
<title>Water Bill Notice (2.2in x 5in)</title>
<style>
@media print {
  @page {
    size: 2.2in 5in;
    margin: 0;
  }

  body {
    width: 2.2in;
    height: 5in;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-size: 8pt; /* Reduced font size to fit */
    font-family: sans-serif;
    line-height: 1.2; /* Reduced line height */
  }

  .header, .notice-title, .bill-details, .customer-info, .water-usage, .payment-info, .important-notice, .footer {
    text-align: left; /* Adjusted for narrow width */
    padding: 2px; /* Reduced padding */
  }

  .header {
    text-align: center;
    border-bottom: 1px dashed #000;
  }

  .notice-title {
    text-align: center;
    font-weight: bold;
    font-size: 9pt; /* Slightly larger title */
    margin-top: 5px;
  }

  .bill-details strong, .customer-info strong, .water-usage strong, .payment-info strong, .important-notice strong {
    display: inline-block;
    width: 80px; /* Reduced label width */
    text-align: left;
    font-weight: bold;
  }

  .footer {
    text-align: center;
    border-top: 1px dashed #000;
    margin-top: 5px;
  }

  .logo {
    width: 100%; /* Reduced logo size */
    height: auto;
    display: block;
    margin: 0 auto 3px;
  }
}

/* Optional screen styles (for preview) */
body {
  font-family: sans-serif;
  font-size: 10pt;
  line-height: 1.2;
}

.header, .notice-title, .bill-details, .customer-info, .water-usage, .payment-info, .important-notice, .footer {
  padding: 5px;
}

.bill-details strong, .customer-info strong, .water-usage strong, .payment-info strong, .important-notice strong {
  display: inline-block;
  width: 100px;
}

.logo{
    max-width: 80px;
    height: auto;
}

</style>
</head>
<body onload="getPrint()">

<div class="header">
  <img src="<?php echo base_url();?>images/mroxas-logo-report.jpg" alt="Water District Logo" class="logo">
  <!--<p style="font-size: 7pt;">PRES. M.A. ROXAS WATER DIST.</p>
  <p style="font-size: 7pt;">Langatian, Pres. M. A. Roxas</p>
  <p style="font-size: 7pt;">TIN 004-315-023-00000</p>-->
</div>

<div class="notice-title">
  NOTICE OF COLLECTION<br>
  Dec 2024
</div>

<div class="bill-details">
  <strong>DATE:</strong> 12-01-2024<br>
  <strong>TIME:</strong> 1:00 PM<br>
  <strong>Period:</strong> 11-01 to 12-01
</div>

<div class="customer-info">
  <strong>NAME:</strong> <br>
  <strong>ACCNT #:</strong> <br>
  <strong>ADDR:</strong> <br>
  <strong>METER #:</strong> <br>
  <strong>BRAND:</strong> <br>
</div>

<div class="water-usage">
  <strong>PRES:</strong> XXXX<br>
  <strong>PREV:</strong> XXXX<br>
  <strong>USAGE:</strong> XXXX<br>
  <strong>BILL:</strong> XXXX<br>
  <strong>ARR:</strong> XXXX<br>
  <strong>DISC:</strong> XXXX<br>
  <strong>DUE:</strong> XXXX<br>
  <strong>LATE:</strong> XXXX<br>
</div>

<div class="payment-info">
  Pay @ Roxas Water Office
</div>

<div class="important-notice">
  Pay before due date for no disconnect.<br>
  <strong>DUE:</strong> XXXX<br>
  <strong>DISC:</strong> XXXX<br>
</div>

<div class="footer">
  ==========<br>
  Loyalty
</div>

</body>
</html>