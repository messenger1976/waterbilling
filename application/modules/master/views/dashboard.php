
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

				<div class="row">
					<div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
						<h1 class="page-title txt-color-blueDark"><i class="fa-fw fa fa-home"></i> Dashboard <span>> My Dashboard</span></h1>
					</div>
					<div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
						<ul id="sparks" class="">
							<li class="sparks-info">
							<?php 
							     $income1 = $this->comm_model->get_income_metercustomer();
							     extract($income1);
								 $income2 = $this->comm_model->get_income_monthlycustomer();
								 extract($income2);
								 $intotal = $total1 + $total2;
							?>
								<h5> Income <span class="txt-color-blue">PHP <?php print_r(number_format($intotal,2));?></span></h5>
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




												<!-- row -->

					<div class="row">

						<article class="col-sm-12 col-md-12 col-lg-6">

							<!-- new widget -->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-1" data-widget-editbutton="false" data-widget-fullscreenbutton="false">

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
									<span class="widget-icon"> <i class="fa fa-comments txt-color-white"></i> </span>
									<h6> SmartChat </h6>
									<div class="widget-toolbar">
										<!-- add: non-hidden - to disable auto hide -->

										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-success" data-toggle="dropdown">
												Status <i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu pull-right js-status-update">
												<li>
													<a href="javascript:void(0);"><i class="fa fa-circle txt-color-green"></i> Online</a>
												</li>
												<li>
													<a href="javascript:void(0);"><i class="fa fa-circle txt-color-red"></i> Busy</a>
												</li>
												<li>
													<a href="javascript:void(0);"><i class="fa fa-circle txt-color-orange"></i> Away</a>
												</li>
												<li class="divider"></li>
												<li>
													<a href="javascript:void(0);"><i class="fa fa-power-off"></i> Log Off</a>
												</li>
											</ul>
										</div>
									</div>
								</header>

								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<div>
											<label>Title:</label>
											<input type="text" />
										</div>
									</div>
									<!-- end widget edit box -->

									<div class="widget-body widget-hide-overflow no-padding">
										<!-- content goes here -->

										<!-- CHAT CONTAINER -->
										<div id="chat-container">
											<span class="chat-list-open-close"><i class="fa fa-user"></i><b>!</b></span>

											<div class="chat-list-body custom-scroll">
												<ul id="chat-users">
													<li>
														<a href="javascript:void(0);"><img src="img/avatars/5.png" alt="">Robin Berry <span class="badge badge-inverse">23</span><span class="state"><i class="fa fa-circle txt-color-green pull-right"></i></span></a>
													</li>
													<li>
														<a href="javascript:void(0);"><img src="img/avatars/male.png" alt="">Mark Zeukartech <span class="state"><i class="last-online pull-right">2hrs</i></span></a>
													</li>
													<li>
														<a href="javascript:void(0);"><img src="img/avatars/male.png" alt="">Belmain Dolson <span class="state"><i class="last-online pull-right">45m</i></span></a>
													</li>
													<li>
														<a href="javascript:void(0);"><img src="img/avatars/male.png" alt="">Galvitch Drewbery <span class="state"><i class="fa fa-circle txt-color-green pull-right"></i></span></a>
													</li>
													<li>
														<a href="javascript:void(0);"><img src="img/avatars/male.png" alt="">Sadi Orlaf <span class="state"><i class="fa fa-circle txt-color-green pull-right"></i></span></a>
													</li>
													<li>
														<a href="javascript:void(0);"><img src="img/avatars/male.png" alt="">Markus <span class="state"><i class="last-online pull-right">2m</i></span> </a>
													</li>
													<li>
														<a href="javascript:void(0);"><img src="img/avatars/sunny.png" alt="">Sunny <span class="state"><i class="last-online pull-right">2m</i></span> </a>
													</li>
													<li>
														<a href="javascript:void(0);"><img src="img/avatars/male.png" alt="">Denmark <span class="state"><i class="last-online pull-right">2m</i></span> </a>
													</li>
												</ul>
											</div>
											<div class="chat-list-footer">

												<div class="control-group">

													<form class="smart-form">

														<section>
															<label class="input">
																<input type="text" id="filter-chat-list" placeholder="Filter">
															</label>
														</section>

													</form>

												</div>

											</div>

										</div>

										<!-- CHAT BODY -->
										<div id="chat-body" class="chat-body custom-scroll">
											<ul>
												<li class="message">
													<img src="img/avatars/5.png" class="online" alt="">
													<div class="message-text">
														<time>
															2014-01-13
														</time> <a href="javascript:void(0);" class="username">Sadi Orlaf</a> Hey did you meet the new board of director? He's a bit of an arse if you ask me...anyway here is the report you requested. I am off to launch with Lisa and Andrew, you wanna join?
														<p class="chat-file row">
															<b class="pull-left col-sm-6"> <!--<i class="fa fa-spinner fa-spin"></i>--> <i class="fa fa-file"></i> report-2013-demographic-report-annual-earnings.xls </b>
															<span class="col-sm-6 pull-right"> <a href="javascript:void(0);" class="btn btn-xs btn-default">cancel</a> <a href="javascript:void(0);" class="btn btn-xs btn-success">save</a> </span>
														</p>
														<p class="chat-file row">
															<b class="pull-left col-sm-6"> <i class="fa fa-ok txt-color-green"></i> tobacco-report-2012.doc </b>
															<span class="col-sm-6 pull-right"> <a href="javascript:void(0);" class="btn btn-xs btn-primary">open</a> </span>
														</p> </div>
												</li>
												<li class="message">
													<img src="img/avatars/sunny.png" class="online" alt="">
													<div class="message-text">
														<time>
															2014-01-13
														</time> <a href="javascript:void(0);" class="username">John Doe</a> Haha! Yeah I know what you mean. Thanks for the file Sadi! <i class="fa fa-smile-o txt-color-orange"></i> 
													</div>
												</li>
											</ul>

										</div>

										<!-- CHAT FOOTER -->
										<div class="chat-footer">

											<!-- CHAT TEXTAREA -->
											<div class="textarea-div">

												<div class="typearea">
													<textarea placeholder="Write a reply..." id="textarea-expand" class="custom-scroll"></textarea>
												</div>

											</div>

											<!-- CHAT REPLY/SEND -->
											<span class="textarea-controls">
												<button class="btn btn-sm btn-primary pull-right">
													Reply
												</button> <span class="pull-right smart-form" style="margin-top: 3px; margin-right: 10px;"> <label class="checkbox pull-right">
														<input type="checkbox" name="subscription" id="subscription">
														<i></i>Press <strong> ENTER </strong> to send </label> </span> <a href="javascript:void(0);" class="pull-left"><i class="fa fa-camera fa-fw fa-lg"></i></a> </span>

										</div>

										<!-- end content -->
									</div>

								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->

							<!-- new widget -->
							<div class="jarviswidget jarviswidget-color-blueDark" id="wid-id-3" data-widget-colorbutton="false">

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
									<span class="widget-icon"> <i class="fa fa-calendar"></i> </span>
									<h6> My Events </h6>
									<div class="widget-toolbar">
										<!-- add: non-hidden - to disable auto hide -->
										<div class="btn-group">
											<button class="btn dropdown-toggle btn-xs btn-default" data-toggle="dropdown">
												Showing <i class="fa fa-caret-down"></i>
											</button>
											<ul class="dropdown-menu js-status-update pull-right">
												<li>
													<a href="javascript:void(0);" id="mt">Month</a>
												</li>
												<li>
													<a href="javascript:void(0);" id="ag">Agenda</a>
												</li>
												<li>
													<a href="javascript:void(0);" id="td">Today</a>
												</li>
											</ul>
										</div>
									</div>
								</header>

								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">

										<input class="form-control" type="text">

									</div>
									<!-- end widget edit box -->

									<div class="widget-body no-padding">
										<!-- content goes here -->
										<div class="widget-body-toolbar">

											<div id="calendar-buttons">

												<div class="btn-group">
													<a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-prev"><i class="fa fa-chevron-left"></i></a>
													<a href="javascript:void(0)" class="btn btn-default btn-xs" id="btn-next"><i class="fa fa-chevron-right"></i></a>
												</div>
											</div>
										</div>
										<div id="calendar"></div>

										<!-- end content -->
									</div>

								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->

						</article>

						<article class="col-sm-12 col-md-12 col-lg-6">

							<!-- new widget -->
							<div class="jarviswidget" id="wid-id-2" data-widget-colorbutton="false" data-widget-editbutton="false">

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
									<span class="widget-icon"> <i class="fa fa-map-marker"></i> </span>
									<h6>Birds Eye</h6>
									<div class="widget-toolbar hidden-mobile">
										<span class="onoffswitch-title"><i class="fa fa-location-arrow"></i> Realtime</span>
										<span class="onoffswitch">
											<input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" checked="checked" id="myonoffswitch">
											<label class="onoffswitch-label" for="myonoffswitch"> <span class="onoffswitch-inner" data-swchon-text="YES" data-swchoff-text="NO"></span> <span class="onoffswitch-switch"></span> </label> </span>
									</div>
								</header>

								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<div>
											<label>Title:</label>
											<input type="text" />
										</div>
									</div>
									<!-- end widget edit box -->

									<div class="widget-body no-padding">
										<!-- content goes here -->

										<div id="vector-map" class="vector-map"></div>
										<div id="heat-fill">
											<span class="fill-a">0</span>

											<span class="fill-b">5,000</span>
										</div>

										<table class="table table-striped table-hover table-condensed">
											<thead>
												<tr>
													<th>Country</th>
													<th>Visits</th>
													<th class="text-align-center">User Activity</th>
													<th class="text-align-center hidden-xs">Online</th>
													<th class="text-align-center">Demographic</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td><a href="javascript:void(0);">USA</a></td>
													<td>4,977</td>
													<td class="text-align-center">
													<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
														2700, 3631, 2471, 1300, 1877, 2500, 2577, 2700, 3631, 2471, 2000, 2100, 3000
													</div></td>
													<td class="text-align-center hidden-xs">143</td>
													<td class="text-align-center">
													<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
														17,83
													</div>
													<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
														<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
															<i class="fa fa-cog fa-lg"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-xs pull-right">
															<li>
																<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
															</li>
															<li>
																<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
															</li>
															<li class="divider"></li>
															<li class="text-align-center">
																<a href="javascript:void(0);">Cancel</a>
															</li>
														</ul>
													</div></td>
												</tr>
												<tr>
													<td><a href="javascript:void(0);">Australia</a></td>
													<td>4,873</td>
													<td class="text-align-center">
													<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
														1000, 1100, 3030, 1300, -1877, -2500, -2577, -2700, 3631, 2471, 4700, 1631, 2471
													</div></td>
													<td class="text-align-center hidden-xs">247</td>
													<td class="text-align-center">
													<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
														22,88
													</div>
													<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
														<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
															<i class="fa fa-cog fa-lg"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-xs pull-right">
															<li>
																<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
															</li>
															<li>
																<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
															</li>
															<li class="divider"></li>
															<li class="text-align-center">
																<a href="javascript:void(0);">Cancel</a>
															</li>
														</ul>
													</div></td>
												</tr>
												<tr>
													<td><a href="javascript:void(0);">India</a></td>
													<td>3,671</td>
													<td class="text-align-center">
													<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
														3631, 1471, 2400, 3631, 471, 1300, 1177, 2500, 2577, 3000, 4100, 3000, 7700
													</div></td>
													<td class="text-align-center hidden-xs">373</td>
													<td class="text-align-center">
													<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
														10,90
													</div>
													<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
														<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
															<i class="fa fa-cog fa-lg"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-xs pull-right">
															<li>
																<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
															</li>
															<li>
																<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
															</li>
															<li class="divider"></li>
															<li class="text-align-center">
																<a href="javascript:void(0);">Cancel</a>
															</li>
														</ul>
													</div></td>
												</tr>
												<tr>
													<td><a href="javascript:void(0);">Brazil</a></td>
													<td>2,476</td>
													<td class="text-align-center">
													<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
														2700, 1877, 2500, 2577, 2000, 3631, 2471, -2700, -3631, 2471, 1300, 2100, 3000,
													</div></td>
													<td class="text-align-center hidden-xs ">741</td>
													<td class="text-align-center">
													<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
														34,66
													</div>
													<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
														<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
															<i class="fa fa-cog fa-lg"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-xs pull-right">
															<li>
																<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
															</li>
															<li>
																<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
															</li>
															<li class="divider"></li>
															<li class="text-align-center">
																<a href="javascript:void(0);">Cancel</a>
															</li>
														</ul>
													</div></td>
												</tr>
												<tr>
													<td><a href="javascript:void(0);">Turkey</a></td>
													<td>1,476</td>
													<td class="text-align-center">
													<div class="sparkline txt-color-blue text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
														1300, 1877, 2500, 2577, 2000, 2100, 3000, -2471, -2700, -3631, -2471, 2700, 3631
													</div></td>
													<td class="text-align-center hidden-xs">123</td>
													<td class="text-align-center">
													<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
														75,25
													</div>
													<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
														<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
															<i class="fa fa-cog fa-lg"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-xs pull-right">
															<li>
																<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
															</li>
															<li>
																<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
															</li>
															<li class="divider"></li>
															<li class="text-align-center">
																<a href="javascript:void(0);">Cancel</a>
															</li>
														</ul>
													</div></td>
												</tr>
												<tr>
													<td><a href="javascript:void(0);">Canada</a></td>
													<td>146</td>
													<td class="text-align-center">
													<div class="sparkline txt-color-orange text-align-center" data-sparkline-height="22px" data-sparkline-width="90px" data-sparkline-barwidth="2">
														5, 34, 10, 1, 4, 6, -9, -1, 0, 0, 5, 6, 7
													</div></td>
													<td class="text-align-center hidden-xs">23</td>
													<td class="text-align-center">
													<div class="sparkline display-inline" data-sparkline-type='pie' data-sparkline-piecolor='["#E979BB", "#57889C"]' data-sparkline-offset="90" data-sparkline-piesize="23px">
														50,50
													</div>
													<div class="btn-group display-inline pull-right text-align-left hidden-tablet">
														<button class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown">
															<i class="fa fa-cog fa-lg"></i>
														</button>
														<ul class="dropdown-menu dropdown-menu-xs pull-right">
															<li>
																<a href="javascript:void(0);"><i class="fa fa-file fa-lg fa-fw txt-color-greenLight"></i> <u>P</u>DF</a>
															</li>
															<li>
																<a href="javascript:void(0);"><i class="fa fa-times fa-lg fa-fw txt-color-red"></i> <u>D</u>elete</a>
															</li>
															<li class="divider"></li>
															<li class="text-align-center">
																<a href="javascript:void(0);">Cancel</a>
															</li>
														</ul>
													</div></td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
													<td colspan=5>
													<ul class="pagination pagination-xs no-margin">
														<li class="prev disabled">
															<a href="javascript:void(0);">Previous</a>
														</li>
														<li class="active">
															<a href="javascript:void(0);">1</a>
														</li>
														<li>
															<a href="javascript:void(0);">2</a>
														</li>
														<li>
															<a href="javascript:void(0);">3</a>
														</li>
														<li class="next">
															<a href="javascript:void(0);">Next</a>
														</li>
													</ul></td>
												</tr>
											</tfoot>
										</table>

										<!-- end content -->

									</div>

								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->

							<!-- new widget -->
							<div class="jarviswidget jarviswidget-color-blue" id="wid-id-4" data-widget-editbutton="false" data-widget-colorbutton="false">

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
									<span class="widget-icon"> <i class="fa fa-check txt-color-white"></i> </span>
									<h6> ToDo's </h6>
									<!-- <div class="widget-toolbar">
									add: non-hidden - to disable auto hide

									</div>-->
								</header>

								<!-- widget div-->
								<div>
									<!-- widget edit box -->
									<div class="jarviswidget-editbox">
										<div>
											<label>Title:</label>
											<input type="text" />
										</div>
									</div>
									<!-- end widget edit box -->

									<div class="widget-body no-padding smart-form">
										<!-- content goes here -->
										<h5 class="todo-group-title"><i class="fa fa-warning"></i> Critical Tasks (<small class="num-of-tasks">1</small>)</h5>
										<ul id="sortable1" class="todo">
											<li>
												<span class="handle"> <label class="checkbox">
														<input type="checkbox" name="checkbox-inline">
														<i></i> </label> </span>
												<p>
													<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
													<span class="date">Jan 1, 2014</span>
												</p>
											</li>
										</ul>
										<h5 class="todo-group-title"><i class="fa fa-exclamation"></i> Important Tasks (<small class="num-of-tasks">3</small>)</h5>
										<ul id="sortable2" class="todo">
											<li>
												<span class="handle"> <label class="checkbox">
														<input type="checkbox" name="checkbox-inline">
														<i></i> </label> </span>
												<p>
													<strong>Ticket #1347</strong> - Inbox email is being sent twice <small>(bug fix)</small> [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="date">Nov 22, 2013</span>
												</p>
											</li>
											<li>
												<span class="handle"> <label class="checkbox">
														<input type="checkbox" name="checkbox-inline">
														<i></i> </label> </span>
												<p>
													<strong>Ticket #1314</strong> - Call customer support re: Issue <a href="javascript:void(0);" class="font-xs">#6134</a><small>(code review)</small>
													<span class="date">Nov 22, 2013</span>
												</p>
											</li>
											<li>
												<span class="handle"> <label class="checkbox">
														<input type="checkbox" name="checkbox-inline">
														<i></i> </label> </span>
												<p>
													<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
													<span class="date">Jan 1, 2014</span>
												</p>
											</li>
										</ul>

										<h5 class="todo-group-title"><i class="fa fa-check"></i> Completed Tasks (<small class="num-of-tasks">1</small>)</h5>
										<ul id="sortable3" class="todo">
											<li class="complete">
												<span class="handle" style="display:none"> <label class="checkbox state-disabled">
														<input type="checkbox" name="checkbox-inline" checked="checked" disabled="disabled">
														<i></i> </label> </span>
												<p>
													<strong>Ticket #17643</strong> - Hotfix for WebApp interface issue [<a href="javascript:void(0);" class="font-xs">More Details</a>] <span class="text-muted">Sea deep blessed bearing under darkness from God air living isn't. </span>
													<span class="date">Jan 1, 2014</span>
												</p>
											</li>
										</ul>

										<!-- end content -->
									</div>

								</div>
								<!-- end widget div -->
							</div>
							<!-- end widget -->

						</article>

					</div>

					<!-- end row -->

					

							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-0" data-widget-editbutton="false">
								
								<header>
									<span class="widget-icon"> <i class="fa fa-table"></i> </span>
									<h6>&nbsp;List of Customers ( <?php echo count(array_filter($customer)) ?> )</h6>
				
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
				                       <?php if(count(array_filter($customer)) > 0){?>
										<table id="firstpagei" class="table table-striped table-bordered table-hover" width="100%">
										
											<thead>			                
												<tr>
													<th data-hide="phone" width="5%">S No</th>
													<th data-class="expand" width="10%"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Customer id</th>
													<th data-hide="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Customer Name </th>
													<th data-hide="expand">Pay Time</th>
													
												</tr>
											</thead>
											<tbody>
											  <?php foreach($customer as $key => $value){?>
												<tr>
													<td><?php echo $key+1;?></td>
													<td><?php echo stripslashes(str_replace('\n','',$value['customer_id']));?></td>
													<td><?php echo stripslashes($value['full_name']);?></td>
													<td><?php 
															   if($value['customer_type']=='metercustomer'){ echo 'Meter'; }
															   if($value['customer_type']=='monthlycustomer'){ echo 'Monthly'; }
								                        ?>
													</td>
												</tr>
											  <?php } ?>	
											</tbody>
										</table>
										 <?php }else{?>
											 <div class="clearfix" style="height:250px; text-align:center; font-size:16px; color:#F00; vertical-align:middle; font-weight:bold;margin-top:40px;">
												No Results Found...
											 </div>
										<?php }?>

									</div>
									<!-- end widget content -->
				
								</div>
								<!-- end widget div -->
				
							</div>
							<!-- end widget -->
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">
								
								<header>
									<span class="widget-icon"> <i class="fa fa-table"></i> </span>
									<h6>&nbsp;Expenses Reports ( <?php echo count($expenses);?> )</h6>
				
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
				                       <?php if(count(array_filter($customer)) > 0){?>
										<table id="expensesmore" class="table table-striped table-bordered table-hover" width="100%">
										    <thead>			                
												<tr>
													<th data-hide="phone">S No</th>
													<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> ID</th>
													<th data-hide="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Type </th>
													<th data-hide="expand">Total</th>
												</tr>
											</thead>
											<tbody>
											  <?php foreach($expenses as $key => $value){?>
												<tr>
													<td><?php echo $key+1;?></td>
													<td><?php echo stripslashes(str_replace('\n','',$value['expenses_id']));?></td>
													<td><?php echo stripslashes($value['expenses_type']);?></td>
													<td><?php echo stripslashes($value['total']);?></td>
												</tr>
											  <?php } ?>	
											</tbody>
										</table>
										 <?php }else{?>
											 <div class="clearfix" style="height:250px; text-align:center; font-size:16px; color:#F00; vertical-align:middle; font-weight:bold;margin-top:40px;">
												No Results Found...
											 </div>
										<?php }?>

									</div>
									<!-- end widget content -->
				
								</div>
								<!-- end widget div -->
				
							</div>
							<!-- end widget -->


				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-2" data-widget-editbutton="false">
								
								<header>
									<span class="widget-icon"> <i class="fa fa-table"></i> </span>
									<h6>&nbsp;This Month Expenses ( <?php echo count($this_expenses);?> )</h6>
				
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
				                       <?php if(count(array_filter($this_expenses)) > 0){?>
										<table id= "monthexp" class="table table-striped table-bordered table-hover" width="100%">
										    <thead>			                
												<tr>
													<th data-hide="phone">S No</th>
													<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> ID</th>
													<th data-hide="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Type </th>
													<th data-hide="expand">Total</th>
												</tr>
											</thead>
											<tbody>
											   <?php foreach($this_expenses as $key => $value){?>
												<tr>
													<td><?php echo $key+1;?></td>
													<td><?php echo stripslashes(str_replace('\n','',$value['expenses_id']));?></td>
													<td><?php echo stripslashes($value['expenses_type']);?></td>
													<td><?php echo stripslashes($value['total']);?></td>
												</tr>
											  <?php } ?>	
											</tbody>
										</table>
										 <?php }else{?>
											 <div class="clearfix" style="height:250px; text-align:center; font-size:16px; color:#F00; vertical-align:middle; font-weight:bold;margin-top:40px;">
												No Results Found...
											 </div>
										<?php }?>

									</div>
									<!-- end widget content -->
				
								</div>
								<!-- end widget div -->
				
							</div>
							<!-- end widget -->
				
							<!-- Widget ID (each widget will need unique ID)-->
							<div class="jarviswidget jarviswidget-color-darken" id="wid-id-2" data-widget-editbutton="false">
								
								<header>
									<span class="widget-icon"> <i class="fa fa-table"></i> </span>
									<h6>&nbsp;This Month Income ( <?php echo  count($meter_income)+count($monthly_income);?> )</h6>
				
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
				                       <?php if(count(array_filter($meter_income)) > 0 || count(array_filter($monthly_income)) > 0){?>
										<table id="dt_basic" class="table table-striped table-bordered table-hover" width="100%">
										    <thead>			                
												<tr>
													<th data-hide="phone">S No</th>
													<th data-class="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i>Customer ID</th>
													<th data-hide="expand"><i class="fa fa-fw fa-user text-muted hidden-md hidden-sm hidden-xs"></i> Name </th>
													<th data-hide="expand">Amount</th>
												</tr>
											</thead>
											<tbody>
											   <?php foreach($meter_income as $key => $value){?>
												<tr>
													<td><?php echo $key+1;?></td>
													<td><?php echo stripslashes(str_replace('\n','',$value['customer_id']));?></td>
													<td><?php echo stripslashes($value['name']);?></td>
													<td><?php echo stripslashes($value['total']);?></td>
												</tr>
											   <?php } ?>
											   
											   <?php if(count(array_filter($meter_income)) > 0){ $i=count(($meter_income)); }else{ $i=1;} ?> 
											   
												   <?php foreach($monthly_income as $key => $value){?>
													<tr>
														<td><?php echo $key+$i;?></td>
														<td><?php echo stripslashes(str_replace('\n','',$value['customer_id']));?></td>
														<td><?php echo stripslashes($value['name']);?></td>
														<td><?php echo stripslashes($value['amount']);?></td>
													</tr>
												   <?php } ?>
											</tbody>
										</table>
											 <?php }else{?>
											 <div class="clearfix" style="height:250px; text-align:center; font-size:16px; color:#F00; vertical-align:middle; font-weight:bold;margin-top:40px;">
												No Results Found...
											 </div>
										<?php }?>

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

					var data1 = [];
					for (var i = 1; i <= 12; i += 1)
						data1.push([i, parseInt(Math.random() * 50)]);

					var data2 = [];
					for (var i = 1; i <= 12; i += 1)
						data2.push([i, parseInt(Math.random() * 60)]);

					var data3 = [];
					for (var i = 1; i <= 12; i += 1)
						data3.push([i, parseInt(Math.random() * 70)]);

					var ds = new Array();

					ds.push({
						data : data1,
						bars : {
							show : true,
							barWidth : 0.2,
							order : 1,
						}
					});
					ds.push({
						data : data2,
						bars : {
							show : true,
							barWidth : 0.2,
							order : 2
						}
					});
					ds.push({
						data : data3,
						bars : {
							show : true,
							barWidth : 0.2,
							order : 3
						}
					});

					//Display graph
					$.plot($("#bar-chart"), ds, {
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

					});



					
    // Sample data for the bar chart
    const data = [
        [1, 15],
        [2, 25],
        [3, 30],
        [4, 18],
        [5, 22]
    ];

    // Options for the chart
    const options = {
        series: {
            bars: {
                show: true,
                barWidth: 0.6,
                align: "center"
            }
        },
        xaxis: {
            mode: "categories",
            ticks: [
                [1, "Jan"],
                [2, "Feb"],
                [3, "Mar"],
                [4, "Apr"],
                [5, "May"]
            ]
        },
        yaxis: {
            min: 0,
            max: 40
        }
    };

    // Plot the chart
    /*$.plot($("#bar-chart"), [{
        data: data
    }], options);*/



				}
		
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
