<!DOCTYPE html>
	
<!-- MAIN PANEL -->
		<div id="main" role="main">
			<div id="ribbon">
				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>
				<ol class="breadcrumb">
					<li><a href="<?php echo ADMIN_URL;?>">Home</a></li>
					<li><a href="<?php echo ADMIN_URL;?>reports/arrears_monitoring_report/">Arrears Monitoring</a></li>
					<li>search</li>
				</ol>
			</div>
			<div id="content">
				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="glyphicon glyphicon-search"></i>Display <span>>  Arrears Monitoring Report </span></h1>
					</div>
				</div>
				<section id="widget-grid" class="">
					<div class="row">
						<div class="col-sm-6 col-lg-12">
								<div class="panel panel-default">
									<div class="widget-body">
											<?php if($msg != ''){?>
											<div class="alert alert-block alert-success">
												<button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
												<p><i class="icon-ok"></i><?php echo $msg?$msg:'';?></p>
											</div>
											<?php } ?>
											<fieldset>
												<legend>
													Arrears Monitoring -Search 
													<div  class="pull-right" style="padding-right:20px;">
														<button type="submit" class="btn btn-sm btn-primary" name="display" id="display" style="margin-bottom: 5px;">Display</button>
													</div>
												</legend>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls"><div class="form-group">
														<span class="input-group-addon"><i class="icon-user"></i><strong>As of Date:</strong></span>
														<input class="form-control" type="text" id="asofdate" name="asofdate" placeholder="DD-MM-YYYY" value="" required>
													</div></div>
												</div>
												<div class="form-group col-lg-6">
													<div class="col-lg-12 controls"><div class="form-group">
														<span class="input-group-addon"><i class="icon-user"></i><strong> Zone:</strong></span>
														<select class="form-control" name="zone" id="zone" required>
															<option value="0">--All--</option>
															<?php foreach($zone as $key => $value){ ?><option value="<?php echo $value['id'];?>"><?php echo $value['zone'];?></option><?php } ?>
														</select>
													</div></div>
												</div>
												<div class="form-group col-lg-6">
                                                    <div class="col-lg-12 controls"><div class="form-group">
                                                        <span class="input-group-addon"><i class="icon-user"></i><strong>Status : </strong></span>
                                                        <select class="form-control" name="status" id="status" required>
                                                            <option value="">--All--</option><option value="1">Active</option><option value="0">Inactive</option><option value="2">Disconnected</option>
                                                        </select>
                                                    </div></div>
                                                </div>
												<div style="clear:both"></div>
												<div class="col-xs-12" id="paidcustomerDiv" style="margin-top: 13px;"></div>
											</fieldset>
									</div>
								</div>	
						</div>
				    </div>
				</section>
			</div>
		</div>
		<?php include('footer.php');?>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	</body>
</html>
<script type="text/javascript">
$(document).ready(function(){
	$("#asofdate").datepicker({showAnim:null,dateFormat:'dd-mm-yy',buttonImage:'/images/calender.jpg',buttonImageOnly:true,firstDay:1,nextText:'',prevText:'',numberOfMonths:[1,1]});
    $('#display').on('click', function(evt){evt.preventDefault();var asofdate=$("#asofdate").val();var zone=$("#zone").val();var status=$("#status").val();if(asofdate===''){alert("Please select As of Date");return false;}showSpinner();$.ajax({type:"POST",url:'<?php echo ADMIN_URL;?>reports/getarrearsmonitoringsearch',data:"asofdate="+asofdate+"&zone="+zone+"&status="+status,complete:function(data){$("#paidcustomerDiv").html(data.responseText.trim());hideSpinner();}});});
});
</script>
