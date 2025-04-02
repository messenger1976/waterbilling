
<!-- MAIN PANEL -->
		<div id="main" role="main" style="margin-left:0px;">

		

			<!-- MAIN CONTENT -->
			<div id="content">

				
				<!-- widget grid -->
				<section id="widget-grid" class="">

					
                <div class="row">

<!-- NEW COL START -->
<article class="col-sm-12 col-md-12 col-lg-12">
					
					<!-- Widget ID (each widget will need unique ID)-->
					<div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false" data-widget-custombutton="false" data-widget-colorbutton="false" data-widget-deletebutton="false">
						<!-- widget options:
							usage: <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false">
							
							data-widget-colorbutton="false"	
							data-widget-editbutton="false"
							data-widget-togglebutton="false"
							data-widget-deletebutton="false"
							data-widget-fullscreenbutton="false"
							data-widget-custombutton="false"
							data-widget-collapsed="true" 
							data-widget-sortable="false"
							
						-->
						<!--<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Customer Info</h2>				
							
						</header>-->

						<!-- widget div-->
						<div>
							
							<!-- widget edit box -->
							<div class="jarviswidget-editbox">
								<!-- This area used as dropdown edit box -->
								
							</div>
							<!-- end widget edit box -->
							
							<!-- widget content -->
							<div class="widget-body no-padding">
								
								<form id="checkout-form" class="smart-form" novalidate="novalidate">
                                    <section style="margin: 5px 15px;">
                                        <div class="row">
											<div class="form-group" style="width:100%;">
												<label class="label col col-2">Search Customer</label>
												<div class="col col-8">
													<label class="input"> <i class="icon-prepend fa fa-search"></i>
														
														<select class="form-control" id="search_box_id">
															option value="">--Select--</option>
															<?php
													
															foreach ($customer_listing as $key => $value) {
																?>
																<option value="<?php echo $value['customer_id']; ?>"> <?php echo $value['customer_id'] . ' ==> ' . $value['last_name'] . ', ' . $value['first_name'] . ' ' . $value['middle_name']; ?></option>
															<?php }
															?>
														</select>
														
													</label>
													
												</div>
												<!--<input type="submit" class="form-control"  id="btn_search_box" name="btn_search_box" value = "Search" style="width: auto; float: left;  background: #3276b1; color:#fff;" />-->
											</div>
                                            
                                        </div>
										
                                    </section>
									<fieldset>
										<div class="row">
											<section class="col col-10">
												<label class="input"> <i class="icon-prepend fa fa-user"></i>
													<input type="text" name="fname" id="fname" placeholder="Full name" readonly>
                                                    <input type="hidden" name="customer_id" id="customer_id">
												</label>
											</section>
											
										</div>

										<div class="row">
											<section class="col col-6">
												<label class="input"> <i class="icon-prepend fa fa-tachometer"></i>
													<input type="text" name="meter_number" id="meter_number" placeholder="Meter Number">
												</label>
											</section>
											<section class="col col-6">
												<label class="input"> <i class="icon-prepend fa fa-home"></i>
													<input type="text" name="address" id="address" placeholder="Address">
												</label>
											</section>
										</div>
									</fieldset>

									
									<fieldset>
									<div class="row">
                                            <label class="label col col-2">Billing Period</label>
											<section class="col col-10">
												<label class="input">
													<input type="text" class="input-lg" name="billing_period" placeholder="billing_period" value="<?php echo $_SESSION['current_billingperiod']; ?>" readonly>
												</label>
											</section>
                                        </div>
                                        <label class="label col col-2">Amount</label>
										<section>
											<label class="input">
												<input type="text" class="input-lg" name="pay" placeholder="Amount">
											</label>
										</section>

										<div class="row">
                                            <label class="label col col-2">Previous Reading</label>
											<section class="col col-10">
												<label class="input">
													<input type="text" class="input-lg" name="prev_reading" placeholder="Previous Reading">
												</label>
											</section>
                                        </div>
                                        <div class="row">
                                        <label class="label col col-2">Current Reading</label>
											<section class="col col-10">
												<label class="input">
													<input type="text" class="input-lg" name="curr_reading" placeholder="Current Reading">
												</label>
											</section>
										</div>

										<div class="row" style="display: none;">
											<label class="label col col-4">Month date</label>
											<section class="col col-5">
												<label class="select">
													<select name="month">
														<option value="0" selected="" disabled="">Month</option>
														<option value="1">January</option>
														<option value="1">February</option>
														<option value="3">March</option>
														<option value="4">April</option>
														<option value="5">May</option>
														<option value="6">June</option>
														<option value="7">July</option>
														<option value="8">August</option>
														<option value="9">September</option>
														<option value="10">October</option>
														<option value="11">November</option>
														<option value="12">December</option>
													</select> <i></i> </label>
											</section>
                                            <label class="label col col-2">Year</label>
											<section class="col col-3">
												<label class="input">
													<input type="text" name="year" placeholder="Year" data-mask="2099">
												</label>
											</section>
										</div>
									</fieldset>

									<footer>
										<button type="submit" class="btn btn-primary">
											Preview
										</button>
										<a href="<?php echo ADMIN_URL;?>mobile_dashboard/logout" class="btn btn-danger" id="btn_logout" name="btn_logout" value="logout">Logout</a>
									</footer>
								</form>

							</div>
							<!-- end widget content -->
							
						</div>
						<!-- end widget div -->
						
					</div>
				
				</article>
				<!-- END COL -->

                </div>
					

				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

		<?php include('mobile_footer.php');?>

	

<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>
		<script type="text/javascript">
		
		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			
			$('#search_box_id').select2();		
			
			
			
			$(document).on('change',"#search_box_id",function(evt){
				evt.preventDefault();
				var cust_id = $(this).val();
				$('#leaking_option').hide();
				if(cust_id){
					// Send an AJAX request to the backend
					$.ajax({
						url: 'leakingentry/get_customer_meter_reading', // Backend PHP script
						type: 'POST',
						data: { customer_id: cust_id },
						dataType: 'json',
						success: function(response) {
							// Clear the child dropdown
							$('#billing_period').empty().append('<option value="">--Select--</option>');

							// Populate the child dropdown with the response data
							if (response.length > 0) {
								$.each(response, function(index, item) {
									if(item.status==0){
										$('#billing_period').append('<option value="' + item.id+' '+item.month+' '+item.year+ '">' + item.month_name+' '+item.year+ '</option>');
									}
									
								});
							}
						},
						error: function(xhr, status, error) {
							console.error('AJAX Error: ' + status + error);
						}
					});
				}else{
					// If no parent is selected, clear the child dropdown
					$('#billing_period').empty().append('<option value="">--Select--</option>');
					//$('#leaking_option').hide();
				}
			});
			
		
		
    
    
		});


		</script>
