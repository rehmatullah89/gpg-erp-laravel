<table class="table table-bordered table-striped table-condensed cf">
                        <thead class="cf">
                          <tr>
                            <th>Total Contracts</th>  
                            <th>Total Jobs</th>  
                            <th>Completed Jobs</th>  
                            <th>Incomplete Jobs</th>  
                          </tr>
                        </thead> 
                        <tbody class="cf">
                          <tr>
                            <td>{{count($qry_data)}}</td>
                            <td>{{$total_all}}</td>
                            <td>{{$total_c}}</td>
                            <td>{{$total_ic}}</td>
                          </tr>
                        </tbody>
                  </table>
                </section>
              </div>
              </section>
                </div>
              </div>

                <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              
              <div class="panel-body">
              <div class="adv-table">
              <table class="display table table-bordered">
              <thead>
              <tr>
                  <th>Action</th>
                  <th>Contract #</th>
                  <th>Task</th>
                  <th>Count</th>
                  <th class="hidden-phone">Complete</th>
                  <th class="hidden-phone">In-Complete</th>
              </tr>
              </thead>
              <tbody>
              <?php $index=1;?>
                    @foreach($qry_data as $key=>$arr_data)
                      <tr>
                        <td data-title="Actions:">{{Form::button('<i class="fa fa-plus"></i>', array('class' => 'btn btn-success', 'onclick'=>'toggleCustomerInfo('.$index.')'))}} </td>  
                        <td data-title=":">{{$key}}</td>
                        <td data-title=":">{{$arr_data['cus_name']}}</td>
                        <td data-title=":" class="hidden-phone">{{$arr_data['count']['total']+1}}</td>
                        <td data-title=":" class="hidden-phone">{{'-'}}</td>
                        <td data-title=":" class="hidden-phone">{{$arr_data['count']['incomplete']+1}}</td>
                      </tr>
                    <tr id="hideme_{{$index}}">
                      <td colspan="6">
                          <table class="table table-bordered table-striped table-condensed cf">
                           @foreach($arr_data as $k=>$val)  
                           @if($k != 'count' && $k != 'cus_name' && $k != 'service_types')    
                            <tr><th>Contract#</th><td>{{$k}}</td></tr>
                            <tr><th>Job Number</th><th>Location</th><th>Task</th></tr>
                            @foreach($val as $k2=>$v2)
                              @if($k2 != 'cus_name' && $k2 != 'count')
                              <tr>
                                <td>{{$k2}}</td><td>{{$v2['location']}}</td><td>{{$v2['task']}}</td>
                              </tr>
                              @endif
                            @endforeach
                          @endif  
                          @endforeach
                        </table>
                        </td>
                    <?php $index++;?>  
                    @endforeach
                  </tr>  
                </tbody>
              </table>