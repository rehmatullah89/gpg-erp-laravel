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
                  EMPLOYEE PAYABLE AMOUNT REPORT
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>Employee Name/ Type</i></b>
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
                                  <td>{{Form::label('cus_name', 'Customer:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} </td>
                                  <td data-title="Customer:">
                                    {{Form::select('cus_name',array(''=>'ALL')+$customers,'', array('class' => 'form-control', 'id' => 'cus_name')) }}
                                  </td>
                                  <td>
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
                      <tbody>
                        <?php $colcount=0;?>
                        @foreach($query_data as $row)
                          <?php $colcount++;?>
                          <tr height="40px">
                            <td align="center" ><strong>{{Form::button('<i class="fa fa-plus"></i>', array('class' => 'btn btn-success btn-xs', 'onclick'=>'toggleCustomerInfo('.$colcount.','.((trim($row['gpg_customer_id']) != '')?$row['gpg_customer_id']:0).')'))}}</strong></td>
                            <td bgcolor="#FFFFCC" width="100px"><strong>{{$row['gpg_customer_id']}}</strong></td>
                            <td bgcolor="#FFFFCC"><strong>{{$row['cus_name']==""?"&lt;no name&gt;":$row['cus_name']}}</strong></td>
                            <td bgcolor="#FFFFCC" width="50px">{{$row['tot_contact']}}</td>
                          </tr>
                            <tr id="hideme_{{$colcount}}" bgcolor="#FFFFCC"><td colspan="6">
                              <!-- orgin Start -->
                              <?php
                              if($row['tot_contact']>4)
                              {
                                $info_res = DB::select(DB::raw("SELECT id, status, type_of_sale, (SELECT NAME FROM gpg_employee WHERE id = gpg_employee_id) as emp_name, contact_info FROM gpg_sales_tracking WHERE gpg_customer_id = '".$row['gpg_customer_id']."'"));
                              ?>
                              <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                <thead>                              
                                  <tr height="35px">
                                    <td bgcolor="#F2F2F2"><strong>Lead Id</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Lead Status</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Type of Sale</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Employee</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Contact Info</strong></td>
                                  </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($info_res as $key => $value1) {
                                    $arr_row = (array)$value1;
                                  ?>
                                <tr>
                                    <td bgcolor="#FFF">{{$arr_row['id']}}</td>
                                    <td bgcolor="#FFF">{{$arr_row['status']}}</td>
                                    <td bgcolor="#FFF">{{$arr_row['type_of_sale']}}</td>
                                    <td bgcolor="#FFF">{{$arr_row['emp_name']}}</td>
                                    <td bgcolor="">
                                        <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                        <tr>
                                          <td bgcolor='#FFF'><?php 
                                            if(preg_match("/#@@#/",$arr_row['contact_info'])){
                                              $arr_row['contact_info'] = str_replace("::","</td><td bgcolor='#FFF'>",$arr_row['contact_info']);
                                              $dat = explode("#@@#",$arr_row['contact_info']);
                                              echo implode("</td></tr><tr><td bgcolor='#FFF'>",$dat);
                                            }else{
                                              echo $arr_row['contact_info'];
                                            }
                                            ?>
                                            </td>
                                          </tr>
                                          </table>
                                    </td>
                                </tr>
                              <?php
                                }// end while
                              ?>
                            </tbody>
                            </table>
                            <?php
                            } //endif
                            else{
                              if(preg_match("/~@@@~/",$row['cnt_info'])) {?>
                                <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                  <tr height="35px">
                                    <td bgcolor="#F2F2F2"><strong>Lead Id</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Lead Status</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Type of Sale</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Employee</strong></td>
                                    <td bgcolor="#F2F2F2"><strong>Contact Info</strong></td>
                                  </tr><?php
                                  foreach(explode("~@@@~",$row['cnt_info']) as $value) {
                                    $data = explode("--@--",$value);
                                  ?>
                                  <tr>
                                    <td bgcolor="#FFF"><?php echo str_replace(",","",@$data[0]);?></td>
                                    <td bgcolor="#FFF"><?php echo @$data[1];?></td>
                                    <td bgcolor="#FFF"><?php echo @$data[2];?></td>
                                    <td bgcolor="#FFF"><?php echo @$data[3];?></td>
                                    <td bgcolor="">
                                      <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                      <tr>
                                        <td bgcolor='#FFF'><?php
                                        if(preg_match("/#@@#/",@$data[4])) {
                                          @$data[4] = str_replace("::","</td><td bgcolor='#FFF'>",@$data[4]);
                                          $dat = explode("#@@#",@$data[4]);
                                          echo implode("</td></tr><tr><td bgcolor='#FFF'>",$dat);
                                        }else{
                                          echo @$data[4];
                                        } ?>
                                        </td>
                                      </tr>
                                      </table>
                                    </td>
                                  </tr><?php } ?>
                              </table>
                              <?php }else{ ?>
                              <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                  <tr>
                                  <td bgcolor="#FFF"><?php echo $row['cnt_info']?></td>
                                  </tr>
                              </table>
                              <?php }
                            }?>
                       <!-- orgin End -->  
                      </td>
                    </tr> 
                    @endforeach
                    </tbody>
                  </table>
                   {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
                  <br/>
                  {{ HTML::link("qc_reports/excelCustContactInfoRepExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
                </section>
              </div>
            </section>
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
          $('#cus_name').val("");
      });
      function toggleCustomerInfo(id,emp_id){
          $('#hideme_'+id).toggle();
          var cus_name = $('#cus_name').val();
         /* var EDate = $('#EDate').val();
          var SJobNumber = $('#SJobNumber').val();
          var EJobNumber = $('#EJobNumber').val();
          $.ajax({
              url: "{{URL('ajax/getEmpPayableAmtInfo')}}",
                data: {
                 'SDate' : SDate,
                 'EDate' : EDate,
                 'emp_id' : emp_id,
                 'SJobNumber' : SJobNumber,
                 'EJobNumber' : EJobNumber
                },
              success: function (data) {
                $('#show_detail_data_'+emp_id).html(data);
              },
          });*/
      }
      $( document ).ready(function() {
        var cnt = '{{count($query_data)}}';  
        var icnt = 1;
        while(icnt <= cnt){
            $('#hideme_'+icnt).hide();
            icnt = parseInt(icnt) + parseInt("1");
         }
    });
    </script>
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script> 
@stop