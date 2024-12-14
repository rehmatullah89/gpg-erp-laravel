<b>{{count($query_data)}}</b> duplicate service jobs found
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <thead class="cf">
                      <tr>
                        <th>Serial</th>
                        <th>Job Number</th>
                        <th>Count</th>
                      </tr>
                    </thead>
                    <tbody class="cf">
                      <?php $SrNo = 1;?>
                      @foreach($query_data as $object)
                        <tr>
                          <td>{{$SrNo++}}</td>
                          <td>{{ HTML::link('job/service_job_list',$object->job_num, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td>
                          <td>{{$object->total}}</td>
                        </tr>
                      @endforeach
                    </tbody>  
                  </table>