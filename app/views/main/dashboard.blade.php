@extends("layouts/dashboard_master")
@section('content')
	<section>
		
	</section>
@stop
@section('small_widgets')
	<div class="row state-overview">
		@foreach($small_widgets as $widget)
			<div class="col-lg-3 col-sm-6">
		        <section class="panel">
		            <div class="symbol {{$widget['color']}}">
		                <i class="fa {{$widget['icon']}}"></i>
		            </div>
		            <div class="value"><h1 class=" count2" id="{{$widget['elementid']}}">0</h1><p>{{$widget['title']}}</p></div>
		        </section>
		    </div>
            <script>
				countUp('{{$widget['value']}}','{{$widget['elementid']}}');
				//alert('{{$widget['value']}}'+'{{$widget['elementid']}}');
			</script>
		@endforeach
	</div>
@stop
@section('dashboard_panels')
	<div class="row">
    	<div class="col-lg-6">
        	
        	<section class="panel">
            	<header class="panel-heading">General Service Jobs Report</header>
                <div id="general_service_jobs" class="panel-body">
                <form name="calendarform" id="calendarform" method="get" onsubmit="return false;">
                	<div class="col-sm-12">
                    	<div class="col-sm-2">
                        	<button class="btn btn-primary" name="get_current_month_data" id="get_current_month_data" value="1" onclick="get_report_data('get_current_month_data');">CURRENT</button>
                        </div>
                        <div class="col-sm-1">
                        	<button class="btn btn-info" name="get_last_month_data" id="get_last_month_data" value="1" onclick="get_report_data('get_last_month_data')">&lt;&lt;</button>
                        </div>
                        <div class="col-sm-3">
                        	<select class="col-sm-12 form-control m-bot15" name="m" id="m">
                            @foreach($month_array as $mon)
                                @if($date_vals['curr_m'] == $mon[0])
                                <option value="{{@$mon[0]}}" selected="selected">{{@$mon[1]}}</option>
                                @else
                                <option value="{{@$mon[0]}}">{{@$mon[1]}}</option>
                                @endif
                            @endforeach
                            </select>
                        </div>
                        <div class="col-sm-3">
                        	<select class="col-sm-12 form-control m-bot15" name="y" id="y">
                            @for($i=$date_vals['s_year']; $i <= $date_vals['e_year']; $i++)
                                @if($date_vals['curr_y'] == $i)
                                <option value="{{$i}}" selected="selected">{{$i}}</option>
                                @else
                                <option value="{{$i}}">{{$i}}</option>
                                @endif
                            @endfor
                            </select>
                        </div>
                        <div class="col-sm-1">
                        	<button class="btn btn-primary" name="" id="btn_go" onclick="get_report_data('selection')">GO</button>
                            <span id="loading-img"></span>
                        </div>
                        <div class="col-sm-2">
                        	<button class="btn btn-info" name="get_next_month_data" id="get_next_month_data" value="1" onclick="get_report_data('get_next_month_data')">&gt;&gt;</button>
                        </div>
                    </div>
                    <div class="col-sm-12 bord-bottom">
                        <div class="col-sm-6">
                            <label><input type="radio" onclick="" checked="" value="created_date" name="general_service_jobs_radio">Job against created date</label>
                        </div>
                        <div class="col-sm-6">
                        <label><input type="radio" onclick="" value="schedule_date" name="general_service_jobs_radio">Job against schedule date</label>
                        </div>
                    </div>
                </form>
                	<div class="col-sm-12" id="reports_results_box">
                        <div class="col-sm-12">
                            <table class="display table-hover table table-bordered table-striped">
                              <thead>
                              <tr>
                                  <th rowspan="2">Job Type</th>
                                  <th rowspan="2">Added</th>
                                  <th rowspan="2">Completed</th>
                                  <th colspan="2" style="text-align:center;">Open</th>
                                  <th rowspan="2">Allocated Hr.</th>
                              </tr>
                              <tr>
                                  <th>Jobs</th>
                                  <th>Contracts</th>
                              </tr>
                              </thead>
                              <tbody>
                              @foreach($jobs_report_panel_data as $p_data)
                                <tr>
                                    <td>{{$p_data['title']}}</td>
                                    <td class="center-text">{{$p_data['added']}}</td>
                                    <td class="center-text">{{$p_data['completed']}}</td>
                                    <td class="center-text">{{$p_data['open']}}</td>
                                    <td class="center-text">{{$p_data['contract']}}</td>
                                    <td class="center-text">{{$p_data['hours']}}</td>
                                </tr>
                              @endforeach
                              </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12">
                            <p class="text-left">
                                Of the ones "Completed" = <a href="/">{{$job_totals['complete_total']}}</a><br />
                                How many have been invoiced = <a href="/">{{$job_totals['jobs_invoiced']}}</a><br />
                                How many have not been invoiced = <a href="/">{{$job_totals['jobs_not_invoiced']}}</a>
                            </p>
                        </div>
                    </div>
                </div>
                
                
                
            </section>
            
        </div>
        <div class="col-lg-6">
        	<section class="panel">
            	<header class="panel-heading">General Service Jobs Report</header>
                <div class="panel-body">
                	
					<div id="multibar-graph" class="graph" style="width: 100%; height: 345px;"></div>
					@if(isset($plotData) && !empty($plotData))
						<script type="text/javascript">
							drawGraph("multibar-graph", {{$plotData}}, "General Service Jobs Report", "Count", "Amount", "#6883a3", "multiBar");
						</script>
					@endif
                </div>
            </section>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12" style="display:none;" id="contract-summarytable">
        	
        	<section class="panel">
            	<header class="panel-heading"> Contracts Summary
                	<span style="float: right">
                    <a href="javascript:;" onclick="$('#contract-barchart').slideDown();$('#contract-summarytable').slideUp();">Graphical View</a>
                    </span>
                </header>
                <table class="display table-hover table table-bordered table-striped" id="contracts-table">
                <thead>
                <tr>
                    <th>Salesperson</th>
                    <th style="text-align: center;">Count</th>
                    <th style="text-align: center;">Amount</th>
                </tr>
                </thead>
                @if(!empty($contract_table))
                <tbody>
                @foreach($contract_table as $c_row)
                  <tr>
                  	<td style="text-align: left;">{{$c_row['name']}}</td>
                    <td>{{$c_row['count']}}</td>
                    <td>{{$c_row['f_sum']}}</td>
                  </tr>
                @endforeach
                </tbody>
                @endif
                </table>
            </section>
        </div>
        <div class="col-lg-12" id="contract-barchart">
        	
        	<section class="panel">
            	<header class="panel-heading"> Contracts Summary
                	<span style="float: right">
                    <a href="javascript:;" onclick="$('#contract-summarytable').slideDown();$('#contract-barchart').slideUp();">Data View</a>
                    </span>
                </header>
                <div class="panel-body">
                <div id="contracts-bar" class="graph" style="width: 100%; height: 455px;"></div>
                @if(!empty($contract_table))
                    <script type="text/javascript">
                        drawGraph("contracts-bar", {{$contract_chartdata; }}, "Contracts Summary", "Counting", "Amounts", "#428bca", "bar");
                    </script>
                @endif
                </div>
            </section>
        </div>
        
	</div>
     <!-- /////////////////////////////////////////////////////////// -->
<div class="row">
    <div class="col-sm-12" style="display: none" id="shop_work_open_quotes">
        <section class="panel">
            <header class="panel-heading">
                Shop Work Open Quotes
                <span style="float: right"><a href="javascript:;" onclick="$('#shop_work_open_quotes_g').slideDown();$('#shop_work_open_quotes').slideUp();">Graphical View</a></span>
            </header>
           <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Salesperson</th>
                    <th style="text-align: center;">Quote Count</th>
                    <th style="text-align: center;">Quoted Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php
                $totalCount = 0;
                $totalAmount = 0;
                $shopWorkOpenQuotes = json_decode($shop_work_open_quotes);
                    foreach ($shopWorkOpenQuotes as $key => $value) {
                        $totalCount += $value->count;
                        $totalAmount += $value->price;
                    ?>
                    <tr>
                        <td style="text-align: left;"><?php echo $value->name; ?></td>
                        <td class="center"><?php echo $value->count;?></td>
                        <td class="center"><?php echo $_DefaultCurrency.number_format($value->price,2); ?></td>
                    </tr>
                <?php 
                    } 
                ?>
                </tbody>
            </table>
        </section>
    </div>
    <div class="col-lg-12" id="shop_work_open_quotes_g">
        <section class="panel">
            <header class="panel-heading">
                Shop Work Open Quotes
                <span style="float: right"><a href="javascript:;" onclick="$('#shop_work_open_quotes').slideDown();$('#shop_work_open_quotes_g').slideUp();">Data View</a></span>
            </header>
            <div class="panel-body">
                <div id="shop-work-open-quote" class="graph" style="width: 100%; height: 200px;"></div>
                <?php if(isset($shop_work_open_quotes) && !empty($shop_work_open_quotes)){  ?>
                    <script type="text/javascript">
                        drawGraph("shop-work-open-quote", <?php echo $shop_work_open_quotes; ?>, "Shop Work Open Quotes", "Quote Count", "Quoted Amount","#2f96b4", "bar");
                    </script>
                <?php } ?>
            </div>
        </section>
    </div>
</div>
<!-- third Row-->
<div class="row">
    <div class="col-sm-12" id="electrical_open_quotes" style="display: none">
        <section class="panel">
            <header class="panel-heading">
                Electrical Open Quotes
                <span style="float: right"><a href="javascript:;" onclick="$('#electrical_open_quotes_g').slideDown();$('#electrical_open_quotes').slideUp();">Graphical View</a></span>
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Salesperson</th>
                    <th style="text-align: center;">Quote Count</th>
                    <th style="text-align: center;">Quoted Amount</th>
                </tr>
                </thead>
                <tbody>
                 <?php
                $totalCount = 0;
                $totalAmount = 0;
                $ElectricalOpenQuotes = json_decode($electrical_open_quotes);
                    foreach ($ElectricalOpenQuotes as $key => $value) {
                        $totalCount += $value->count;
                        $totalAmount += $value->price;
                    ?>
                    <tr>
                        <td style="text-align: left;"><?php echo $value->name; ?></td>
                        <td class="center"><?php echo $value->count;?></td>
                        <td class="center"><?php echo $_DefaultCurrency.number_format($value->price,2); ?></td>
                    </tr>
                <?php 
                    } 
                ?>
                </tbody>
            </table>
        </section>
    </div>
    <div class="col-lg-12" id="electrical_open_quotes_g">
        <section class="panel">
            <header class="panel-heading">
                Electrical Open Quotes
                <span style="float: right"><a href="javascript:;" onclick="$('#electrical_open_quotes').slideDown();$('#electrical_open_quotes_g').slideUp();">Data View</a></span>
            </header>
            <div class="panel-body">
                <div id="electrical-open-quotes" class="graph" style="width: 100%; height: 350px;"></div>
                <?php if(isset($electrical_open_quotes) && !empty($electrical_open_quotes)){  ?>
                    <script type="text/javascript">
                        drawGraph("electrical-open-quotes", <?php echo $electrical_open_quotes; ?>, "Electrical Open Quotes", "Quote Count", "Quoted Amount","#7a43b6", "bar");
                    </script>
                <?php } ?>
            </div>
        </section>
    </div>
</div>
<!-- fourth Row-->
<div class="row">
    <div class="col-sm-12" style="display: none" id="field_service_woq">
        <section class="panel">
            <header class="panel-heading">
                Field Service Work Open Quotes
                <span style="float: right"><a href="javascript:;" onclick="$('#field_service_woq_g').slideDown();$('#field_service_woq').slideUp();">Graphical View</a></span>
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Salesperson</th>
                    <th style="text-align: center;">Quote Count</th>
                    <th style="text-align: center;">Quoted Amount</th>
                </tr>
                </thead>
                <tbody>
               <?php
                $totalCount = 0;
                $totalAmount = 0;
                $FieldServiceWorkOpenQuotes = json_decode($field_service_work_open_quotes);
                    foreach ($FieldServiceWorkOpenQuotes as $key => $value) {
                        $totalCount += $value->count;
                        $totalAmount += $value->price;
                    ?>
                    <tr>
                        <td style="text-align: left;"><?php echo $value->name; ?></td>
                        <td class="center"><?php echo $value->count;?></td>
                        <td class="center"><?php echo $_DefaultCurrency.number_format($value->price,2); ?></td>
                    </tr>
                <?php 
                    } 
                ?>
                </tbody>
            </table>
        </section>
    </div>
    <div class="col-lg-12" id="field_service_woq_g">
        <section class="panel">
            <header class="panel-heading">
                Field Service Work Open Quotes
                <span style="float: right"><a href="javascript:;" onclick="$('#field_service_woq').slideDown();$('#field_service_woq_g').slideUp();">Data View</a></span>
            </header>
            <div class="panel-body">
                <div id="field-service-work-open-quotes" class="graph" style="width: 100%; height: 310px;"></div>
                <?php if(isset($field_service_work_open_quotes) && !empty($field_service_work_open_quotes)){  ?>
                    <script type="text/javascript">
                        drawGraph("field-service-work-open-quotes", <?php echo $field_service_work_open_quotes; ?>, "Field Service Work Open Quotes", "Quote Count", "Quoted Amount","#CD0D74", "bar");
                    </script>
                <?php } ?>
            </div>
        </section>
    </div>
</div>
<!-- fourth Row-->
<div class="row">
    <div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
                Grassivy Jobs Listing Filters
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Filter Type</th>
                    <th style="text-align: center;">Job Count</th>
                    <th style="text-align: center;">Contract Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                $GrassivyJobsListingFilters = json_decode($grassivy_jobs_listing_filters);
                    foreach ($GrassivyJobsListingFilters as $key => $value) {?>
                        <tr>
                            <td style="text-align: left;"><?php echo $value->label; ?></td>
                            <td style="text-align: center;"><?php echo $value->value; ?></td>
                            <td style="text-align: center;"><?php echo $_DefaultCurrency.$value->amount; ?></td>
                        </tr>

                <?php    }
                ?>
             </tbody>
            </table>
            <div class="panel-body">
                <div id="lb_Grassivy-Jobs-Listing-Filters" class="col-lg-12 pie-title"></div>
                <div id="qt_Grassivy-Jobs-Listing-Filters" class="col-lg-2 pie-left"></div>
                <div class="col-lg-8">
                    <div id="Grassivy-Jobs-Listing-Filters" class="graph" style="width: 100%; min-height: 300px;"></div>
                </div>
                <div id="amt_Grassivy-Jobs-Listing-Filters" class="col-lg-2 pie-right"></div>
                <?php if(isset($grassivy_jobs_listing_filters) && !empty($grassivy_jobs_listing_filters)){  ?>
                    <script type="text/javascript">
                        drawGraph("Grassivy-Jobs-Listing-Filters", <?php echo $grassivy_jobs_listing_filters; ?>, "Field Service Work Open Quotes", "Job Count", "Contract Amount","grey", "donut");
                    </script>
                <?php } ?>
            </div>
        </section>
         <section class="panel">
            <header class="panel-heading">
                Shop Work Jobs Listing Filters
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Filter Type</th>
                    <th style="text-align: center;">Job Count</th>
                    <th style="text-align: center;">Contract Amount</th>
                </tr>
                </thead>
                <tbody>
                 <?php 
                    $ShopWorkJobsListingFilters = json_decode($shop_work_jobs_listing_filters);
                        foreach ($ShopWorkJobsListingFilters as $key => $value) {?>
                            <tr>
                                <td style="text-align: left;"><?php echo $value->label; ?></td>
                                <td class="center"><?php echo $value->value; ?></td>
                                <td class="center"><?php echo $_DefaultCurrency.$value->amount; ?></td>
                            </tr>

                    <?php    }
                ?>
                </tbody>
            </table>

        </section>
           <section class="panel">
            <header class="panel-heading">
                Special Project Jobs Listing Filters
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Filter Type</th>
                    <th style="text-align: center;">Job Count</th>
                    <th style="text-align: center;">Contract Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                $SpecialProjectJobsListingFilters = json_decode($special_project_jobs_listing_filters);
                $cnt_total =0;
                $amt_total = 0;
                    foreach ($SpecialProjectJobsListingFilters as $key => $value) {?>
                        <tr>
                            <td style="text-align: left;"><?php echo $value->label; ?></td>
                            <td class="center"><?php echo $value->value; ?></td>
                            <td class="center"><?php echo $_DefaultCurrency.$value->amount; ?></td>
                        </tr>

                <?php    
                     $cnt_total += $value->value;
                     $amt_total += $value->amount;
                }
                ?>
                <tr>
                    <td class="right">Total</td>
                    <td class="center"><?php echo $cnt_total; ?></td>
                    <td class="center"><?php echo number_format($amt_total,2); ?></td>
                </tr>
                </tbody>
            </table>

        </section>
         <section class="panel">
            <header class="panel-heading">
                FSW Report Filters
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Filter Type</th>
                    <th style="text-align: center;">Job Count</th>
                    <th style="text-align: center;">Quoted Amount</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $FSWReportFilters = json_decode($fsw_report_filters);
                        foreach ($FSWReportFilters as $key => $value) {?>
                            <tr>
                                <td style="text-align: left;"><?php echo $value->label; ?></td>
                                <td class="center"><?php echo $value->value; ?></td>
                                <td class="center"><?php echo $_DefaultCurrency.$value->amount; ?></td>
                            </tr>

                    <?php    }
                ?>
                </tbody>
            </table>
        </section>
         <section class="panel">
            <header class="panel-heading">
                TC Report Filters
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Filter Type</th>
                    <th style="text-align: center;">Job Count</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $TCReportFilters = json_decode($tc_report_filters);
                        foreach ($TCReportFilters as $key => $value) {?>
                            <tr>
                                <td style="text-align: left;"><?php echo $value->label; ?></td>
                                <td class="center"><?php echo $_DefaultCurrency.$value->amount; ?></td>
                            </tr>

                    <?php    }
                ?>
                </tbody>
            </table>
        </section>
         <section class="panel">
            <header class="panel-heading">
                Service Job Recommendation Categories
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Category</th>
                    <th style="text-align: center;">Recommendation Count</th>
                </tr>
                </thead>
                <tbody>
                <?php 
                    $service_job_recommendation_categories = json_decode($service_job_recommendation_categories);
                        foreach ($service_job_recommendation_categories as $key => $value) {?>
                            <tr>
                                <td style="text-align: left;"><?php echo $value->cat_label; ?></td>
                                <td class="center"><?php echo $value->total; ?></td>
                            </tr>

                    <?php    }
                ?>
                </tbody>
            </table>
        </section> 
          <section class="panel">
            <header class="panel-heading">
                Service Job Employee Recommendations
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Employee</th>
                    <th style="text-align: center;">Recommendation Count</th>
                </tr>
                </thead>
                <tbody>
                 <?php 
                    $service_job_employee_recommendations = json_decode($service_job_employee_recommendations);
                        foreach ($service_job_employee_recommendations as $key => $value) {?>
                            <tr>
                                <td style="text-align: left;"><?php echo $value->name; ?></td>
                                <td class="center"><?php echo $value->assigned; ?></td>
                            </tr>

                    <?php    }
                ?>
                </tbody>
            </table>

        </section> 
        </div>
        <div class="col-sm-6">
        <section class="panel">
            <header class="panel-heading">
                Electrical Jobs Listing Filters
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Filter Type</th>
                    <th style="text-align: center;">Job Count</th>
                    <th cstyle="text-align: center;">Contract Amount</th>
                </tr>
                </thead>
                <tbody>
                     <?php 
                $ElectricalJobsListingFilters = json_decode($electrical_jobs_listing_filters);
                    foreach ($ElectricalJobsListingFilters as $key => $value) {?>
                        <tr>
                            <td style="text-align: left;"><?php echo $value->label; ?></td>
                            <td class="center"><?php echo $value->value; ?></td>
                            <td class="center"><?php echo $_DefaultCurrency.$value->amount; ?></td>
                        </tr>

                <?php    }
                ?>
                </tbody>
            </table>
            <div class="panel-body">
                <div id="lb_Electrical-Jobs-Listing-Filters" class="col-lg-12 pie-title"></div>
                <div id="qt_Electrical-Jobs-Listing-Filters" class="col-lg-2 pie-left"></div>
                <div class="col-lg-8">
                    <div id="Electrical-Jobs-Listing-Filters" class="graph" style="width: 100%; min-height: 300px;"></div>
                </div>
                <div id="amt_Electrical-Jobs-Listing-Filters" class="col-lg-2 pie-right"></div>
                <?php if(isset($electrical_jobs_listing_filters) && !empty($electrical_jobs_listing_filters)){  ?>
                    <script type="text/javascript">
                        drawGraph("Electrical-Jobs-Listing-Filters", <?php echo $electrical_jobs_listing_filters; ?>, "Field Service Work Open Quotes", "Job Count", "Contract Amount","grey", "donut");
                    </script>
                <?php } ?>
            </div>
        </section>
         <section class="panel">
            <header class="panel-heading">
                Service Jobs Listing Filters
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Filter Type</th>
                    <th style="text-align: center;">Job Count</th>
                    <th style="text-align: center;">Contract Amount</th>
                </tr>
                </thead>
                <tbody>
                    <?php   
                    $ServiceJobsListingFilters = json_decode($service_jobs_listing_filters);
                    foreach ($ServiceJobsListingFilters as $key => $value) {?>
                        <tr>
                            <td style="text-align: left;"><?php echo $value->label; ?></td>
                            <td class="center"><?php echo $value->value; ?></td>
                            <td class="center"><?php echo $_DefaultCurrency.$value->amount; ?></td>
                        </tr>

                <?php    }
                ?>
                </tbody>
            </table>
            <div class="panel-body">
                <div id="lb_Service-Jobs-Listing-Filters" class="col-lg-12 pie-title"></div>
                <div id="qt_Service-Jobs-Listing-Filters" class="col-lg-2 pie-left"></div>
                <div class="col-lg-8">
                    <div id="Service-Jobs-Listing-Filters" class="graph" style="width: 100%; min-height: 300px;"></div>
                </div>
                <div id="amt_Service-Jobs-Listing-Filters" class="col-lg-2 pie-right"></div>
                <?php if(isset($service_jobs_listing_filters) && !empty($service_jobs_listing_filters)){  ?>
                    <script type="text/javascript">
                        drawGraph("Service-Jobs-Listing-Filters", <?php echo $service_jobs_listing_filters; ?>, "Field Service Work Open Quotes", "Job Count", "Contract Amount","grey", "donut");
                    </script>
                <?php } ?>
            </div>
        </section> 
         <section class="panel">
            <header class="panel-heading">
                Field Service Work Filters
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Filter Type</th>
                    <th style="text-align: center;">Job Count</th>
                    <th style="text-align: center;">Quoted Amount</th>
                </tr>
                </thead>
                <tbody>
                    <?php 
                     $field_service_work_filters = json_decode($field_service_work_filters);
                    foreach ($field_service_work_filters as $key => $value) {?>
                        <tr>
                            <td style="text-align: left;"><?php echo $value->label; ?></td>
                            <td class="center"><?php echo $value->value; ?></td>
                            <td class="center"><?php echo $_DefaultCurrency.$value->amount; ?></td>
                        </tr>

                <?php    }
                ?>
                </tbody>
            </table>
        </section>  
         <section class="panel">
            <header class="panel-heading">
                Rental Invoice Alert
            </header>
            <table class="display table-hover table table-bordered table-striped" id="example">
                <thead>
                <tr>
                    <th>Job Number</th>
                    <th style="text-align: center;">Schedule Date</th>
                </tr>
                </thead>
                <tbody>
                   <?php 
                     $rental_invoice_alert = json_decode($rental_invoice_alert);
                    foreach ($rental_invoice_alert as $key => $value) {?>
                        <tr>
                             <td style="text-align: left;"><?php echo $value->num; ?></td>
                            <td class="center"><?php echo date('m/d/Y',strtotime($value->date)); ?></td>
                        </tr>

                <?php    }
                ?> 
                </tbody>
            </table>
        </section> 
    </div>    
</div>   
@stop