<table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
  <thead>
    <tr>
      <th>Serial</th>
      <th>Job Number</th>
      <th>Material Cost</th>
      <th>Tax Amount</th>
    </tr>
  </thead>
  <tbody>
    <?php $SrNo = 1;?>
    @foreach($query_data as $row)
    <tr>
      <td height="30">{{$SrNo++}}</td><td>{{ HTML::link('job/commission_list',$row['job_num'], array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}</td><td>{{number_format($row['material_cost'],2)}}</td><td>{{number_format($row['tax_amt'],2)}}</td>
    </tr>
    @endforeach
  </tbody>  
</table>  