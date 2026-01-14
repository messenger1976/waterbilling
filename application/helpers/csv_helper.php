<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------
/**
 * CSV Helpers
 * @author		Spark(Mani)
 * @copyright	Copyright (c) 2013, Sparkinfosys.com
 * @version		2.0
 * @package		Chums
 * @subpackage  CSV Helpers
 * @link         
 * */
// ------------------------------------------------------------------------
/**
 * Array to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
	if ( ! function_exists('array_to_csv'))
{
	function array_to_csv($array, $download = "")
	{
		if ($download != "")
		{	
			// Clean any previous output buffers
			while (ob_get_level()) {
				ob_end_clean();
			}
			
			// Prevent any output before headers
			if (headers_sent($file, $line)) {
				die("Headers already sent in $file on line $line. Cannot send CSV file.");
			}
			
			// Set proper headers for CSV download
			header('Content-Type: text/csv; charset=UTF-8');
			header('Content-Disposition: attachment; filename="' . $download . '"');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header('Pragma: public');
			header('Expires: 0');
			
			// Disable output buffering for streaming
			if (ob_get_level()) {
				ob_end_clean();
			}
			
			// Don't output UTF-8 BOM as it can cause corruption in some systems
		}		

		// For download, stream directly to output (more memory efficient)
		if ($download != "")
		{
			$f = fopen('php://output', 'w');
			if (!$f) {
				show_error("Can't open php://output");
				return;
			}
			
			$n = 0;		
			foreach ($array as $line)
			{
				$n++;
				if ( ! fputcsv($f, $line))
				{
					fclose($f);
					show_error("Can't write line $n: $line");
					return;
				}
				
				// Flush output every 100 rows to prevent timeout
				if ($n % 100 == 0) {
					flush();
					if (function_exists('ob_flush')) {
						@ob_flush();
					}
				}
			}
			fclose($f);
			
			// Final flush
			flush();
			if (function_exists('ob_flush')) {
				@ob_flush();
			}
			
			// Exit to prevent any further output
			exit;
		}
		else
		{
			// For non-download (return string), use buffering
			ob_start();
			$f = fopen('php://output', 'w');
			if (!$f) {
				ob_end_clean();
				show_error("Can't open php://output");
				return '';
			}
			
			$n = 0;		
			foreach ($array as $line)
			{
				$n++;
				if ( ! fputcsv($f, $line))
				{
					fclose($f);
					ob_end_clean();
					show_error("Can't write line $n: $line");
					return '';
				}
			}
			fclose($f);
			$str = ob_get_contents();
			ob_end_clean();
			
			return $str;
		}		
	}
}

// ------------------------------------------------------------------------

/**
 * Query to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('query_to_csv'))
{
	function query_to_csv($query, $headers = TRUE, $download = "")
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('invalid query');
		}
		
		$array = array();
		
		if ($headers)
		{
			$line = array();
			foreach ($query->list_fields() as $name)
			{
				$line[] = ucwords($name);
			}
			$array[] = $line;
		}
		
		foreach ($query->result_array() as $row)
		{
			$line = array();
			foreach ($row as $item)
			{
				$line[] = $item;
			}
			$array[] = $line;
		}

		echo array_to_csv($array, $download);
	}
}

/* End of file csv_helper.php */
/* Location: ./application/helpers/csv_helper.php */