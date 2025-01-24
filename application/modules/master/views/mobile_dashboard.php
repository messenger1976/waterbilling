
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
					<div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false" data-widget-custombutton="false">
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
						<header>
							<span class="widget-icon"> <i class="fa fa-edit"></i> </span>
							<h2>Customer Info</h2>				
							
						</header>

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
                                    <fieldset>
                                        <div class="row">
                                            <label class="label col col-2">Search</label>
                                            <section class="col col-8">
                                                <label class="input"> <i class="icon-prepend fa fa-search"></i>
													
													<select class="form-control" id="search_box_id">
														<option>1</option>
													</select>
                                                    
												</label>
                                                
                                            </section>
                                            <input type="submit" class="form-control"  id="btn_search_box" name="btn_search_box" value = "Search" style="width: auto; float: left;  background: #3276b1; color:#fff;" />
                                        </div>
										
                                    </fieldset>
									<fieldset>
										<div class="row">
											<section class="col col-10">
												<label class="input"> <i class="icon-prepend fa fa-user"></i>
													<input type="text" name="fname" id="fname" placeholder="Full name">
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

										<div class="row">
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
		
		
    $("#btn-search").on('click', function(evt){
        evt.preventDefault();
        alert($("#search").val());
    });
    $("#search1").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "search.php",
                type: "GET",
                data: { term: request.term },
                dataType: "json",
                success: function (data) {
                    response($.map(data, function (item) {
                        return {
                            label: item.first_name + " (" + item.last_name + ")",
                            value: item.first_name+' '+item.last_name, // What appears in the input field
                            id: item.customer_id       // Custom property to store the id
                        };
                    }));
                },
                error: function (err) {
                    console.log("Error fetching data."+err.message);
                }
            });
        },
        minLength: 2, // Start search after 2 characters
        select: function (event, ui) {
            // Populate the input with the selected item's name
            $("#search").val(ui.item.value);
            // Store the selected item's ID in a hidden field
            $("#customer_id").val(ui.item.id);
            return false; // Prevent the default action
        }
    });
});


		</script>
