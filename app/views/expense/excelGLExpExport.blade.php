           <table class="table table-bordered table-striped table-condensed cf" >
              <thead>
                <tr><th>Action</th><th>Expense Gl-Code</th><th>Name</th><th>Type</th><th>Date</th><th>Num</th><th>Source Name</th><th>Memo</th><th>Class</th><th>Clr</th><th>Split</th><th>Debit</th><th>Credit</th><th>Amount</th></tr>   
              </thead>
              <tbody class="cf">
              @if(!empty($data_arr))
                <?php $preParent='';
                  $rowCount=0; 
                  $prev=''; ?>
                @foreach($data_arr as $row)
                  @if($row['expenseParent']!=0 && $preParent != $row['expenseParent'])
                   <tr><td colspan="8"><b>{{$row['expenseGlCode']}}</b></td></tr>  
                  @elseif($prev!=$row['expenseGlCode'] && !empty($prev))
                    <tr><td colspan="8"><b>{{$row['expenseGlCode']}}</b></td></tr>  
                  @endif
                  <tr>
                    <td>
                      {{ Form::open(array('method' => 'post','id'=>'myForm'.$row['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('expense/deleteGlCodeExpense', $row['id']))) }}
                      {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['id'].'").submit()')) }}
                      {{ Form::close() }}
                    </td>
                    <td>{{''}}</td>
                    <td>{{$row['name']}}</td>
                    <td>{{$row['type']}}</td>
                    <td>{{($row['date']!=''?date('m/d/Y',strtotime($row['date'])):"-")}}</td>
                    <td>{{$row['num']}}</td>
                    <td>{{$row['source_name']}}</td>
                    <td>{{$row['memo']}}</td>
                    <td>{{$row['class']}}</td>
                    <td>{{$row['clr']}}</td>
                    <td>{{$row['split']}}</td>
                    <td>{{"$".number_format($row['debit'],2)}}</td>
                    <td>{{"$".number_format($row['credit'],2)}}</td>
                    <td>{{"$".number_format($row['amount'],2)}}</td>
                  </tr>
                  <?php $prev=$row['expenseGlCode'];
                    $preParent=$row['expenseParent']; ?>
                @endforeach
              @else
              <tr><td>No records found on this page!</td></tr>
              @endif
              </tbody>
              </table>