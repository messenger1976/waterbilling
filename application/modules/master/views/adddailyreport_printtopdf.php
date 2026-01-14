<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="utf-8" />
		<title></title>
		<meta name="description" content="Static &amp; Dynamic Tables" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		
		<!-- Basic Styles -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/font-awesome.min.css">
		
		<!-- SmartAdmin Styles : Caution! DO NOT change the order -->
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-production-plugins.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-production.min.css">
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo base_url();?>css/smartadmin-skins.min.css">
        <style>
            .table>tbody>tr>td{
                padding: 5px;
            }
            
            /* Print-specific styles */
            @media print {
                @page {
                    size: A4 landscape; /* Use landscape for wide tables */
                    margin: 10mm;
                }
                
                * {
                    -webkit-print-color-adjust: exact !important;
                    print-color-adjust: exact !important;
                }
                
                body {
                    margin: 0;
                    padding: 0;
                    font-size: 9pt;
                    background: white !important;
                    color: black !important;
                }
                
                .table {
                    width: 100% !important;
                    font-size: 7pt;
                    border-collapse: collapse !important;
                    border: 1px solid #000;
                }
                
                .table th,
                .table td {
                    padding: 3px 2px;
                    border: 1px solid #000 !important;
                    text-align: left;
                }
                
                .table th {
                    background-color: #f0f0f0 !important;
                    font-weight: bold;
                }
                
                .table thead {
                    display: table-header-group !important;
                }
                
                .table tbody {
                    display: table-row-group !important;
                }
                
                .table tfoot {
                    display: table-footer-group !important;
                }
                
                /* Prevent page breaks inside rows */
                tr {
                    page-break-inside: avoid;
                }
                
                /* Prevent page breaks inside table cells */
                td, th {
                    page-break-inside: avoid;
                }
                
                /* Ensure logo and header print properly */
                img {
                    max-width: 100%;
                    height: auto;
                    display: block;
                    margin: 0 auto;
                }
                
                h3, h6 {
                    margin: 5px 0;
                    page-break-after: avoid;
                }
                
                /* Better spacing for signature section */
                table[width="100%"] {
                    margin-top: 20px;
                    page-break-inside: avoid;
                }
                
                /* Hide unnecessary elements */
                script, .no-print {
                    display: none !important;
                }
                
                /* Ensure text alignment is preserved */
                [style*="text-align:right"] {
                    text-align: right !important;
                }
                
                [align="right"] {
                    text-align: right !important;
                }
            }
            
            /* Screen styles */
            @media screen {
                .table {
                    font-size: smaller;
                }
            }
        </style>
	</head>
<body class="color" onLoad="window.print()">
    <div style="text-align: center;"><img src="<?php echo site_url();?>images/mroxas-logo-report.jpg" height="80px"/></div>
<h3 style="text-align: center;">DAILY COLLECTION REPORT</h3>
<h6 style="text-align: center;"><?php echo $trans_date;?></h6>
<div class="row">
	<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
		<?php
            if(count($record) > 0){
                foreach($record as $key => $row){ 
					$id=$row['id'];
				}
			}
        ?>
	</div>
</div>     
	 <div class="table-responsive" >
	 
        <table class="table" style="font-size:smaller; width: 100%;" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th data-hide="phone">OR #</th>
					
					<th data-hide="phone">Concessionaires</th>																												
					<th  style="text-align:right;" data-hide="phone">Total Amount Collected</th>
					<th  style="text-align:right;" data-hide="phone">Current</th>
					<th  style="text-align:right;" data-hide="phone">Arrears</th>
                    <th  style="text-align:right;" data-hide="phone">Previous Year</th>
					<th style="text-align:right;">WMMF</th>
                    <th style="text-align:right;">Penalty</th>
                    <th style="text-align:right;">SC Disc</th>
                    <th style="text-align:right;">Leaking Disc</th>
                    <th style="text-align:right;">A/R-Leaking</th>
                    <th style="text-align:right;">A/R-Leaking Balance</th>
                    <th style="text-align:right;">VAT</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($zone) > 0){
						$cr = 0;
                        $grand_total_current = 0;
                        $grand_total_penalty =0;
						$grand_total_wmmf =0;
                        $grand_total_vat =0;
                        $grand_total_leaking =0;
                        $grand_total_ar_leaking =0;
                        $grand_total_ar_leaking_balance =0;
                        $grand_total_sc =0;
                        $grand_total_arrears = 0; // Fixed: Initialize missing variable
                        foreach($zone as $key => $row){ 
				?>                                            
					<tr>
						<td></td>
						<td><b><?php echo stripslashes($row['zone']); ?></b></td>
						<td colspan="8"></td>
					</tr>
                <?php
                $mysql_transdate = date('Y-m-d',strtotime($trans_date));
				$current_billing_period_year = date('Y',strtotime($trans_date));
                 $get_dailytrans = $this->my_model->get_metercustomer_records($mysql_transdate,$row['id']);
                 $total_grand_zone = 0;
                 $total_current_zone = 0;
				 $total_arrears_zone = 0;
                 $total_penalty_zone = 0;
                 $total_wmmf_zone = 0;
                 $total_vat_zone = 0;
                 $total_leaking_zone = 0;
                 $total_ar_leaking_zone = 0;
                 $total_ar_leaking_balance_zone = 0;
                 $total_sc_zone = 0;

                 foreach($get_dailytrans as $key => $gdailytrans){ 
                    //$gross_total = $gdailytrans['grand_total'] + $gdailytrans['vat_amount'];
                    //$penalty = $gross_total - $gdailytrans['reading_amount'];
					$penalty = $gdailytrans['amount']-$gdailytrans['per_unit'];
					$current = 0;
					$arrears = 0;
                    if($penalty<=0){
                        $penalty = 0;
						$current = $gdailytrans['per_unit'];
                    }else{
						$arrears = $gdailytrans['per_unit'];
					}

					//$prev_year = get_customer_unpaid_records($gdailytrans['customer_id'],'12',$current_billing_period_year-1);
                    $prev_year = 0;
					$ar_leaking = array('leaking_total_amount' => 0, 'leaking_balance' => 0);
                    if(isset($gdailytrans['leaking_amount']) && $gdailytrans['leaking_amount']>0){
						$ornumber_search = sprintf('%07d',$gdailytrans['or_number']);
						$ar_leaking_result = $this->leakingentry_model->get_soa_statement_OR($ornumber_search);
						if($ar_leaking_result && is_array($ar_leaking_result)){
							$ar_leaking = $ar_leaking_result;
							if(isset($ar_leaking['leaking_balance'])){
								$gdailytrans['grand_total'] = $gdailytrans['grand_total']-$ar_leaking['leaking_balance'];
							}
						}
					}
					//print_r($ar_leaking['leaking_total_amount']);
                    echo '<tr>';
                    echo '<td>'.sprintf('%07d',$gdailytrans['or_number']).'</td><td>'.$gdailytrans['last_name'].', '.$gdailytrans['first_name'].' '.$gdailytrans['middle_name'].'</td>
                    <td align="right">'.number_format($gdailytrans['grand_total'],2).'</td>
                    <td align="right">'.number_format( $gdailytrans['current_amount'],2).'</td>
                    <td align="right">'.number_format( $gdailytrans['arrears_amount'],2).'</td>
					<td align="right">'.number_format( $prev_year,2).'</td>
                    <td align="right">'. number_format($gdailytrans['total_wmmf'],2).'</td>
                    <td align="right">'. number_format($gdailytrans['total_penalty'],2).'</td>
                    <td align="right">'.number_format(isset($gdailytrans['sc_discount']) ? $gdailytrans['sc_discount'] : 0,2).'</td>
                    <td align="right">'.number_format(isset($gdailytrans['leaking_amount']) ? $gdailytrans['leaking_amount'] : 0,2).'</td>
                    <td align="right">'.number_format(isset($ar_leaking['leaking_total_amount']) ? $ar_leaking['leaking_total_amount'] : 0,2).'</td>
                    <td align="right">'.number_format(isset($ar_leaking['leaking_balance']) ? $ar_leaking['leaking_balance'] : 0,2).'</td>
                    <td align="right">'.number_format(isset($gdailytrans['vat_amount']) ? $gdailytrans['vat_amount'] : 0,2).'</td>
                    ';
                    echo '</tr>';
                    $total_grand_zone += $gdailytrans['grand_total'];
                    $total_current_zone += $gdailytrans['current_amount'];
					$total_arrears_zone += $gdailytrans['arrears_amount'];
                    $total_wmmf_zone += $gdailytrans['total_wmmf'];
                    $total_penalty_zone += $gdailytrans['total_penalty'];
                    $total_vat_zone += isset($gdailytrans['vat_amount']) ? $gdailytrans['vat_amount'] : 0;
                    $total_leaking_zone += isset($gdailytrans['leaking_amount']) ? $gdailytrans['leaking_amount'] : 0;
                    $total_sc_zone += isset($gdailytrans['sc_discount']) ? $gdailytrans['sc_discount'] : 0;
					$total_ar_leaking_zone += isset($ar_leaking['leaking_total_amount']) ? $ar_leaking['leaking_total_amount'] : 0;
					$total_ar_leaking_balance_zone += isset($ar_leaking['leaking_balance']) ? $ar_leaking['leaking_balance'] : 0;
                 }
                 echo '<tr><td></td><th>TOTAL</th><th style="text-align:right">'.number_format($total_grand_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_current_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_arrears_zone,2).'</th>
                 <th style="text-align:right">0.00</th>
                 <th style="text-align:right">'.number_format($total_wmmf_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_penalty_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_sc_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_leaking_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_ar_leaking_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_ar_leaking_balance_zone,2).'</th>
                 <th style="text-align:right">'.number_format($total_vat_zone,2).'</th>
                 </tr>';
                ?>
				<?php  
                
                    $cr += $total_grand_zone; 
                    $grand_total_current += $total_current_zone; 
                    $grand_total_arrears += $total_arrears_zone; 
                    $grand_total_wmmf += $total_wmmf_zone;
                    $grand_total_penalty += $total_penalty_zone;
                    $grand_total_vat += $total_vat_zone;
                    $grand_total_leaking += $total_leaking_zone;
                    $grand_total_sc += $total_sc_zone;
            		$grand_total_ar_leaking += $total_ar_leaking_zone;
					$grand_total_ar_leaking_balance+=$total_ar_leaking_balance_zone;
                } 
                ?>
                 <?php } ?>


                
                <tr>
					<td></td>
					<td><b>LEAKING A/R PAYMENT REPORT</b></td>
					<td colspan="11"></td>
				</tr>
				

				
                <?php
				$mysql_transdate1 = date('Y-m-d',strtotime($trans_date));
				$get_dailytrans1 = $this->leakingentry_model->get_soa_statement_transdate($mysql_transdate1);
				$total_leaking_ar = 0;
				//print_r($get_dailytrans1);
				foreach($get_dailytrans1 as $key => $gdailytrans1){
					
					echo '<tr>
					<td>'.$gdailytrans1['leakingledgerdetails_source_type'].'#'.$gdailytrans1['leakingledgerdetails_or_number'].'</td>
					<td>'.$gdailytrans1['last_name'].', '.$gdailytrans1['first_name'].'</td>
					<td  style="text-align:right">'.number_format($gdailytrans1['leakingledgerdetails_amount'],2).'</td>
					<td colspan=6></td>
					<td style="text-align:right">'.number_format($gdailytrans1['leaking_total_amount'],2).'</td>
					<td style="text-align:right">'.number_format($gdailytrans1['leakingledgerdetails_balance'],2).'</td>
					<td></td>
					</tr>';
					$total_leaking_ar+=$gdailytrans1['leakingledgerdetails_amount'];
				}

				echo '<tr><td></td><th>TOTAL</th><th style="text-align:right">'.number_format($total_leaking_ar,2).'</th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 <th style="text-align:right"></th>
                 </tr>';
				 $cr+=$total_leaking_ar;
				?>
				
                <tr>
					<th></th>
					<th>Grand Total</th>
                    <th style="text-align:right"><?php echo number_format($cr,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_current,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_arrears,2);?></th>
					<th style="text-align:right">0.00</th>
					
					<th style="text-align:right"><?php echo number_format($grand_total_wmmf,2);?></th>
					
					<th style="text-align:right"><?php echo number_format($grand_total_penalty,2);?></th>
					<th style="text-align:right"><?php echo number_format($grand_total_sc,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_leaking,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_ar_leaking,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_ar_leaking_balance,2);?></th>
                    <th style="text-align:right"><?php echo number_format($grand_total_vat,2);?></th>
				</tr>
                
               
			</tbody>
       </table>

       <table width="100%"  style="font-size:smaller;" cellspacing="5" cellpadding="5">
            <tr>
                <th colspan="3">&nbsp;</th>
            </tr>
            <tr>
                <td width="30%">Prepared by:</td><td width="20%"></td><td width="30%">Verified by:</td>
            </tr>
            <tr>
                <th colspan="3">&nbsp;</th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr style="font-weight: bold;">
                <?php
                    $preparedby_name = $preparedby[0]['first_name'].' '.$preparedby[0]['middle_name'].' '.$preparedby[0]['last_name'];
                    $verifiedby_name = $verifiedby[0]['first_name'].' '.$verifiedby[0]['middle_name'].' '.$verifiedby[0]['last_name'];
                    $approvedby_name = $approvedby[0]['first_name'].' '.$approvedby[0]['middle_name'].' '.$approvedby[0]['last_name'];
                ?>
                <td width="30%"><span style="border-bottom: 1px solid black; "><?php echo strtoupper($preparedby_name);?></span></td><td width="20%"></td><td width="30%"><span style="border-bottom: 1px solid black;"><?php echo strtoupper($verifiedby_name);?></span></td>
            </tr>
            <tr>
                <td width="30%"><?php echo $preparedby[0]['jobtitle'];?></td><td width="20%"></td><td width="30%"><?php echo $verifiedby[0]['jobtitle'];?></td>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <td width="30%">Approved by:</td><td width="20%"></td><td width="30%"></td>
            </tr>
            <tr>
                <th colspan="3">&nbsp;</th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
            <tr style="font-weight: bold;">
                <td><span style="border-bottom: 1px solid black;"><?php echo strtoupper($approvedby_name);?></span></td><td></td><td>Date/Time printed: <?php echo date('Y-m-d H:m:s');?></td>
            </tr>
            <tr>
                <td width="30%"><?php echo $approvedby[0]['jobtitle'];?></td><td width="20%"></td><td width="30%"></td>
            </tr>
            <tr>
                <th colspan="3">&nbsp; </th>
            </tr>
       </table>
	</div>
	
	<!-- Minimal script for print functionality only -->
	<script type="text/javascript" class="no-print">
		// Auto-print on page load
		window.onload = function() {
			window.print();
		};
	</script>
</body>
</html>
