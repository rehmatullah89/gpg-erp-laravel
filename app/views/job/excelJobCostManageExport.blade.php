     <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">
                                      <tr>
                                          <th>Del</th>
                                          <th style="text-align:center;">Job Num</th>
                                          <th style="text-align:center;">Name</th>
                                          <th style="text-align:center;">Type</th>
                                          <th style="text-align:center;">Date</th>
                                          <th style="text-align:center;">Num</th>
                                          <th style="text-align:center;">Source Name</th>
                                          <th style="text-align:center;">Memo </th>
                                          <th style="text-align:center;">Account</th>
                                          <th style="text-align:center;">Clr</th>
                                          <th style="text-align:center;">Split</th>
                                          <th style="text-align:center;">Amount</th>
                                      </tr>
                                      </thead>
                                      <tbody>
                                        @foreach($query_data as $row)
                                          <tr>
                                            <td>
                                             {{ Form::open(array('method' => 'post','id'=>'myForm'.$row->id.'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('job/destroyJobCost', $row->id))) }}
                                             {{Form::hidden('id',$row->id)}}
                                             {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row->id.'").submit()')) }}
                                             {{ Form::close() }}
                                            </td>
                                            <td>{{$row->job_num}}</td>
                                            <td><?php $nameJonNum = preg_split('/:/',$row->name); echo $nameJonNum[0]; ?></td>
                                            <td>{{$row->type}}</td>
                                            <td>{{($row->date!=''?date('m/d/Y',strtotime($row->date)):"-")}}</td>
                                            <td>{{$row->num}}</td>
                                            <td>{{$row->source_name}}</td>
                                            <td>{{$row->memo}}</td>
                                            <td>{{$row->account}}</td>
                                            <td>{{$row->clr}}</td>
                                            <td>{{$row->split}}</td>
                                            <td>{{'$'.number_format($row->amount,2)}}</td>
                                          </tr>
                                        @endforeach
                                      </tbody>
                                  </table>