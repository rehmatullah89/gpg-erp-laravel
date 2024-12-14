<table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
  <thead>
    <tr>
      <th>Serial</th>
      <th>Job Number</th>
      <th>Date</th>
      <th>Time In</th>
      <th>Time Out</th>
    </tr>
  </thead>
  <tbody>
    <?php $SrNo=1;?>
    @foreach($query_data as $row)
    <tr>
      <td height="30">{{$SrNo++}}</td>
      <td>{{ HTML::link('job/job_form/'.$row['GPG_job_id'].'/'.$row['job_num'].'', $row['job_num'] , array('target'=>'_blank','class'=>'btn btn-link', 'id'=>$row['GPG_job_id'],'j_num'=>$row['job_num']))}}</td>
      <td>{{date('m/d/Y',strtotime($row['date']))}}</td>
      <td>{{$row['time_in']}}</td>
      <td>{{$row['time_out']}}</td>
    </tr>
    @endforeach
  </tbody>  
</table>  