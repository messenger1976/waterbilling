
 <script type="text/javascript">
function getPrint(){
	window.print();
}
</script>	

<!DOCTYPE html>
<html>
<head>
<title>Printable Item</title>
<style>
@media print {
  @page {
    size: 2.2in 5in;
    margin: 0; /* Important: Remove default margins */
  }

  body {
    width: 2.2in;
    height: 5in;
    margin: 0; /* Important: Remove default margins */
    padding: 0; /* Important: Remove default padding */
    box-sizing: border-box; /* Ensures padding/border doesn't affect dimensions */
    font-size: 12pt; /* Adjust font size as needed */
    /* Add any other necessary styles for printing here */
  }
}

/* Optional: Styles for screen display (before printing) */
body {
    font-family: sans-serif; /* Example font */
    /* Other screen styles */
}

.content {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  text-align: center;
}

</style>
</head>
<body onLoad="getPrint();"

<div class="content">
  <p>Printable Content</p>
  <p>Example Text</p>
  <p>More text</p>
</div>

</body>
</html>




