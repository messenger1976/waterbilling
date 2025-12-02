<!-- Content starts here - no header/sidebar -->
<div style="max-width: 1400px; margin: 0 auto; background: #fff; padding: 15px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
		<div class="row" style="margin-bottom: 20px;">
			<div class="col-xs-12 text-right">
				<button class="btn btn-primary" onclick="window.print()"><i class="fa fa-print"></i> Print</button>
				<a href="<?php echo base_url();?>master/statementofaccount/search" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Search</a>
			</div>
		</div>
		
		<!-- Card Design -->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="card" style="border: none; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-radius: 8px; overflow: hidden;">
					<div class="card-body" style="padding: 20px;">
								<!-- Customer Information -->
								<div class="row" style="margin-bottom: 20px;">
									<div class="col-md-12">
										<div class="panel panel-default">
											<div class="panel-heading">
												<strong>Customer Information</strong>
											</div>
											<div class="panel-body">
												<div class="row">
													<div class="col-md-6">
														<table class="table table-bordered">
															<tr>
																<td width="40%"><strong>Customer ID:</strong></td>
																<td><?php echo $customer_info['customer_id']; ?></td>
															</tr>
															<tr>
																<td><strong>Name:</strong></td>
																<td><?php echo strtoupper($customer_info['last_name'].', '.$customer_info['first_name'].' '.$customer_info['middle_name']); ?></td>
															</tr>
															<tr>
																<td><strong>Address:</strong></td>
																<td><?php echo strtoupper($customer_info['address']); ?></td>
															</tr>
															<tr>
																<td><strong>Zone:</strong></td>
																<td><?php echo isset($customer_info['zone']) ? strtoupper($customer_info['zone']) : 'N/A'; ?></td>
															</tr>
														</table>
													</div>
													<div class="col-md-6">
														<table class="table table-bordered">
															<tr>
																<td width="40%"><strong>Meter Number:</strong></td>
																<td><?php echo $customer_info['meter_number']; ?></td>
															</tr>
															<tr>
																<td><strong>Classification:</strong></td>
																<td><?php echo isset($customer_info['class_name']) ? $customer_info['class_name'] : 'N/A'; ?></td>
															</tr>
															<tr>
																<td><strong>Account Type:</strong></td>
																<td><?php echo isset($customer_info['cust_type_name']) ? $customer_info['cust_type_name'] : 'N/A'; ?></td>
															</tr>
															<tr>
																<td><strong>Current Balance:</strong></td>
																<td><strong class="text-danger">PHP <?php echo number_format($current_balance, 2); ?></strong></td>
															</tr>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<!-- Billing Table -->
								<div class="row" style="margin-bottom: 30px;">
									<div class="col-md-12">
										<div class="panel panel-primary">
											<div class="panel-heading">
												<strong><i class="fa fa-file-text"></i> Billing Records</strong>
											</div>
											<div class="panel-body" style="padding: 0;">
												<div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
													<table class="table table-striped table-bordered table-hover" id="billing_table" style="width: 100%; min-width: 800px;">
														<thead>
															<tr>
																<th style="text-align: center;">Date</th>
																<th style="text-align: center;">Ref No</th>
																<th style="text-align: left;">Description</th>
																<th style="text-align: right;">Amount</th>
															</tr>
														</thead>
														<tbody>
															<?php 
															if(!empty($billing_entries)) {
																foreach($billing_entries as $entry) {
																	$entry_date = date('d-m-Y', strtotime($entry['date']));
																	$entry_date_sort = date('Y-m-d', strtotime($entry['date']));
																	$debit = number_format($entry['debit'], 2);
															?>
															<tr>
																<td style="text-align: center;" data-order="<?php echo $entry_date_sort; ?>"><?php echo $entry_date; ?></td>
																<td style="text-align: center;"><?php echo $entry['refno']; ?></td>
																<td style="text-align: left;">
																	<?php echo $entry['description']; ?>
																	<?php if(isset($entry['consumed'])) { ?>
																		<br><small class="text-muted">
																			Reading: <?php echo $entry['previous_reading']; ?> - <?php echo $entry['reading']; ?> 
																			(Consumed: <?php echo $entry['consumed']; ?> cu.m)
																		</small>
																	<?php } ?>
																</td>
																<td style="text-align: right;">
																	<strong class="text-danger">PHP <?php echo $debit; ?></strong>
																</td>
															</tr>
															<?php 
																}
															} else {
															?>
															<tr>
																<td colspan="4" style="text-align: center;">No billing records found.</td>
															</tr>
															<?php } ?>
														</tbody>
														<?php if(!empty($billing_entries)) { ?>
														<tfoot>
															<tr>
																<th colspan="3" style="text-align: right;"><strong>Total Billing:</strong></th>
																<th style="text-align: right;">
																	<strong class="text-danger">PHP <?php echo number_format($total_billing, 2); ?></strong>
																</th>
															</tr>
														</tfoot>
														<?php } ?>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<!-- Payment Table -->
								<div class="row">
									<div class="col-md-12">
										<div class="panel panel-success">
											<div class="panel-heading">
												<strong><i class="fa fa-money"></i> Payment Records</strong>
											</div>
											<div class="panel-body" style="padding: 0;">
												<div class="table-responsive" style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
													<table class="table table-striped table-bordered table-hover" id="payment_table" style="width: 100%; min-width: 1300px;">
														<thead>
															<tr>
																<th style="text-align: center;">Bill No.</th>
																<th style="text-align: center;">Billing Period</th>
																<th style="text-align: center;">Due Date</th>
																<th style="text-align: center;">Previous Reading</th>
																<th style="text-align: center;">Current Reading</th>
																<th style="text-align: center;">Consumed</th>
																<th style="text-align: right;">Bill Amount</th>
																<th style="text-align: right;">Discount</th>
																<th style="text-align: right;">Penalty</th>
																<th style="text-align: right;">Maintenance Fee</th>
																<th style="text-align: center;">OR Number</th>
																<th style="text-align: right;">Pay Amount</th>
																<th style="text-align: center;">Date Paid</th>
															</tr>
														</thead>
														<tbody>
															<?php 
															if(!empty($payment_entries)) {
																foreach($payment_entries as $entry) {
																	$entry_date = date('d-m-Y', strtotime($entry['date']));
																	$entry_date_sort = date('Y-m-d', strtotime($entry['date']));
																	$due_date_display = !empty($entry['due_date']) ? date('d-m-Y', strtotime($entry['due_date'])) : '';
																	$pay_amount = isset($entry['pay_amount']) ? floatval($entry['pay_amount']) : 0;
															?>
															<tr>
																<td style="text-align: center;"><?php echo isset($entry['bill_refno']) ? $entry['bill_refno'] : '-'; ?></td>
																<td style="text-align: center;"><?php echo isset($entry['billing_period']) ? $entry['billing_period'] : (isset($entry['billing_periods']) ? $entry['billing_periods'] : '-'); ?></td>
																<td style="text-align: center;"><?php echo $due_date_display; ?></td>
																<td style="text-align: center;"><?php echo isset($entry['previous_reading']) ? $entry['previous_reading'] : '-'; ?></td>
																<td style="text-align: center;"><?php echo isset($entry['current_reading']) ? $entry['current_reading'] : '-'; ?></td>
																<td style="text-align: center;"><?php echo isset($entry['consumed']) ? $entry['consumed'] : '-'; ?></td>
																<td style="text-align: right;"><?php echo isset($entry['bill_amount']) && $entry['bill_amount'] > 0 ? 'PHP ' . number_format($entry['bill_amount'], 2) : '-'; ?></td>
																<td style="text-align: right;"><?php echo isset($entry['discount']) && $entry['discount'] > 0 ? 'PHP ' . number_format($entry['discount'], 2) : '-'; ?></td>
																<td style="text-align: right;"><?php echo isset($entry['penalty']) && $entry['penalty'] > 0 ? 'PHP ' . number_format($entry['penalty'], 2) : '-'; ?></td>
																<td style="text-align: right;"><?php echo isset($entry['maintenance_fee']) && $entry['maintenance_fee'] > 0 ? 'PHP ' . number_format($entry['maintenance_fee'], 2) : '-'; ?></td>
																<td style="text-align: center;"><?php echo $entry['refno']; ?></td>
																<td style="text-align: right;">
																	<strong class="text-success"><?php echo $pay_amount > 0 ? 'PHP ' . number_format($pay_amount, 2) : '-'; ?></strong>
																</td>
																<td style="text-align: center;" data-order="<?php echo $entry_date_sort; ?>"><?php echo $entry_date; ?></td>
															</tr>
															<?php 
																}
															} else {
															?>
															<tr>
																<td colspan="13" style="text-align: center;">No payment records found.</td>
															</tr>
															<?php } ?>
														</tbody>
														<?php if(!empty($payment_entries)) { ?>
														<tfoot>
															<tr>
																<th colspan="6" style="text-align: right;"><strong>Total Payment:</strong></th>
																<th colspan="6" style="text-align: right;">
																	<strong class="text-success">PHP <?php echo number_format($total_payment, 2); ?></strong>
																</th>
																<th></th>
															</tr>
														</tfoot>
														<?php } ?>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								
					</div>
				</div>
			</div>
		</div>
</div>
<!-- End content wrapper -->

<!-- PAGE RELATED PLUGIN(S) -->
<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	$(document).ready(function() {
		// pageSetUp() removed - not needed for this page
		
		/* BASIC */
		var responsiveHelper_dt_basic = undefined;
		var breakpointDefinition = {
			tablet : 1024,
			phone : 480
		};

		// Initialize Billing Table
		var responsiveHelper_dt_billing = undefined;
		$('#billing_table').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"ordering": false,
			"order": [],
			"responsive": true,
			"pageLength": 25,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"columnDefs": [
				{ 
					"orderable": false,
					"targets": "_all"
				}
			],
			"oLanguage": {
				"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
			},
			"preDrawCallback" : function() {
				if (typeof ResponsiveDatatablesHelper !== 'undefined') {
					if (!responsiveHelper_dt_billing) {
						responsiveHelper_dt_billing = new ResponsiveDatatablesHelper($('#billing_table'), breakpointDefinition);
					}
				}
			},
			"rowCallback" : function(nRow) {
				if (typeof ResponsiveDatatablesHelper !== 'undefined' && responsiveHelper_dt_billing) {
					responsiveHelper_dt_billing.createExpandIcon(nRow);
				}
			},
			"drawCallback" : function(oSettings) {
				if (typeof ResponsiveDatatablesHelper !== 'undefined' && responsiveHelper_dt_billing) {
					responsiveHelper_dt_billing.respond();
				}
			}
		});
		
		// Initialize Payment Table
		var responsiveHelper_dt_payment = undefined;
		$('#payment_table').dataTable({
			"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
				"t"+
				"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
			"autoWidth" : true,
			"ordering": false,
			"order": [],
			"responsive": true,
			"pageLength": 25,
			"lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
			"columnDefs": [
				{ 
					"orderable": false,
					"targets": "_all"
				}
			],
			"oLanguage": {
				"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
			},
			"preDrawCallback" : function() {
				if (typeof ResponsiveDatatablesHelper !== 'undefined') {
					if (!responsiveHelper_dt_payment) {
						responsiveHelper_dt_payment = new ResponsiveDatatablesHelper($('#payment_table'), breakpointDefinition);
					}
				}
			},
			"rowCallback" : function(nRow) {
				if (typeof ResponsiveDatatablesHelper !== 'undefined' && responsiveHelper_dt_payment) {
					responsiveHelper_dt_payment.createExpandIcon(nRow);
				}
			},
			"drawCallback" : function(oSettings) {
				if (typeof ResponsiveDatatablesHelper !== 'undefined' && responsiveHelper_dt_payment) {
					responsiveHelper_dt_payment.respond();
				}
			}
		});
	});
</script>

<style>
	@media print {
		#ribbon, .btn, .jarviswidget-editbox, .dt-toolbar {
			display: none !important;
		}
		.widget-body {
			padding: 0 !important;
		}
		.page-title {
			margin-bottom: 10px !important;
		}
	}
	
	/* Mobile Responsive Styles */
	@media (max-width: 768px) {
		.table-responsive {
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
		}
		.table {
			font-size: 12px;
		}
		.table th,
		.table td {
			padding: 8px 4px;
		}
		.panel-body {
			padding: 15px !important;
		}
		.table-bordered td {
			font-size: 11px;
		}
		.btn {
			padding: 8px 12px;
			font-size: 13px;
			margin-bottom: 5px;
		}
		h1.page-title {
			font-size: 18px;
		}
		h1.page-title span {
			display: block;
			font-size: 14px;
			margin-top: 5px;
		}
	}
	
	@media (max-width: 480px) {
		.table {
			font-size: 10px;
		}
		.table th,
		.table td {
			padding: 6px 2px;
		}
		.table-bordered td {
			font-size: 10px;
		}
		.btn {
			padding: 6px 10px;
			font-size: 12px;
			width: 100%;
			margin-bottom: 5px;
		}
		.text-right {
			text-align: left !important;
		}
		.col-md-6 {
			margin-bottom: 15px;
		}
	}
</style>

</body>

</html>

