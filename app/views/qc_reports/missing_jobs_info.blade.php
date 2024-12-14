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
                    MISSING INFO IN JOBS REPORT 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                  <b>Search By:<i>Missing/ Group By</i></b>
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
                                  <td>{{Form::label('opt_type', 'Missing:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} </td>
                                  <td data-title="Missing:">
                                    {{Form::select('opt_type',array('loc'=>'Location','job_comp'=>'Job Site Info Completely','job_part'=>'Job Site Info Partially','eng_comp'=>'Engine Info Completely','eng_part'=>'Engine Info Partially','gen_comp'=>'Generator Info Completely','gen_part'=>'Generator Info Partially'),'loc', array('class' => 'form-control', 'id' => 'opt_type')) }}
                                  </td>
                                  <td>{{Form::label('opt_group', 'Group By:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}} </td>
                                  <td data-title="Group By:">
                                    {{Form::select('opt_group',array('cus'=>'Customer','emp'=>'Employee'),'', array('class' => 'form-control', 'id' => 'opt_group')) }}
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
                        <?php $colcount=0;
                              $g_key = 'GPG_employee_id';
                              $opt_type = Input::get('opt_type');
                              $opt_group= Input::get('opt_group');
                              if($opt_group=="")
                                $opt_group = "emp";
                              if($opt_type=="")
                                $opt_type = "loc";
                        ?>
                        @foreach($query_data as $row)
                          <?php $colcount++;?>
                          <tr height="40px">
                            <td align="center" ><strong>{{Form::button('<i class="fa fa-plus"></i>', array('class' => 'btn btn-success btn-xs', 'onclick'=>'toggleCustomerInfo('.$colcount.','.((trim($row[$g_key]) != '')?$row[$g_key]:0).')'))}}</strong></td>
                            <td bgcolor="#FFFFCC" width="100px"><strong>{{$opt_group=="cus"?$row['GPG_customer_id']:$row['GPG_employee_id']}}</strong></td>
                            <td bgcolor="#FFFFCC"><strong>{{$opt_group=="cus"?($row['cus_name']==""?"&lt;no name&gt;":$row['cus_name']):($row['emp_name']==""?"&lt;no name&gt;":$row['emp_name'])}}</strong></td>
                            <td bgcolor="#FFFFCC" width="50px">{{$row['tot_count']}}</td>
                          </tr>
                          <tr id="hideme_{{$colcount}}" bgcolor="#FFFFCC"><td colspan="6">
                          <!-- orgin Start -->
                          <?php
                            if($row['tot_count']>20 or true) {
                              if($opt_type=="loc") {
                                $SUBDSQL = "";
                                if($opt_group=="emp") {
                                  $SUBDSQL = " AND GPG_employee_id = '".$row['GPG_employee_id']."'";
                                }
                                elseif($opt_group=="cus") {
                                  $SUBDSQL = " AND GPG_customer_id = '".$row['GPG_customer_id']."'";
                                }
                                $sub_query = "SELECT 
                                    (SELECT name FROM gpg_customer WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
                                    (SELECT name FROM gpg_employee WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name,
                                    gpg_field_service_work.*
                                    FROM 
                                    gpg_field_service_work
                                    WHERE 
                                    (gpg_field_service_work.gpg_consum_contract_equipment_id IS NULL 
                                    OR gpg_field_service_work.gpg_consum_contract_equipment_id = \"\") AND 1
                                    ".$SUBDSQL." ORDER BY job_num";
                                }
                                elseif($opt_type=="job_comp") {
                                  $SUBDSQL = "";
                                if($opt_group=="emp") {
                                  $SUBDSQL = " AND GPG_employee_id = '".$row['GPG_employee_id']."'";
                                }
                                elseif($opt_group=="cus") {
                                  $SUBDSQL = " AND GPG_customer_id = '".$row['GPG_customer_id']."'";
                                }
                                $sub_query = "SELECT job_num,
                                        (SELECT name FROM gpg_customer WHERE id = gpg_field_service_work.GPG_customer_id),
                                        (SELECT name FROM gpg_employee WHERE id = gpg_field_service_work.GPG_employee_id),
                                        gpg_field_service_work.job_site_contact,
                                        gpg_consum_contract_equipment.address1,
                                        gpg_consum_contract_equipment.city,
                                        gpg_consum_contract_equipment.state,
                                        gpg_consum_contract_equipment.zip,
                                        gpg_field_service_work.GPG_employee_id,
                                        gpg_field_service_work.GPG_customer_id,
                                        (SELECT
                                        NAME
                                        FROM gpg_customer
                                        WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
                                        (SELECT
                                        NAME
                                        FROM gpg_employee
                                        WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
                                        FROM gpg_field_service_work,
                                        gpg_consum_contract_equipment
                                        WHERE gpg_consum_contract_equipment.id = gpg_field_service_work.gpg_consum_contract_equipment_id
                                        AND (gpg_field_service_work.job_site_contact IS NULL
                                        OR gpg_field_service_work.job_site_contact = \"\")
                                        AND (gpg_consum_contract_equipment.address1 IS NULL
                                        OR gpg_consum_contract_equipment.address1 = \"\")
                                        AND (gpg_consum_contract_equipment.city IS NULL
                                        OR gpg_consum_contract_equipment.city = \"\")
                                        AND (gpg_consum_contract_equipment.state IS NULL
                                        OR gpg_consum_contract_equipment.state = \"\")
                                        AND (gpg_consum_contract_equipment.phone IS NULL
                                        OR gpg_consum_contract_equipment.phone = \"\")
                                        ".$SUBDSQL."
                                    ORDER BY job_num";
                                }
                                elseif($opt_type=="job_part") {
                                  $SUBDSQL = "";
                                if($opt_group=="emp") {
                                  $SUBDSQL = " AND GPG_employee_id = '".$row['GPG_employee_id']."'";
                                }
                                elseif($opt_group=="cus") {
                                  $SUBDSQL = " AND GPG_customer_id = '".$row['GPG_customer_id']."'";
                                }
                                $sub_query = "SELECT
                                          job_num,
                                          (SELECT name FROM gpg_customer WHERE id = gpg_field_service_work.GPG_customer_id),
                                          (SELECT name FROM gpg_employee WHERE id = gpg_field_service_work.GPG_employee_id),
                                          gpg_field_service_work.job_site_contact,
                                          gpg_consum_contract_equipment.address1,
                                          gpg_consum_contract_equipment.city,
                                          gpg_consum_contract_equipment.state,
                                          gpg_consum_contract_equipment.zip,
                                          gpg_field_service_work.GPG_employee_id,
                                          gpg_field_service_work.GPG_customer_id,
                                          (SELECT
                                           NAME
                                           FROM gpg_customer
                                           WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
                                          (SELECT
                                           NAME
                                           FROM gpg_employee
                                           WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
                                        FROM gpg_field_service_work,
                                          gpg_consum_contract_equipment
                                        WHERE gpg_consum_contract_equipment.id = gpg_field_service_work.gpg_consum_contract_equipment_id
                                          AND ((gpg_field_service_work.job_site_contact IS NULL
                                              OR gpg_field_service_work.job_site_contact = \"\")
                                          OR (gpg_consum_contract_equipment.address1 IS NULL
                                              OR gpg_consum_contract_equipment.address1 = \"\")
                                          OR (gpg_consum_contract_equipment.city IS NULL
                                              OR gpg_consum_contract_equipment.city = \"\")
                                          OR (gpg_consum_contract_equipment.state IS NULL
                                              OR gpg_consum_contract_equipment.state = \"\")
                                          OR (gpg_consum_contract_equipment.phone IS NULL
                                              OR gpg_consum_contract_equipment.phone = \"\"))
                                    ".$SUBDSQL." ORDER BY job_num";
                                }
                                elseif($opt_type=="eng_part") {
                                  $SUBDSQL = "";
                                if($opt_group=="emp") {
                                  $SUBDSQL = " AND GPG_employee_id = '".$row['GPG_employee_id']."'";
                                }
                                elseif($opt_group=="cus") {
                                  $SUBDSQL = " AND GPG_customer_id = '".$row['GPG_customer_id']."'";
                                }
                                $sub_query= "SELECT *,(SELECT NAME
                                        FROM gpg_customer
                                        WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
                                        (SELECT
                                        NAME
                                        FROM gpg_employee
                                        WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
                                        FROM 
                                        gpg_field_service_work
                                        WHERE 
                                        ((gpg_field_service_work.eng_make IS NULL 
                                        OR gpg_field_service_work.eng_make = \"\")
                                        OR
                                        (gpg_field_service_work.eng_model IS NULL 
                                        OR gpg_field_service_work.eng_model = \"\")
                                        OR
                                        (gpg_field_service_work.eng_serial IS NULL 
                                        OR gpg_field_service_work.eng_serial = \"\")
                                        OR
                                        (gpg_field_service_work.eng_spec IS NULL 
                                        OR gpg_field_service_work.eng_spec = \"\")) 
                                        ".$SUBDSQL."
                                    ORDER BY job_num";
                                }elseif($opt_type=="eng_comp"){
                                  $SUBDSQL = "";
                                if($opt_group=="emp"){
                                  $SUBDSQL = " AND GPG_employee_id = '".$row['GPG_employee_id']."'";
                                }
                                elseif($opt_group=="cus"){
                                  $SUBDSQL = " AND GPG_customer_id = '".$row['GPG_customer_id']."'";
                                }
                                $sub_query= "SELECT *,(SELECT
                                          NAME
                                          FROM gpg_customer
                                          WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
                                          (SELECT
                                          NAME
                                          FROM gpg_employee
                                          WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
                                          FROM 
                                          gpg_field_service_work
                                          WHERE 
                                          ((gpg_field_service_work.eng_make IS NULL 
                                          OR gpg_field_service_work.eng_make = \"\")
                                          AND
                                          (gpg_field_service_work.eng_model IS NULL 
                                          OR gpg_field_service_work.eng_model = \"\")
                                          AND
                                          (gpg_field_service_work.eng_serial IS NULL 
                                          OR gpg_field_service_work.eng_serial = \"\")
                                          AND
                                          (gpg_field_service_work.eng_spec IS NULL 
                                          OR gpg_field_service_work.eng_spec = \"\")) 
                                          ".$SUBDSQL." ORDER BY job_num";
                                }
                                elseif($opt_type=="gen_part") {
                                  $SUBDSQL = "";
                                if($opt_group=="emp") {
                                  $SUBDSQL = " AND GPG_employee_id = '".$row['GPG_employee_id']."'";
                                }
                                elseif($opt_group=="cus"){
                                  $SUBDSQL = " AND GPG_customer_id = '".$row['GPG_customer_id']."'";
                                }
                                $sub_query= "SELECT *,(SELECT
                                          NAME
                                          FROM gpg_customer
                                          WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
                                          (SELECT
                                          NAME
                                          FROM gpg_employee
                                          WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
                                          FROM 
                                          gpg_field_service_work
                                          WHERE 
                                          ((gpg_field_service_work.gen_make IS NULL 
                                          OR gpg_field_service_work.gen_make = \"\")
                                          OR
                                          (gpg_field_service_work.gen_model IS NULL 
                                          OR gpg_field_service_work.gen_model = \"\")
                                          OR
                                          (gpg_field_service_work.gen_serial IS NULL 
                                          OR gpg_field_service_work.gen_serial = \"\")
                                          OR
                                          (gpg_field_service_work.gen_spec IS NULL 
                                          OR gpg_field_service_work.gen_spec = \"\")) 
                                          ".$SUBDSQL." ORDER BY job_num";
                                }
                                elseif($opt_type=="gen_comp") {
                                  $SUBDSQL = "";
                                  if($opt_group=="emp") {
                                    $SUBDSQL = " AND GPG_employee_id = '".$row['GPG_employee_id']."'";
                                }
                                elseif($opt_group=="cus")
                                {
                                  $SUBDSQL = " AND GPG_customer_id = '".$row['GPG_customer_id']."'";
                                }
                                $sub_query= "SELECT *,(SELECT
                                        NAME
                                        FROM gpg_customer
                                        WHERE id = gpg_field_service_work.GPG_customer_id) AS cus_name,
                                        (SELECT
                                        NAME
                                        FROM gpg_employee
                                        WHERE id = gpg_field_service_work.GPG_employee_id) AS emp_name
                                        FROM 
                                        gpg_field_service_work
                                        WHERE 
                                        ((gpg_field_service_work.gen_make IS NULL 
                                        OR gpg_field_service_work.gen_make = \"\")
                                        AND
                                        (gpg_field_service_work.gen_model IS NULL 
                                        OR gpg_field_service_work.gen_model = \"\")
                                        AND
                                        (gpg_field_service_work.gen_serial IS NULL 
                                        OR gpg_field_service_work.gen_serial = \"\")
                                        AND
                                        (gpg_field_service_work.gen_spec IS NULL 
                                        OR gpg_field_service_work.gen_spec = \"\")) 
                                        ".$SUBDSQL." ORDER BY job_num";
                               }
                               $info_res = DB::select(DB::raw($sub_query));
                              ?>
                              <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                <tr height="35px">
                                  <td bgcolor="#F2F2F2"><strong>Job Num</strong></td>
                                  <td bgcolor="#F2F2F2"><strong>Customer</strong></td>
                                  <td bgcolor="#F2F2F2"><strong>Employee</strong></td><?php 
                                  if($opt_type=="job_part" || $opt_type=="job_comp" || $opt_type=="eng_part" || $opt_type=="eng_comp" || $opt_type=="gen_part" || $opt_type=="gen_comp") {
                                  ?>
                                  <td bgcolor="#F2F2F2" width="300px"><strong>Info</strong></td>
                                  <?php 
                                }
                                ?>
                                </tr><?php 
                                  foreach ($info_res as $key => $value2) { 
                                    $arr_row = (array)$value2;
                                  ?>
                                 <tr>
                                   <td bgcolor="#FFF">{{ HTML::link('job/field_service_work_list',$arr_row['job_num'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                                   <td bgcolor="#FFF"><?php echo $arr_row['cus_name'];?></td>
                                   <td bgcolor="#FFF"><?php echo $arr_row['emp_name'];?></td><?php 
                                   if($opt_type=="job_part" || $opt_type=="job_comp") {
                                   ?>
                                   <td  bgcolor="">
                                    <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                      <tr>
                                        <td bgcolor='#FFF' width="150px">Contact Name</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['job_site_contact']?></td>
                                      </tr>
                                      <tr>
                                        <td bgcolor='#FFF'>Address</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['address1']?></td>
                                        </tr>
                                      <tr>
                                        <td bgcolor='#FFF'>Phone</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['phone']?></td>
                                      </tr>
                                      <tr>
                                        <td bgcolor='#FFF'>State</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['state']?></td>
                                      </tr>
                                      <tr>
                                        <td bgcolor='#FFF'>Zip</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['zip']?></td>
                                      </tr>
                                    </table>
                                    </td> <?php 
                                  }
                                  if($opt_type=="eng_part" || $opt_type=="eng_comp"){
                                  ?>
                                    <td  bgcolor="">
                                      <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                        <tr>
                                          <td bgcolor='#FFF' width="150px">Engine Make</td>
                                          <td bgcolor='#FFF'><?php echo $arr_row['eng_make']?></td>
                                        </tr>
                                        <tr>
                                          <td bgcolor='#FFF'>Model</td>
                                          <td bgcolor='#FFF'><?php echo $arr_row['eng_model']?></td>
                                        </tr>
                                        <tr>
                                          <td bgcolor='#FFF'>Serial</td>
                                          <td bgcolor='#FFF'><?php echo $arr_row['eng_serial']?></td>
                                        </tr>
                                        <tr>
                                          <td bgcolor='#FFF'>Spec</td>
                                          <td bgcolor='#FFF'><?php echo $arr_row['eng_spec']?></td>
                                        </tr>
                                    </table>
                                    </td><?php 
                                  }
                                  if($opt_type=="gen_part" || $opt_type=="gen_comp") {
                                  ?>
                                    <td  bgcolor="">
                                    <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                      <tr>
                                        <td bgcolor='#FFF' width="150px">Generator Make</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['gen_make'];?></td>
                                      </tr>
                                      <tr>
                                        <td bgcolor='#FFF'>Model</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['gen_model'];?></td>
                                      </tr>
                                      <tr>
                                        <td bgcolor='#FFF'>Serial</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['gen_serial'];?></td>
                                      </tr>
                                      <tr>
                                        <td bgcolor='#FFF'>Spec</td>
                                        <td bgcolor='#FFF'><?php echo $arr_row['gen_spec'];?></td>
                                        </tr>
                                    </table>
                                    </td><?php 
                                  }?>
                                 </tr>
                                  <?php 
                                }
                                ?>
                                </table>
                              <?php 
                            }else{?>
                              <table bordercolor="#cccccc" cellspacing="1" width="100%" border="1" style="border-collapse:collapse">
                                <tr height="35px">
                                  <td bgcolor="#F2F2F2"><strong>Job Num</strong></td>
                                  <td bgcolor="#F2F2F2"><strong>Customer</strong></td>
                                  <td bgcolor="#F2F2F2"><strong>Employee</strong></td>
                                </tr><?php 
                                  $dat = explode("@###@,",$row['msng_dat']);
                                  foreach($dat as $k => $v)
                                  {
                                    $v = str_replace("@###@","",$v);
                                    $str = explode("~##~",$v);
                                    ?>
                                      <tr>
                                        <td>{{ HTML::link('job/field_service_work_list',@$str[0], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                                        <td><?php echo @$str[1];?></td>
                                        <td><?php echo @$str[2];?></td>
                                      </tr>
                                    <?php 
                                  }
                                ?>
                                </table>
                              <?php 
                            }
                            ?>
                          <!-- orgin End -->  
                            </td>
                          </tr> 
                    @endforeach
                    </tbody>
                  </table>
                   {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
                  <br/>
                  {{ HTML::link("qc_reports/excelMissingJobsInfoRepExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
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
          $('#opt_type').val("");
          $('#opt_group').val("");
      });
      function toggleCustomerInfo(id,emp_id){
          $('#hideme_'+id).toggle();
          var opt_type = $('#opt_type').val();
          var opt_group = $('#opt_group').val();
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