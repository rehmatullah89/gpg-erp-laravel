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
                  COST DUPLICATION REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Dates / Filters</i></b>
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
                 {{ Form::open(array('before' => 'csrf' ,'url'=>route('reports/'.$uriSegment), 'files'=>true, 'method' => 'post')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <?php
                                $SelParam = Input::get("SelParam");
                                if($SelParam=='') {
                                  $SelParam[0]='name';
                                  $SelParam[1]='source_name';
                                  $SelParam[2]='amount';
                                }
                              ?>
                              <tr>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="name" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='name')?"checked":''?>>Name</td>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="source_name" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='source_name')?"checked":''?>>Source Name </td>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="amount" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='amount')?"checked":''?>>Amount </td>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="num" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='num')?"checked":''?>>Num</td>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="date" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='date')?"checked":''?> >Date </td>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="account" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='account')?"checked":''?>>Account</td>
                              </tr>
                              <tr>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="memo" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='memo')?"checked":''?>>Memo</td>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="type" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='type')?"checked":''?>>Type</td>
                                <td><input type="checkbox" name="SelParam[]" id="SelParam" value="split" <? if (isset($SelParam))foreach($SelParam as $chk)echo($chk=='split')?"checked":''?>>Split </td>
                              </tr> 
                              <tr>
                                <td>
                                  {{Form::submit('Cost Duplication Report', array('class' => 'btn btn-info'))}}
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
                  <th>Action</th>
                  <th>Type</th>
                  <th>Date</th>
                  <th>Num</th>
                  <th>Name</th>
                  <th>Source Name</th>
                  <th>Memo</th>
                  <th>Account</th>
                  <th>Split</th>
                  <th>Amount</th>
              </tr>
              </thead>
              <tbody class="cf">
                @foreach($query_data as $gpg_job_cost)
                  @if(!empty($gpg_job_cost))
                  <tr>
                    <td>
                        {{ Form::open(array('method' => 'post','id'=>'myForm'.$gpg_job_cost['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('reports/deleteCostDuplic', $gpg_job_cost['id']))) }}
                        {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$gpg_job_cost['id'].'").submit()')) }}
                        {{ Form::close() }}
                    </td>
                    <td>{{@$gpg_job_cost['type']}}</td>
                    <td>{{@$gpg_job_cost['date']}}</td>
                    <td>{{@$gpg_job_cost['num']}}</td>
                    <td>{{@$gpg_job_cost['name']}}</td>
                    <td>{{@$gpg_job_cost['source_name']}}</td>
                    <td>{{@$gpg_job_cost['memo']}}</td>
                    <td>{{@$gpg_job_cost['account']}}</td>
                    <td>{{@$gpg_job_cost['split']}}</td>
                    <td>{{@$gpg_job_cost['amount']}}</td>
                  </tr>
                  @endif
                @endforeach
              </tbody>
              </table>
              {{ HTML::link("reports/excelCostDuplicReportExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
              <br/>
             {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
            </section>
            </div>  
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
          $('#SDate').val("");
          $('#EDate').val("");
          $('#SchDate1').val("");
          $('#SchEDate1').val("");
          $('#optJobStatus').val("");
      });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop