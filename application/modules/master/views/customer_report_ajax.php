<div class="row">
	<div class="col-lg-12 col-sm-12 col-xs-12 col-md-12">
		<?php
            // Check if record exists and is an array
            if(isset($record) && is_array($record) && count($record) > 0){
                // Records are available
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
                    if(isset($record) && is_array($record) && count($record) > 0){
						$index = 1;
                        foreach($record as $key => $row){ 
				?>                                            
					<tr>
						<td><?php echo $index; ?></td>
						<td><?php echo isset($row['customer_id']) ? stripslashes($row['customer_id']) : ''; ?></td>
						<td><?php echo isset($row['first_name']) ? stripslashes($row['first_name']) : ''; ?></td>
						<td><?php echo isset($row['last_name']) ? stripslashes($row['last_name']) : ''; ?></td>
						<td><?php echo isset($row['address']) ? stripslashes($row['address']) : ''; ?></td>
						<td><?php echo isset($row['zone_name']) ? stripslashes($row['zone_name']) : ''; ?></td>
						<td><?php echo isset($row['classification_name']) ? stripslashes($row['classification_name']) : ''; ?></td>
						<td>
							<span <?php 
								$status_val = isset($row['status']) ? $row['status'] : '';
								if($status_val == '1' || $status_val === 1){ 
									echo " class='label label-success arrowed-in arrowed-in-right'"; 
								} else if($status_val == '0' || $status_val === 0){ 
									echo "class='label label-warning arrowed'";
								} else if($status_val == '2' || $status_val === 2){ 
									echo "class='label label-danger arrowed'";
								} else {
									echo "class='label label-default arrowed'";
								}
							?>>
								<a href="#" style="color:#FFF; text-decoration:none;">
									<?php 
										$status_val = isset($row['status']) ? $row['status'] : '';
										if($status_val == '1' || $status_val === 1){ 
											echo "Active"; 
										} else if($status_val == '0' || $status_val === 0){ 
											echo "Inactive"; 
										} else if($status_val == '2' || $status_val === 2){ 
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
	
										
									
