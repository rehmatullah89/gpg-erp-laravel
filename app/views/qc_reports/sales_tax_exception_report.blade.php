@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
               SALES TAX EXCEPTION REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b>  JOB WITH ZERO MATERIAL COST/TAX AMOUNT</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/sales_tax_exception_report'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                    <tbody>
                                    <tr>
                                      <td><b>Select Search Filter: </b></td>
                                      <td>
                                        {{Form::select('optStatus',array('zero_material_amount'=>'Job with Zero Material Cost','zero_tax_amount'=>'Job with Zero Tax Amount'),'', ['id' => 'optStatus', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td>
                                        {{Form::submit('Search', array('class' => 'btn btn-success'))}}
                                      </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <br/>
                                  </section>
                               {{ Form::close() }}
              </section> 
                <section id="no-more-tables" style="padding:10px;">
                  <div><span style="float:left"><b>{{count($query_data)}}</b> jobs found with zero Material cost</span>
                  <span style="float:right"><b>Material Cost Total:</b>{{number_format($Total_mat_cost,2,'.',',')}}&nbsp;&nbsp;&nbsp;&nbsp;<b>Tax Amount Total:{{number_format($Toal_tax,2,'.',',')}}</b></span></div> 
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Serial</th>
                        <th>Job Number</th>
                        <th>Material Cost</th>
                        <th>Tax Amount</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $SrNo = 1;?>
                      @foreach($query_data as $row)
                        <tr>
                          <td height="30">{{$SrNo++}}</td><td>{{ HTML::link('job/commission_list',$row['job_num'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td><td>{{number_format($row['material_cost'],2)}}</td><td>{{number_format($row['tax_amt'],2)}}</td>
                        </tr>
                      @endforeach
                    </tbody>  
                  </table>  
                </section>  
              </section>
              </div>
              </div>
              {{ HTML::link("qc_reports/excelSalesTaxExcpExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
              <br/>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
  <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
  <script src="{{asset('js/common-scripts.js')}}"></script>
@stop