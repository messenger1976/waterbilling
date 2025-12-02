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
	 <div class="table-responsive">
	 
        <table  class="table table-bordered">
			<thead>
				<tr>
					<th data-hide="phone">SN#</th>
					<th data-hide="phone">Customer ID</th>
					<th data-hide="phone">First Name</th>
					<th data-hide="phone">Last Name</th>
					<th data-hide="phone">Address</th>
					<th data-hide="phone">Zone</th>
					<th data-hide="phone">Classification</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<?php
                    if(count($record) > 0){
						$index = 1;
                        foreach($record as $key => $row){ 
				?>                                            
					<tr>
						<td><?php echo $index; ?></td>
						<td><?php echo stripslashes($row['customer_id']); ?></td>
						<td><?php echo stripslashes($row['first_name']); ?></td>
						<td><?php echo stripslashes($row['last_name']); ?></td>
						<td><?php echo stripslashes($row['address']); ?></td>
						<td><?php echo stripslashes($row['zone_name']); ?></td>
						<td><?php echo stripslashes($row['classification_name']); ?></td>
						<td>
							<span <?php 
								if($row['status'] == '1'){ 
									echo " class='label label-success arrowed-in arrowed-in-right'"; 
								} else if($row['status'] == '0'){ 
									echo "class='label label-warning arrowed'";
								} else if($row['status'] == '2'){ 
									echo "class='label label-danger arrowed'";
								} 
							?>>
								<a href="#" style="color:#FFF; text-decoration:none;">
									<?php 
										if($row['status'] == '1'){ 
											echo "Active"; 
										} else if($row['status'] == '0'){ 
											echo "Inactive"; 
										} else if($row['status'] == '2'){ 
											echo "Disconnected"; 
										} else {
											echo "Unknown";
										}
									?>
								</a>
							</span>
						</td>
					</tr>
				<?php 
					$index++;
				} ?>
                 <?php } else { ?>
					<tr>
						<td colspan="8" style="text-align:center;">No records found</td>
					</tr>
				<?php } ?>
			</tbody>
       </table>
	</div>
	
										
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
			
			/* BASIC ;*/
				var responsiveHelper_dt_basic = undefined;
				var responsiveHelper_datatable_fixed_column = undefined;
				var responsiveHelper_datatable_col_reorder = undefined;
				var responsiveHelper_datatable_tabletools = undefined;
				
				var breakpointDefinition = {
					tablet : 1024,
					phone : 480
				};

				$('#dt_basic').dataTable({
					"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
					"autoWidth" : true,
			        "oLanguage": {
					    "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
					},
					"preDrawCallback" : function() {
						// Initialize the responsive datatables helper once.
						if (!responsiveHelper_dt_basic) {
							responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_basic'), breakpointDefinition);
						}
					},
					"rowCallback" : function(nRow) {
						responsiveHelper_dt_basic.createExpandIcon(nRow);
					},
					"drawCallback" : function(oSettings) {
						responsiveHelper_dt_basic.respond();
					}
				});

			/* END BASIC */
		
		})

</script>
									
