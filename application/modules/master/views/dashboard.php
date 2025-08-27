
<!-- MAIN PANEL -->
		<div id="main" role="main">

			<!-- RIBBON -->
			<div id="ribbon">

				<span class="ribbon-button-alignment"> 
					<span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
						<i class="fa fa-refresh"></i>
					</span> 
				</span>

				<!-- breadcrumb -->
				<ol class="breadcrumb">
					<li>Home</li><li>Dashboard</li>
				</ol>
				
			</div>
			<!-- END RIBBON -->

			<!-- MAIN CONTENT -->
			<div id="content">

				<!--<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> My Dashboard</span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
							
								<h5> Income <span class="txt-color-blue">PHP </span></h5>
								<div class="sparkline txt-color-blue hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php
							     $expense1 = $this->my_model->get_outcome_expenses();
							     extract($expense1);
								 $expense2 = $this->my_model->get_outcome_payroll();
								 extract($expense2);
								 $extotal = $extotal1 + $extotal2;
							?>
							<li class="sparks-info">
								<h5> Expense <span class="txt-color-purple">PHP <?php print_r(number_format($extotal,2));?></span></h5>
								<div class="sparkline txt-color-purple hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
							<?php 
							     $total_customer = $this->my_model->total_customer();
							     extract($total_customer); 
							?>
							<li class="sparks-info">
								<h5> Total Customer <span class="txt-color-greenDark">&nbsp;<?php print_r($count_id);?></span></h5>
								<div class="sparkline txt-color-greenDark hidden-mobile hidden-md hidden-sm">
									
								</div>
							</li>
						</ul>
					</div>
				</div>-->


				<div class="row">
					
					<div class="col-sm-12">
						
						<div class="well well-light">
							
							<!--<h1>Professional, <small>4 Plans</small></h1>-->
							<div class="row">
								
						        <div class="col-xs-12 col-sm-6 col-md-3">
						            <div class="panel panel-success pricing-big">
						            	
						                <div class="panel-heading">
						                    <h3 class="panel-title"><i class="fa fa-bar-chart-o fa-lg"></i>
						                        Total Sales</h3>
						                </div>
						                <div class="panel-body no-padding text-align-center">
						                    <div class="the-price">
						                        <h1>
													<?php 
							     $income1 = $this->comm_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->comm_model->get_income_monthlycustomer();
								 extract($income2);
								 $intotal = $total1 + $total2;
							?>
						                            <strong>₱ <?php print_r(number_format($intotal,2));?></strong></h1>
						                    </div>
											
						                </div>
						                <div class="panel-footer text-align-center">
						                    <a href="<?php echo ADMIN_URL;?>addpaymentcustomer/add" class="btn btn-primary btn-block" role="button">Add Bills Payment <span> now!</span></a>
						                	
						                </div>
						            </div>
						        </div>
						        
						        <div class="col-xs-12 col-sm-6 col-md-3">
						            <div class="panel panel-teal pricing-big">
						            	
						                <div class="panel-heading">
						                    <h3 class="panel-title"><i class="fa fa-user fa-lg"></i>
						                        Total Customers</h3>
						                </div>
						                <div class="panel-body no-padding text-align-center">
						                    <div class="the-price">
						                        <h1>
													<?php 
							     $total_customer = $this->my_model->total_customer();
							     extract($total_customer); 
							?>
						                            <strong><?php print_r($count_id);?></strong></h1>
						                    </div>
											
						                </div>
						                <div class="panel-footer text-align-center">
						                    <a href="<?php echo ADMIN_URL;?>addcustomer" class="btn btn-primary btn-block" role="button">Customers Listing<span> now!</span></a>
						                	
						                </div>
						            </div>
						        </div>
						        
						        <div class="col-xs-12 col-sm-6 col-md-3">
						            <div class="panel panel-primary pricing-big">
						            	<img src="img/ribbon.png" class="ribbon" alt="">
						                <div class="panel-heading">
						                    <h3 class="panel-title"><i class="fa fa-money fa-lg"></i>
						                        Total Leaking Balances</h3>
						                </div>
						                <div class="panel-body no-padding text-align-center">
						                    <div class="the-price">
						                        <h1>
						                           <strong>₱ <?php print_r(number_format($leaking['total'],2));?></strong></h1>
						                    </div>
											
						                </div>
						                <div class="panel-footer text-align-center">
						                    <a href="<?php echo ADMIN_URL;?>Leakingentry" class="btn btn-primary btn-block" role="button">Leaking Listing<span> now!</span></a>
						                	
						                </div>
						            </div>
						        </div>
						        
						        <div class="col-xs-12 col-sm-6 col-md-3">
						            <div class="panel panel-darken pricing-big">
						            	
						                <div class="panel-heading">
						                    <h3 class="panel-title"><i class="fa fa-comments fa-lg"></i>
						                        Open Tickets</h3>
						                </div>
						                <div class="panel-body no-padding text-align-center">
						                    <div class="the-price">
						                        <h1>
						                            <strong><?php print_r($total_prbm);?></strong></h1>
						                    </div>
											
						                </div>
						                <div class="panel-footer text-align-center">
						                    <a href="<?php echo ADMIN_URL;?>technicalproblems" class="btn btn-primary btn-block" role="button">Tickets Listing<span> now!</span></a>
						                	
						                </div>
						            </div>
						        </div>		    	
				    		</div>
				
							
				
		
							
						</div>
						
					</div>
					
				</div>

				<!-- widget grid -->
				<section id="widget-grid" class="">

					<!-- row -->
					<div class="row">
				
						<!-- NEW WIDGET START -->
						<article class="col-sm-12">

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
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
									<span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
									<h6>&nbsp;Sales Chart</h6>

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

										<div id="bar-chart" class="chart"></div>

									</div>
									<!-- end widget content -->

								</div>
								<!-- end widget div -->

							</div>
							<!-- end widget -->




												

					

							
				
							


				
							
				
							
				
						</article>
						<!-- WIDGET END -->
				
					</div>
				
					<!-- end row -->

					

				</section>
				<!-- end widget grid -->

			</div>
			<!-- END MAIN CONTENT -->

		</div>
		<!-- END MAIN PANEL -->
		

		<?php include('footer.php');?>

	</body>

</html>

<!-- PAGE RELATED PLUGIN(S) -->
		<script src="<?php echo base_url();?>js/plugin/datatables/jquery.dataTables.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.colVis.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.tableTools.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatables/dataTables.bootstrap.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/datatable-responsive/datatables.responsive.min.js"></script>



		<!-- Flot Chart Plugin: Flot Engine, Flot Resizer, Flot Tooltip -->
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.cust.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.resize.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.fillbetween.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.orderBar.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.pie.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.time.min.js"></script>
		<script src="<?php echo base_url();?>js/plugin/flot/jquery.flot.tooltip.min.js"></script>

		<!-- EASY PIE CHARTS -->
		<script src="js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
    

		<script type="text/javascript">
		
		/* chart colors default */
			var chrt_border_color = "#efefef";
			var chrt_grid_color = "#DDD";
			var chrt_main = "#E24913";
			/* red       */
			var chrt_second = "#6595b4";
			/* blue      */
			var chrt_third = "#FF9F01";
			/* orange    */
			var chrt_fourth = "#7e9d3a";
			/* green     */
			var chrt_fifth = "#BD362F";
			/* dark red  */
			var chrt_mono = "#000";

		// DO NOT REMOVE : GLOBAL FUNCTIONS!
		
		$(document).ready(function() {
			
			pageSetUp();
			
			/* // DOM Position key index //
		
			l - Length changing (dropdown)
			f - Filtering input (search)
			t - The Table! (datatable)
			i - Information (records)
			p - Pagination (paging)
			r - pRocessing 
			< and > - div elements
			<"#id" and > - div with an id
			<"class" and > - div with a class
			<"#id.class" and > - div with an id and class
			
			Also see: http://legacy.datatables.net/usage/features
			*/	
	
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
			
			/* COLUMN FILTER  */
		    var otable = $('#datatable_fixed_column').DataTable({
		    	//"bFilter": false,
		    	//"bInfo": false,
		    	//"bLengthChange": false
		    	//"bAutoWidth": false,
		    	//"bPaginate": false,
		    	//"bStateSave": true // saves sort state using localStorage
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6 hidden-xs'f><'col-sm-6 col-xs-12 hidden-xs'<'toolbar'>>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
				"autoWidth" : true,
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_fixed_column) {
						responsiveHelper_datatable_fixed_column = new ResponsiveDatatablesHelper($('#datatable_fixed_column'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_fixed_column.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_fixed_column.respond();
				}		
			
		    });
		    
		    // custom toolbar
		    $("div.toolbar").html('<div class="text-right"><img src="img/logo.png" alt="SmartAdmin" style="width: 111px; margin-top: 3px; margin-right: 10px;"></div>');
		    	   
		    // Apply the filter
		    $("#datatable_fixed_column thead th input[type=text]").on( 'keyup change', function () {
		    	
		        otable
		            .column( $(this).parent().index()+':visible' )
		            .search( this.value )
		            .draw();
		            
		    } );
		    /* END COLUMN FILTER */   
	    
			/* COLUMN SHOW - HIDE */
			$('#datatable_col_reorder').dataTable({
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'C>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				"autoWidth" : true,
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_col_reorder) {
						responsiveHelper_datatable_col_reorder = new ResponsiveDatatablesHelper($('#datatable_col_reorder'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_col_reorder.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_col_reorder.respond();
				}			
			});
			
			/* END COLUMN SHOW - HIDE */
	
			/* TABLETOOLS */
			$('#datatable_tabletools').dataTable({
				
				// Tabletools options: 
				//   https://datatables.net/extensions/tabletools/button_options
				"sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-6 hidden-xs'T>r>"+
						"t"+
						"<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-sm-6 col-xs-12'p>>",
				"oLanguage": {
					"sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
				},		
		        "oTableTools": {
		        	 "aButtons": [
		             "copy",
		             "csv",
		             "xls",
		                {
		                    "sExtends": "pdf",
		                    "sTitle": "SmartAdmin_PDF",
		                    "sPdfMessage": "SmartAdmin PDF Export",
		                    "sPdfSize": "letter"
		                },
		             	{
	                    	"sExtends": "print",
	                    	"sMessage": "Generated by SmartAdmin <i>(press Esc to close)</i>"
	                	}
		             ],
		            "sSwfPath": "js/plugin/datatables/swf/copy_csv_xls_pdf.swf"
		        },
				"autoWidth" : true,
				"preDrawCallback" : function() {
					// Initialize the responsive datatables helper once.
					if (!responsiveHelper_datatable_tabletools) {
						responsiveHelper_datatable_tabletools = new ResponsiveDatatablesHelper($('#datatable_tabletools'), breakpointDefinition);
					}
				},
				"rowCallback" : function(nRow) {
					responsiveHelper_datatable_tabletools.createExpandIcon(nRow);
				},
				"drawCallback" : function(oSettings) {
					responsiveHelper_datatable_tabletools.respond();
				}
			});
			
			/* END TABLETOOLS */



				if ($("#bar-chart").length) {

					//var data1 = [[1,1000],[2,2000],[3,3000],[4,5000],[5,5045],[6,5000],[7,6000],[8,8000],[9,8900],[10,10000],[11,1000],[12,3400]];
					/*for (var i = 1; i <= 12; i += 1)
						data1.push([i, parseInt(data[i])]);*/
					var data2 = [
						[0,<?php echo number_format($JanTotalPaid['total'],0,'.','');?>],
						[1,<?php echo number_format($FebTotalPaid['total'],0,'.','');?>],
						[2,<?php echo number_format($MarchTotalPaid['total'],0,'.','');?>],
						[3,<?php echo number_format($AprilTotalPaid['total'],0,'.','');?>],
						[4,<?php echo number_format($MayTotalPaid['total'],0,'.','');?>],
						[5,<?php echo $JuneTotalPaid['total'];?>],
						[6,<?php echo $JulyTotalPaid['total'];?>],
						[7,<?php echo $AugTotalPaid['total'];?>],
						[8,<?php echo $SepTotalPaid['total'];?>],
						[9,<?php echo $OctTotalPaid['total'];?>],
						[10,<?php echo $NovTotalPaid['total'];?>],
						[11,<?php echo $DecTotalPaid['total'];?>]];

					var data1 = [
						[0,<?php echo number_format($JanTotalUnpaid['total'],0,'.','');?>],
						[1,<?php echo $FebTotalUnpaid['total'];?>],
						[2,<?php echo $MarchTotalUnpaid['total'];?>],
						[3,<?php echo $AprilTotalUnpaid['total'];?>],
						[4,<?php echo $MayTotalUnpaid['total'];?>],
						[5,<?php echo $JuneTotalUnpaid['total'];?>],
						[6,<?php echo $JulyTotalUnpaid['total'];?>],
						[7,<?php echo $AugTotalUnpaid['total'];?>],
						[8,<?php echo $SepTotalUnpaid['total'];?>],
						[9,<?php echo $OctTotalUnpaid['total'];?>],
						[10,<?php echo $NovTotalUnpaid['total'];?>],
						[11,<?php echo $DecTotalUnpaid['total'];?>]];
					/*for (var i = 1; i <= 12; i += 1)
						data2.push([i, parseInt(Math.random() * 60)]);*/

					var data3 = [
						[0,<?php echo number_format($JanTotal['total'],0,'.','');?>],
						[1,<?php echo $FebTotal['total'];?>],
						[2,<?php echo $MarchTotal['total'];?>],
						[3,<?php echo $AprilTotal['total'];?>],
						[4,<?php echo $MayTotal['total'];?>],
						[5,<?php echo $JuneTotal['total'];?>],
						[6,<?php echo $JulyTotal['total'];?>],
						[7,<?php echo $AugTotal['total'];?>],
						[8,<?php echo $SepTotal['total'];?>],
						[9,<?php echo $OctTotal['total'];?>],
						[10,<?php echo $NovTotal['total'];?>],
						[11,<?php echo $DecTotal['total'];?>]];
					/*for (var i = 1; i <= 12; i += 1)
						data3.push([i, parseInt(Math.random() * 70)]);*/

					var ds = new Array();

					ds.push({
						label: 'Unpaid',
						data : data1,
						bars : {
							show : true,
							barWidth : 0.2,
							order : 1,
						}
					});
					ds.push({
						label: 'Total Sales Collection',
						data : data2,
						bars : {
							show : true,
							barWidth : 0.2,
							order : 2
						}
					});
					ds.push({
						label: 'Total Billing Collection',
						data : data3,
						bars : {
							show : true,
							barWidth : 0.2,
							order : 3
						}
					});

					//Display graph
					/*$.plot($("#bar-chart"), ds, {
						colors : [chrt_second, chrt_fourth, "#666", "#BBB"],
						grid : {
							show : true,
							hoverable : true,
							clickable : true,
							tickColor : chrt_border_color,
							borderWidth : 0,
							borderColor : chrt_border_color,
						},
						legend : true,
						tooltip : true,
						tooltipOpts : {
							content : "<b>%x</b> = <span>%y</span>",
							defaultTheme : false
						}

					});*/
    
	const ticks = [
        [0, "January"],
		[1, "February"],
		[2, "March"],
		[3, "April"],
		[4, "May"],
		[5, "Jun"],
		[6, "July"],
		[7, "August"],
		[8, "September"],
		[9, "October"],
		[10, "November"],
		[11, "December"]
    ];
    // Options for the chart
    const options = {
        series: {
            bars: {
                show: true,
                barWidth: 0.6,
                align: "center"
            },
			
        },
        xaxis: {
            mode: "categories",
            ticks: ticks
        },
        colors : [chrt_second, chrt_fourth, "#666", "#BBB"],
		grid : {
			show : true,
			hoverable : true,
			clickable : true,
			tickColor : chrt_border_color,
			borderWidth : 0,
			borderColor : chrt_border_color,
		},
		legend : true,
		tooltip : true,
		tooltipOpts: {
            content: "<b>%s</b> = <span>%y</span>",
            defaultTheme: false
        }
    };

    // Plot the chart
    $.plot($("#bar-chart"),ds, options);



				}





				/* pie chart */

				if ($('#pie-chart').length) {

					var data_pie = [];
					var series = Math.floor(Math.random() * 10) + 1;
					for (var i = 0; i < series; i++) {
						data_pie[i] = {
							label : "Series" + (i + 1),
							data : Math.floor(Math.random() * 100) + 1
						}
					}

					$.plot($("#pie-chart"), data_pie, {
						series : {
							pie : {
								show : true,
								innerRadius : 0.5,
								radius : 1,
								label : {
									show : false,
									radius : 2 / 3,
									formatter : function(label, series) {
										return '<div style="font-size:11px;text-align:center;padding:4px;color:white;">' + label + '<br/>' + Math.round(series.percent) + '%</div>';
									},
									threshold : 0.1
								}
							}
						},
						legend : {
							show : true,
							noColumns : 1, // number of colums in legend table
							labelFormatter : null, // fn: string -> string
							labelBoxBorderColor : "#000", // border color for the little label boxes
							container : null, // container (as jQuery object) to put legend in, null means default on top of graph
							position : "ne", // position of default legend container within plot
							margin : [5, 10], // distance from grid edge to default legend container within plot
							backgroundColor : "#efefef", // null means auto-detect
							backgroundOpacity : 1 // set to 0 to avoid background
						},
						grid : {
							hoverable : true,
							clickable : true
						},
					});

				}

				/* end pie chart */
		
		})



		</script>

		
		<script>				
		/*$(document).ready(function() {
			$('#expensesmore').dataTable().fnDestroy();
			table = $('#expensesmore').dataTable( {
				"ajax": {
					url: "<?php echo base_url();?>master/dashboard/index/pagination",
					data: {},
					type: "POST",
				},
				"columns": [
			{ "data": "id" },
			{ "data": "expenses_id" },
			{ "data": "expenses_type" },
			{ "data": "total" },			
			],
			"language": {
            "infoEmpty": "No Indent Available.",
				sProcessing: '<i class="fa fa-spinner fa-pulse  fa-4x" ></i>'
						},
					processing : true
				});
		});	*/
		</script>
				
		<script>				
		$(document).ready(function() {
			$('#monthexp').dataTable().fnDestroy();
			table = $('#monthexp').dataTable( {
				"ajax": {
					url: "<?php echo base_url();?>master/dashboard/index/paginationtwo",
					data: {},
					type: "POST",
				},
				"columns": [
			{ "data": "id" },
			{ "data": "expenses_id" },
			{ "data": "expenses_type" },
			{ "data": "total" },			
			],
			"language": {
            "infoEmpty": "No Indent Available.",
				sProcessing: '<i class="fa fa-spinner fa-pulse  fa-4x" ></i>'
						},
					processing : true
				});
	});	
				</script>
				
	<script>				
					$(document).ready(function() {
					var f = 'Meter';
					var s = 'Monthly';
			$('#firstpagei').dataTable().fnDestroy();
			table = $('#firstpagei').dataTable( {
				"ajax": {
					url: "<?php echo base_url();?>index.php/master/dashboard/index/paginationfrst",
					data: {},
					type: "POST",
				},
				"columns": [
			{ "data": "id" },
			{ "data": "customer_id" },
			{ "data": "full_name" },
			{ "data": "customer_type",
				render: function ( data, type, row ) {
                        if(data == 'monthlycustomer'){ return 'monthly'; };
                        if(data == 'metercustomer'){ return 'meter'; };
				},
			},		
			],
			"language": {
            "infoEmpty": "No Data Available.",
				sProcessing: '<i class="fa fa-spinner fa-pulse  fa-4x" ></i>'
						},
					processing : true
				});
	});	
				</script>
