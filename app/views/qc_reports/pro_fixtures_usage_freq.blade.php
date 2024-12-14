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
               FIXTURE USAGE FREQUENCY
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>View For:</b>  Fixture ID/Name/Quantity/Quotes</i>
                          </header>
              </section> 
                <section id="no-more-tables" style="padding:10px;">
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead>
                      <tr>
                        <th>SR #</th>
                        <th>Fixture ID</th>
                        <th>Fixture Name</th>
                        <th>Quantity Used</th>
                        <th>Quotes</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php 
                    $data = array();
                    foreach ($query_data as $key => $row){
                      $data[$row['id']]['name'] = $row['fixture_name'];
                      if(!isset($data[$row['id']]['total']))
                        $data[$row['id']]['total'] = 0;
                        $data[$row['id']]['total'] += $row['pro_qty_used'];
                        $data[$row['id']]['jobs'][$row['job_num']] = $row['pro_qty_used']."_".$row['gjeqid']."_".$row['occurence']."_".$row['cus_name'];
                    } // end foreach
                    $srno = 0;
                    if(sizeof($data)>0){
                      foreach($data as $key => $value){
                        $bgcolor = "#FFFFCC";
                        $srno++;
                        if($srno%2==0)
                          $bgcolor = "#FFFFFF";
                        ?>
                        <tr bgcolor="<?php echo $bgcolor?>" height="25px">
                          <td>{{$srno}}</td>
                          <td>{{$key}}</td>
                          <td>{{$value['name']}}</td>
                          <td>{{$value['total']}}</td>
                          <td title="{{print_r($value['jobs'])}}"><?php
                            foreach($value['jobs'] as $key2 => $value2){
                              $temp_arr = explode("_",$value2);
                              ?>
                              {{ HTML::link('quote/elec_quote_list',$key2.'-'.@$temp_arr[3], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                              <br/>
                              <div  id="details_<?php echo $key2?>_<?php echo $srno?>" style="display:none;">
                                <table bgcolor="#EEEEEE" border="1px soild #000000" cellspacing="0" cellpadding="4" style="border-collapse:collapse" width="200px">
                                  <tr>
                                    <td colspan="2" align="center"><strong><?php echo $key2?></strong></td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#FFFFCC">Quantity</td>
                                    <td bgcolor="#FFFFFF"><?php echo $temp_arr[0]?></td>
                                  </tr>
                                  <tr>
                                    <td bgcolor="#FFFFCC">Occurence</td>
                                    <td bgcolor="#FFFFFF"><?php echo $temp_arr[2]?> time(s)</td>
                                  </tr>
                                </table>
                              </div>
                            <?php }?>
                          </td>
                        </tr>
                        <tr bgcolor="<?php echo $bgcolor?>" height="25px" bordercolor="#000000" style="border:1px solid #000000;display:none;" >
                          <td colspan="4">
                            <table width="400px" style="border:1px solid #000000" cellspacing="0" bgcolor="<?php echo $bgcolor?>">
                              <tr height="30px">
                                <td align="center" style="border:1px solid #cccccc;"><strong>Total Quantity</strong></td>
                                <td align="center" style="border:1px solid #cccccc;"><strong>Occurence</strong></td>
                              </tr>
                              <?php 
                                foreach($value['jobs'] as $key2 => $value2){
                                  $temp_arr = explode("_",$value2);
                                  ?>
                                  <tr height="25px">
                                    <td style="border:1px solid #999999;"><?php echo $temp_arr[0]?></td>
                                    <td style="border:1px solid #999999;"><?php echo $temp_arr[2]?></td>
                                  </tr>
                                  <?php }?>
                            </table>
                          </div>
                         </td>
                        </tr>
                        <?php }
                        }?>
                    </tbody>  
                  </table>  
                </section>  
              </section>
              </div>
              </div>
              {{ HTML::link("qc_reports/excelProFixtureUsageExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}
              <br/>
              {{ $query_data->appends(array_filter(Input::except('_token')))->links() }}
  <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
  <script src="{{asset('js/common-scripts.js')}}"></script>
@stop