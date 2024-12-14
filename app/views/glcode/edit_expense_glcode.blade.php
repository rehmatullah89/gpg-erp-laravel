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
               ADD NEW EXPENSE GL-CODE
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>Enter required* Inoformation! </i></b>
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
              </section>
             <!-- ////////////////////////////////////////// -->
              {{ Form::open(array('before' => 'csrf' ,'url'=>route('glcode/edit_expense_glcode',array('id'=>$id)), 'files'=>true, 'method' => 'post')) }}
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                  <table class="table table-bordered table-striped table-condensed cf" id="myTable" style="text-align:center;">
                        <tbody>
                          <tr>
                          <th style="text-align:center;">Select Parent Expense Gl-Code:*</th>
                          <td>
                            {{ Form::select('_parent_id',array(''=>'Select Parent')+$gcodes,$res['parent_id'], array('class' => 'form-control dpd1', 'id' => '_parent_id', 'required')) }}
                            </td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Expense Gl-Code Type[{{ HTML::link('glcode/new_type/', 'Add New' , array('class'=>'btn btn-link btn-xs'))}} ]:*</th>
                          <td>
                            {{ Form::select('_gpg_expense_gl_type_id',array(''=>'Select Type')+$gltypes,$res['gpg_expense_gl_type_id'], array('class' => 'form-control dpd1', 'id' => '_gpg_expense_gl_type_id', 'required')) }}
                            </td>
                          </tr>
                          
                          <tr>
                          <th style="text-align:center;">Expense  GL-Code#*:</th>
                          <td>{{ Form::input('number','_expense_gl_code',$res['expense_gl_code'], array('class' => 'form-control dpd1', 'id' => '_expense_gl_code', 'required')) }}</td>
                          </tr>
                          <tr class="qntity">
                            <th style="text-align:center;">Description:*</th>
                            <td>{{ Form::text('_description',$res['description'], array('class' => 'form-control dpd1', 'id' => '_description','required')) }}</td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Status:</th>
                          <td>
                            {{ Form::select('_status',array('A'=>'Active','B'=>'Blocked'),$res['status'], array('class' => 'form-control dpd1', 'id' => '_status', 'required')) }}
                            </td>
                          </tr>
                          <tr>
                          <th style="text-align:center;">Exclude form OH Calculation:</th>
                          <td>
                            {{ Form::checkbox('_exclude_from_oh',1,$res['exclude_from_oh'], array('class' => 'form-control dpd1', 'id' => '_exclude_from_oh')) }}
                            </td>
                          </tr>
                          @if(sizeof($tags_array)>1)
                          <?php $j=1;?>
                          @foreach($tags_array as $key=>$tagArr) 
                            <?php //$arr = @explode('~', $tagArr);?>
                            <tr>
                            @if($j==1)
                            <th style="text-align:center;">Tags[<i class='fa fa-plus-square' title="creat New Row" id='create_another_row'></i>]:</th>
                            <td>
                              {{ Form::select('parent_tag_'.$j,$ptypeArr,@$tagArr['parent_tag'], array('class' => 'form-control dpd1', 'id' => 'parent_tag_1', 'required','style'=>'display:inline;')) }}
                              {{ Form::select('child_tag_'.$j,$ctypeArr,@$tagArr['child_tag'], array('class' => 'form-control dpd1', 'id' => 'child_tag_1', 'required','style'=>'display:inline;')) }}
                              <input type="hidden" name="counter_row" id="counter_row" value="{{$j}}"> 
                            </td>
                            @else
                            <td>&nbsp;</td>
                            <td>
                              {{ Form::select('parent_tag_'.$j,$ptypeArr,@$tagArr['parent_tag'], array('class' => 'form-control dpd1', 'id' => 'parent_tag_1', 'required','style'=>'display:inline;')) }}
                              {{ Form::select('child_tag_'.$j,$ctypeArr,@$tagArr['child_tag'], array('class' => 'form-control dpd1', 'id' => 'child_tag_1', 'required','style'=>'display:inline;')) }}
                              <input type="hidden" name="counter_row" id="counter_row" value="{{$j}}"> 
                            </td>
                            @endif
                            </tr>
                            <?php $j++;?>
                          @endforeach  
                          @else
                            <tr>
                            <th style="text-align:center;">Tags[<i style="cursor:pointer;" class='fa fa-plus-square' title="creat New Row" id='create_another_row'></i>]:</th>
                            <td>
                              {{ Form::select('parent_tag_1',$ptypeArr,'', array('class' => 'form-control dpd1', 'id' => 'parent_tag_1', 'required','style'=>'display:inline;')) }}
                              {{ Form::select('child_tag_1',$ctypeArr,'', array('class' => 'form-control dpd1', 'id' => 'child_tag_1', 'required','style'=>'display:inline;')) }}
                              <input type="hidden" name="counter_row" id="counter_row" value="1"> 
                            </td>
                            </tr>
                          @endif
                        </tbody>
                  </table>
                    {{ Form::submit('Update Expence GL-Code', array('class' => 'btn btn-success')) }}
              </section>
              </div>
              </div>
              {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
    <script type="text/javascript">
     $('#create_another_row').click(function(){
        var count = parseInt('1')+parseInt($('#counter_row').val());
        var str = '<tr><th style="text-align:center;">&nbsp;</th><td><select name="parent_tag_'+count+'" style="display:inline;" required="required" id="parent_tag_'+count+'" class="form-control dpd1">'+document.getElementById('parent_tag_1').innerHTML+'</select><select name="child_tag_'+count+'" style="display:inline;" required="required" id="child_tag_'+count+'" class="form-control dpd1">'+document.getElementById('child_tag_1').innerHTML+'</select></td></tr>';
        $('#myTable tr:last').after(str);
        $('#counter_row').val(count);
     }); 
    </script>
@stop