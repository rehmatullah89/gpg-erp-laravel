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
                  CUSTOMERS JOB DETAIL REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Customer Name</i></b>
                </header>
                  @if (isset($errors) && ($errors->any()))
                              <div class="alert alert-danger">
                                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                                  <h4>Error</h4>
                                     <ul>
                                      {{ implode('', $errors->all('<li class="error">:message</li>')) }}
                                     </ul>
                              </div>
                          @endif
                          @if(@Session::has('success'))
                              <div class="alert alert-success alert-block">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                                 <h4>Success</h4>
                                  <ul>
                                  {{ Session::get('success') }}
                                 </ul>
                              </div>
                          @endif
                 <?php $uriSegment = Request::segment(2);?> 
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Invoice Start Date:" style="width:12.5%;">
                                    {{Form::label('InvoiceSDate', 'Invoice Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('InvoiceSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceSDate')) }}
                                  </td>
                                  <td data-title="Invoice End Date:" style="width:12.5%;">
                                    {{Form::label('InvoiceEDate', 'Invoice End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('InvoiceEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'InvoiceEDate')) }}
                                  </td>
                                  <td data-title="Customer:" style="width:12.5%;">
                                    {{Form::label('optCustomer', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::select('optCustomer',array(''=>'ALL')+$customers,'', array('class' => 'form-control', 'id' => 'optCustomer')) }}
                                  </td>
                                  <td style="width:12.5%;">
                                    {{Form::submit('Submit', array('class' => 'btn btn-info'))}}
                                    {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                  </td>
                                </tr>
                            </tbody>
                          </table>
                     </section>
                    {{ Form::close() }}
              </section>
              </section>
              </div>

            <div class="row">
              <div class="col-sm-12">
              <section class="panel">
              <div class="panel-body">
              <section id="no-more-tables" >
             <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
                <tr>
                  <th>#id</th>
                  <th>Customer Name </th>
                  <th>Electrial</th>
                  <th>Grassivy</th>
                  <th>Special Project</th>
                  <th>Service</th>
                  <th>Rental</th>
                  <th>Shop</th>
                  <th>Sales Total</th>
               </tr>
              <tbody class="cf">
              <?php 
                $colcount=0;
                foreach ($query_data as $key => $val){
                  $gpg_inv_amt = 0;
                  $sh_inv_amt =0;
                  $rnt_inv_amt =0;
                  $ser_inv_amt =0;
                  $jobCount = 0;
                ?>
                <tr  bgcolor="#FFFFFF">
                  <td align="center" ><strong>{{$key}}</strong></td>
                  <td height="30" nowrap="nowrap" >{{$val['name']}}</td>
                  <?php 
                    $str= '';
                    $jobCount =   count(@$val['gpg_job']);
                    if (isset($val['gpg_job']))
                    foreach ($val['gpg_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $gpg_inv_amt = $gpg_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/elec_job_list',($jobCount >=1)?number_format($gpg_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   <?php 
                    $str= '';
                    $gpg_inv_amt = 0;
                    $jobCount =   count(@$val['gpg_grassivy_job']);
                    if (isset($val['gpg_grassivy_job']))
                    foreach ($val['gpg_grassivy_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $gpg_inv_amt = $gpg_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/grassivyJobList',($jobCount >=1)?number_format($gpg_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <?php 
                    $str= '';
                    $gpg_inv_amt = 0;
                    $jobCount =   count(@$val['gpg_special_project_job']);
                    if (isset($val['gpg_special_project_job']))
                    foreach ($val['gpg_special_project_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $gpg_inv_amt = $gpg_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/specialProjectJobList',($jobCount >=1)?number_format($gpg_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   <?php 
                    $str= '';
                    $ser_inv_amt = 0;
                    $jobCount =   count(@$val['service_job']);
                    if (isset($val['service_job']))
                    foreach ($val['service_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $ser_inv_amt = $ser_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/service_job_list',($jobCount >=1)?number_format($ser_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <?php 
                    $str= '';
                    $rnt_inv_amt = 0;
                    $jobCount =   count(@$val['rnt_job']);
                    if (isset($val['rnt_job']))
                    foreach ($val['rnt_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $rnt_inv_amt = $rnt_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('invoice',($jobCount >=1)?number_format($rnt_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                   <?php 
                    $str= '';
                    $sh_inv_amt = 0;
                    $jobCount =   count(@$val['sh_job']);
                    if (isset($val['sh_job']))
                    foreach ($val['sh_job'] as $job_key => $job_val){ 
                      $invData = explode('~~',$job_val);
                      $sh_inv_amt = $sh_inv_amt + $invData[0];
                      $str .= '&#13; Job Num:'.$job_key.'&#13; Invoicev #:'.$invData[1].'&#13; Inv Amt Net:'.'$'.number_format((double)$job_val,2);  
                    }
                  ?>
                  <td align="center" title="{{$str}}">{{ HTML::link('job/shopWorkJobList',($jobCount >=1)?number_format($sh_inv_amt,2):'-', array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                  <td colspan="6"><?php echo '<strong>'.'$'.number_format($sh_inv_amt+$ser_inv_amt+$gpg_inv_amt+$rnt_inv_amt,2).'</strong>'; ?></td>
                </tr>
                <?php }?>
            </tbody>
          </table>
               {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
              <br/>
              {{ HTML::link("qc_reports/excelCustJobDetailRepExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
            </section>
           </div>
        </div>      
      </div>
     
    <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd'
      });  
      $("section[mysection=hide_n_show]").hide();
      $('#togglerButton').click(function(){
         $("section[mysection=hide_n_show]").toggle("slow");
         if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
            $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
         else 
            $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
      }); 
      $('#reset_search_form').click(function(){
          $('#InvoiceSDate').val("");
          $('#InvoiceEDate').val("");
          $('#optCustomer').val("");
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop