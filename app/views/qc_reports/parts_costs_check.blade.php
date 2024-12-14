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
                CHANGED PARTS PRICE 
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>SEARCH by:</b> Job Number & Filters</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/parts_costs_check'), 'files'=>true, 'method' => 'post')) }}
                                  <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                    <tbody>
                                    <?php
                                      $jobnumber = Input::get("jobnumber");
                                      $cp = Input::get("cp");
                                      $lp = Input::get("lp");
                                      $mp = Input::get("mp");
                                    ?>
                                    <tr>
                                      <td><b>Job Number: </b></td>
                                      <td>
                                        {{Form::text('jobnumber',$jobnumber, ['id' => 'jobnumber', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td>
                                        <input type="checkbox" name="cp" value="1" <?php echo $cp==1?"checked":""?> /> Changed Cost Price
                                          &nbsp;&nbsp;
                                          <input type="checkbox" name="lp" value="1" <?php echo $lp==1?"checked":""?> /> Changed List Value
                                          &nbsp;&nbsp;
                                          <input type="checkbox" name="mp" value="1" <?php echo $mp==1?"checked":""?> /> Changed Margin Value
                                          &nbsp;&nbsp;
                                      </td>
                                      <td>
                                        {{Form::submit('Search', array('class' => 'btn btn-success'))}}
                                        {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                      </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <br/>
                                  </section>
                               {{ Form::close() }}
              </section> 
                <section id="no-more-tables" style="padding:10px;">
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>Sr.No</th>
                        <th>Job Number</th>
                        <th>Part Type</th>
                        <th>Part Number</th>
                        <th>Serial Number</th>
                        <th>Spec Number</th>
                        <th>Part Cost</th>
                        <th>Cost in Job</th>
                        <th>Part List</th>
                        <th>List in Job</th>
                        <th>Part Margin</th>
                        <th>Margin in Job</th>
                        <th>Changed on</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php
                      $count =0;
                      if(count($query_data)>0)
                      {
                        $SrNo = 0;
                        foreach ($query_data as $key => $arr) {
                          $SrNo++;
                        ?>
                          <tr>
                            <td width="20" height="25" align="center" >{{++$count}}</td>
                            <td align="center" bgcolor="#FFFFCC">
                            {{ HTML::link('job/field_service_work_list', $arr['job_num'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                            </td>
                            <td align="center" bgcolor="#FFFFCC">{{$arr['part_type']}}</td>
                            <td align="center" bgcolor="#FFFFCC">
                            {{ HTML::link('parts', $arr['part_number'] , array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}  
                            </td>
                            <td align="center" bgcolor="#FFFFCC">{{$arr['serial_number']}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{$arr['spec_number']}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{number_format($arr['mat_cost'],2)}}</td>
                            <td align="center" bgcolor="<?php echo $arr['cp']==1?"#FFC1C1":"#FFFFCC"?>" >{{number_format($arr['job_mat_cost'],2)}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{number_format($arr['mat_list'],2)}}</td>
                            <td align="center" bgcolor="<?php echo $arr['lp']==1?"#FFC1C1":"#FFFFCC"?>">{{number_format($arr['job_mat_list'],2)}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{number_format($arr['mat_margin'],2)}}</td>
                            <td align="center" bgcolor="<?php echo $arr['mp']==1?"#FFC1C1":"#FFFFCC"?>">{{number_format($arr['job_mat_margin'],2)}}</td>
                            <td align="center" bgcolor="#FFFFCC">{{date('m/d/Y',strtotime($arr['modified_on']))}}</td>
                          </tr>
                        <?php }
                      }?>
                    </tbody>  
                  </table>  
                </section>  
              </section>
              </div>
              </div>
              {{ HTML::link("qc_reports/excelPartsCostsExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
              <br/>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
       <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });
          
          $('#reset_search_form').click(function(){
              $('#jobnumber').val("");
          });
           $('#jobnumber').focus(function() {  
              $(this).autocomplete({
                source: function (request, response) {
                $("span.ui-helper-hidden-accessible").before("<br/>");  
                $.ajax({
                    url: "{{URL('ajax/getJobNumberAutocomplete')}}",
                    data: {
                        JobNumber: this.term
                    },
                success: function (data) {
                  response( $.map( data, function( item ) {
                  return {
                      label: item.name,
                      value: item.id
                  };
                }));
                },
               });
              },
             });
            });
        </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop