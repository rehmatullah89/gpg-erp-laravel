@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<style>
body .modal {
    /* new custom width */
    width: 100%;
}
</style>
              <!-- page start-->
          <div class="row">
            <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">    
                OVER HEAD BUDGETING REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i> Group By</i></b>
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
                                  <td data-title="Start Date:" style="width:12.5%;">
                                    {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td>
                                  <td data-title="End Date:" style="width:12.5%;">
                                    {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} 
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>
                                  <td data-title="Group By:" style="width:12.5%;" id="sort_group_by">
                                    {{Form::label('groupBy', 'Group By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::select('groupBy',array(''=>'ALL')+$arr_tags_names,'', array('class' => 'form-control', 'id' => 'groupBy')) }}
                                  </td>
                                  <td data-title="View By:" style="width:12.5%;">
                                    {{Form::label('view', 'View By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight:bold;'))}} 
                                    {{ Form::select('view',array('default_view'=>'Default View','custom_view'=>'Customized View'),'default_view', array('class' => 'form-control', 'id' => 'view')) }}
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
              <?php $CView = Input::get("view");?>
            @if ($CView == 'custom_view')
            <table class="table table-bordered table-striped table-condensed cf" >
            <tbody>
              <tr>
                <td>
                  <select id="act" name="act" class="form-control">
                    <option value="over_head_budgeting_report_simple_export">Simple Parent Totals</option>
                    <option value="over_head_budgeting_report_parent_child_export">Parent Child Export</option>
                    <option value="company_modeling">Company Modeling Report</option>      
                  </select>
                </td>
                <td>{{Form::button('Generate Excel Report', array('class' => 'btn btn-success', 'id'=>'generate_report2'))}} </td>
              </tr>
            </tbody>
            </table>
              <table class="table table-bordered table-striped table-condensed cf" >
                <tbody class="cf">
                <?php
                  $grand_debit = 0;
                  $grand_credit = 0;
                  $grand_amount = 0;
                  $tag_id = -1;
                  $started = 0;
                  $sub = 0;
                  $tag_total_debit = 0;
                  $tag_total_credit = 0;
                  $tag_total_amount = 0;
                  $sub_total_debit = 0;
                  ksort($tags_arr2);
                  foreach($tags_arr2 as $k => $v)
                  foreach($v as $k1 => $v1){
                    if(isset($arr_final_totals[$v1]))
                    {
                      $key = $v1;
                      $val = $arr_final_totals[$v1];
                      if(strpos(@$val['gl_tags'],'.'!==false))
                        list($compare_gl_parent,$compare_list_child) = explode(".",$val['gl_tags']);
                      else{
                        $compare_gl_parent = @$val['gl_tags'];
                        $compare_list_child = 0;
                      }
                      $title_type_qry = DB::select(DB::raw("SELECT CONCAT(expense_gl_code,' - ',description) as title, exclude_from_oh, (SELECT type FROM gpg_expense_gl_type WHERE id = gpg_expense_gl_code.gpg_expense_gl_type_id) as type FROM gpg_expense_gl_code WHERE id = '".$key."'"));
                      $title_type = array();
                      foreach ($title_type_qry as $key1 => $value1) {
                        $title_type = (array)$value1;
                      }
                      if($compare_gl_parent != $tag_id){
                        if($started==1){
                          echo '<tr height="30px">
                            <td align="right" style="margin-right:16px;font-size:14px" bgcolor="#EEEEEE"><strong>'.($arr_tags_names[$show_tag_id]).'&nbsp;&nbsp;</strong></td>
                            <td bgcolor="#FFFFCC" align="right"><span style="color:#999999;float:left;margin-left:5px">Debit</span><strong>'.'$'.number_format($tag_total_debit,2).'</strong></td>
                            <td bgcolor="#FFFFCC" align="right"><span style="color:#999999;float:left;margin-left:5px">Credit</span><strong>'.'$'.number_format($tag_total_credit,2).'</strong></td>
                            <td bgcolor="#FFC1C1" align="right"><span style="color:#999999;float:left;margin-left:5px">Amount</span><strong>'.'$'.number_format($tag_total_amount,2).'</strong></td>
                            </tr>';
                        $tag_total_debit = 0;
                        $tag_total_credit = 0;
                        $tag_total_amount = 0;
                       }
                       $started = 1;
                       ?>
                       <tr>
                           <?php 
                           $tag_id = $compare_gl_parent;
                           if(preg_match('/./',$tag_id)){
                              $sub = 1;
                              $tag_id_levels = explode(".",$tag_id);
                              $show_tag_id = $tag_id_levels[0];
                            }
                            else
                            $show_tag_id = $tag_id;
                    }// end if($compare_gl_parent != $tag_id)
                    $grand_debit  += $val['debit_total'];
                    $grand_credit += $val['credit_total'];
                    $grand_amount += $val['amount_total'];
                    $tag_total_debit += $val['debit_total'];
                    $tag_total_credit += $val['credit_total'];
                    $tag_total_amount += $val['amount_total'];
                   ?>
                          <td><a href="#myModal" id="{{$key}}" name="edit_gl_code" data-toggle="modal">{{Form::button('<i class="fa fa-anchor"></i>', array('class' => 'btn btn-primary btn-xs'))}}</a>
                          <a href="#myModal2" id="{{$key}}" name="update_gl_code_data" data-toggle="modal">{{Form::button('<i class="fa fa-edit"></i>', array('class' => 'btn btn-success btn-xs'))}}</a>
                          </td>
                          <td><strong><?php echo $title_type['title']." ".$key?></strong><span style="color:#999999;float:right;margin-left:5px"><?php echo ($title_type['exclude_from_oh']?"Excluded":"")?>&nbsp;&nbsp;</span></td>
                          <?php 
                          $name_tag = "";
                          if(preg_match('/./',@$val['gl_tags'])){
                            $names_tags = explode(".",$val['gl_tags']);
                            foreach($names_tags as $tag_index => $tag_val){
                                $name_tag .= "&nbsp;&nbsp;".$arr_tags_names[$tag_val]."<br />" ;
                            }
                          }
                          ?>
                          <td>{{$name_tag}}</td>
                          <td>&nbsp;&nbsp;<strong>{{$title_type['type']?$title_type['type']:"-"}}</strong></td>
                          <td bgcolor="#FFFFCC" align="right"><span style="color:#999999;float:left;margin-left:5px">Debit</span>&nbsp;&nbsp;<strong>{{'$'.number_format($val['debit_total'],2)}}</strong></td>
                          <td bgcolor="#FFFFCC" align="right"><span style="color:#999999;float:left;margin-left:5px">Credit</span>&nbsp;&nbsp;<strong>{{'$'.number_format($val['credit_total'],2)}}</strong></td>
                          <td bgcolor="#FFC1C1" align="right"><span style="color:#999999;float:left;margin-left:5px">Amount</span>&nbsp;&nbsp;<strong>{{'$'.number_format($val['amount_total'],2)}}</strong></td>
                         </tr>
                         <?php  }
                          }?>
                </tbody>
              </table>  
            @else  
            <table class="table table-bordered table-striped table-condensed cf" >
            <tbody>
              <tr>
                <td>
                  <select id="export_type"  class="form-control">
                    <option value="">-Choose Excel Report-</option>
                    <option value="gl_detail_report">GL Detail Report</option>
                    <option value="gl_detail_with_account_no">GL Detail With Line Item Account Number Report</option>
                  </select>
                </td>
                <td>{{Form::button('Generate Excel Report', array('class' => 'btn btn-success', 'id'=>'generate_report1'))}} </td>
              </tr>
            </tbody>
            </table>
            @if($tagging == 0)
            <?php
                $tag_title = "";
                $tag_group_totals = array();
                $parent_tag_totals = array();
                $child_tag_totals = array();
            ?>
             <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
                <tr>
                  <th>Date</th>
                  <th>Modified On</th>
                  <th>Last Modified By</th>
                  <th>Type</th>
                  <th>Num</th>
                  <th>Name</th>
                  <th>Source Name</th>
                  <th>Memo</th>
                  <th>Class</th>
                  <th>Tags</th>
                  <th>Clr</th>
                  <th>Debit</th>
                  <th>Credit</th>
                  <th>Amount</th>
               </tr>
              <tbody class="cf">
              <?php
                foreach ($query_data as $row=>$val) {
                  $debit        = 0;
                  $credit       = 0;
                  $amount     = 0;
                  $debit_total  = 0;
                  $credit_total = 0;
                  $amount_total = 0;
              ?>
                <tr  bgcolor="#EEEEEE">
                  <td height="25" align="left" colspan="15"><strong> <?php 
                  if(is_numeric($row)) {
                    echo $arr_heads_name[$row][0];
                  } else {
                    echo $row;
                  }
                ?></strong></td>
                </tr><?php 
                  for ($i=0 ; $i < count($val[$groupBy_new]) ; $i++) {
                    $debit        = $val['debit'][$i];
                    $credit       = $val['credit'][$i];
                    $amount       = $debit - $credit;
                    $debit_total  = $debit + $debit_total;
                    $credit_total = $credit + $credit_total;
                    $amount_total = $amount + $amount_total;
                  ?>
                <tr height="30">
                  <td  align="left" bgcolor="#FFFFFF"><?php echo date('m/d/Y',strtotime($val['date'][$i])) ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo strlen($val['last_modified_on'][$i])>0?date('m/d/Y',strtotime($val['last_modified_on'][$i])):"-" ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $val['modified_by'][$i] ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $val['type'][$i] ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $val['num'][$i] ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $val['name'][$i] ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $val['source_name'][$i] ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $val['memo'][$i] ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $val['class'][$i] ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $tag_title ?></td>
                  <td  align="left" bgcolor="#FFFFFF"><?php echo $val['clr'][$i] ?></td>
                  <td  align="left" bgcolor="#FFFFCC"><?php echo '$'.number_format($debit,2)?></td>
                  <td  align="left" bgcolor="#FFFFCC"><?php echo '$'.number_format($credit,2)?></td>
                  <td  align="left" bgcolor="#ffc1c1"><?php echo '$'.number_format($amount,2)?></td>
                </tr>
                <?php  } ?>
                <tr height="30">
                  <td colspan="11"  align="left" bgcolor="#FFFFFF">&nbsp;</td>
                  <td  align="left" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($debit_total,2)?></strong></td>
                  <td  align="left" bgcolor="#FFFFCC"><strong><?php echo '$'.number_format($credit_total,2)?></strong></td>
                  <td  align="left" bgcolor="#ffc1c1"><strong><?php echo '$'.number_format($amount_total,2)?></strong></td>
                </tr>
                 <?php }?>
              </tbody>
          @endif
          @endif
          </table>
               {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
              <br/>
              {{ HTML::link("qc_reports/glDetailReportExport?".http_build_query(array_filter(Input::except('_token', 'page'))), '' , array('class'=>'btn btn-success','id'=>'link1'))}}
              {{ HTML::link("qc_reports/glDetailAccountNosExport?".http_build_query(array_filter(Input::except('_token', 'page'))), '' , array('class'=>'btn btn-success','id'=>'link2'))}}
              {{ HTML::link("qc_reports/ohBudgetingReportSimpleExport?".http_build_query(array_filter(Input::except('_token', 'page'))), '' , array('class'=>'btn btn-success','id'=>'link3'))}}
              {{ HTML::link("qc_reports/ohBudgetingReportParentChildExport?".http_build_query(array_filter(Input::except('_token', 'page'))), '' , array('class'=>'btn btn-success','id'=>'link4'))}}
              {{ HTML::link("qc_reports/companyModelingExport?".http_build_query(array_filter(Input::except('_token', 'page'))), '' , array('class'=>'btn btn-success','id'=>'link5'))}}
            </section>
           </div>
        </div>      
      </div>
      <!-- Modal -->
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog"  style="width:900px;">
            <div class="modal-content" >
              <div class="modal-header">
              {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
               <h4 class="modal-title">OVER HEAD BUDGETING REPORT DETAILS</h4>
              </div>
              <div class="modal-body">
                 <section id="flip-scroll" >
                  <table class="table table-bordered table-striped table-condensed cf" >
                    <thead class="cf">
                      <th>Date</th>
                      <th>Last modified on</th>
                      <th>Modified by</th>
                      <th>Type</th>
                      <th>Num</th>
                      <th>Name</th>
                      <th>Source name</th>
                      <th>Memo</th>
                      <th>Class</th>
                      <th>Clr</th>
                      <th>Split</th>
                      <th>Debit</th>
                      <th>Credit</th>
                      <th>Amount</th>
                    </thead>
                    <tbody class="cf" id="ohb_data">

                    </tbody>
                  </table>
                  </section>
              </div>
              <div class="btn-group" style="padding:20px;">
                {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
              </div>
            </div>
          </div>
        </div>
      <!-- modal -->
      <!-- Modal#2 -->
        <div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
          <div class="modal-dialog"  style="width:900px;">
            <div class="modal-content" >
              <div class="modal-header">
              {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
               <h4 class="modal-title">Eidt GL-Code</h4>
              </div>
              <div class="modal-body">
                 <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" >
                    <thead class="cf">
                      <th style="width:16.6%;">Select Parent Expense Gl-Code</th>
                      <th style="width:16.6%;">Expense Gl-Code Type</th>
                      <th style="width:16.6%;">Expense Gl-Code</th>
                      <th style="width:16.6%;">Description</th>
                      <th style="width:16.6%;">Status</th>
                      <th style="width:16.6%;">Exclude form OH Calculation</th>
                    </thead>
                    <tbody class="cf">
                        <tr>
                          <td>{{ Form::select('_parent_id',$parent_arr,'', array('class' => 'form-control', 'id' => '_parent_id')) }}</td>
                          <td>{{ Form::select('_gpg_expense_gl_type_id',$gpg_expense_gl_type,'', array('class' => 'form-control', 'id' => '_gpg_expense_gl_type_id')) }}</td>
                          <td>{{ Form::text('_expense_gl_code','', array('class' => 'form-control', 'id' => '_expense_gl_code')) }}</td>
                          <td>{{ Form::text('_description','', array('class' => 'form-control', 'id' => '_description')) }}</td>
                          <td>{{ Form::select('_status',array('A'=>'Active','B'=>'Blocked'),'', array('class' => 'form-control', 'id' => '_status')) }}</td>
                          <td><input type="checkbox" checked="checked" value="" id="_exclude_from_oh" name="_exclude_from_oh"></td>
                          <input type="hidden" id="glCodeId" value="">
                        </tr>
                    </tbody>
                  </table>
                  </section>
              </div>
              <div class="btn-group" style="padding:20px;">
                {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                {{Form::button('Update', array('class' => 'btn btn-success','id'=>'update_gl_codes','data-dismiss'=>'modal'))}}
              </div>
            </div>
          </div>
        </div>
      <!-- modal #2 end-->
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
          $('#view').val("default_view");
          $('#groupBy').val("");
      });
    
      $( document ).ready(function() {
        if($('#view option:selected').val() == 'default_view'){
          $('#sort_group_by').html('Group By: <select name="groupBy" id="groupBy" class="form-control"><option selected="selected" value="">ALL</option><option value="gpg_expense_gl_code_id">Account</option><option value="source_name">Source Name</option><option value="class">Class</option><option value="tags_91801">Class</option><option value="tags_91802">Sub Class</option><option value="tags_91745">Acct Type 3</option><option value="tags_91744">Acct Type 2</option><option value="tags_91632">Acct Type</option></select>');
        }
        else if($('#view option:selected').val() == 'custom_view'){
          $('#sort_group_by').html('Sort By: <select id="orderBy" class="form-control" name="orderBy"><option value="">-</option><option value="class">Class</option><option value="date">Date</option><option value="name">Name</option><option value="source_name">Source Name</option><option value="type">Type</option></select>');   
        }
      });
      $('a[name=edit_gl_code]').click(function(){
        var id = $(this).attr('id');
        var SDate = $('#SDate').val();
        var EDate = $('#EDate').val();
        var orderBy = $('#orderBy').val();
         $.ajax({
            url: "{{URL('ajax/getOHBudgetInfo')}}",
              data: {
               'id' : id,
               'SDate' : SDate,
               'EDate' : EDate,
               'orderBy' : orderBy
              },
            success: function (data) {
              $('#ohb_data').html(data);
            },
        });
      });
      $('a[name=update_gl_code_data]').click(function(){
          var id = $(this).attr('id');
          $('#glCodeId').val(id);
          $.ajax({
            url: "{{URL('ajax/getGLCodeData')}}",
              data: {
               'id' : id
              },
            success: function (data) {
              $('#_parent_id').val(data.parent_id);
              $('#_gpg_expense_gl_type_id').val(data.gpg_expense_gl_type_id);
              $('#_expense_gl_code').val(data.expense_gl_code);
              $('#_description').val(data.description);
              $('#_status').val(data.status);
            },
        });
      });
      $('#update_gl_codes').click(function(){
          var id = $('#glCodeId').val();
          var parent_id = $('#_parent_id').val();
          var gpg_expense_gl_type_id= $('#_gpg_expense_gl_type_id').val();
          var expense_gl_code= $('#_expense_gl_code').val();
          var description= $('#_description').val();
          var status= $('#_status').val();
          $.ajax({
            url: "{{URL('ajax/updateGLCodeData')}}",
              data: {
               'id' : id,
               'parent_id' : parent_id,
               'gpg_expense_gl_type_id' : gpg_expense_gl_type_id,
               'expense_gl_code' : expense_gl_code,
               'description' : description,
               'status' : status
              },
            success: function (data) {
              alert('Updated Successfully');
              location.reload();
            },
        });
      });
    $('#generate_report1').click(function(){
      var selval = $('#export_type option:selected').val(); // selected value
      if (selval == 'gl_detail_report') {
          $('a#link1')[0].click();
      }else if(selval == 'gl_detail_with_account_no'){
          $('#link2')[0].click();
      }else{
        alert('Please Select a report first!');
      }
    });
    $('#generate_report2').click(function(){
      var selval = $('#act option:selected').val();
      if (selval == 'over_head_budgeting_report_simple_export') {
          $('#link3')[0].click();
      }else if(selval == 'over_head_budgeting_report_parent_child_export'){
          $('#link4')[0].click();
      }else if(selval == 'company_modeling'){
          $('#link5')[0].click();
      }
    });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop