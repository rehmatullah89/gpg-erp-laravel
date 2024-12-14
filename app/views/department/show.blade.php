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
                ADD DEPARTMENT USERS 
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                  <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                    <b><i>Select Department Users and Modify their roles! </i></b>
                  </header>
              </section>
             <!-- ////////////////////////////////////////// -->
             {{ Form::open(array('before' => 'csrf','method' => 'post' ,'url'=>route('department/manageDepartmentUsers', array('id'=> $id)))) }}
               <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
              <div class="form-group"><b> Department Name:&nbsp;<i style="color:#78cd51;"><?php echo $query_data;?></i></b><br/></div>
              <div class="form-group"><b> Head Of Department*:&nbsp;</b>
                <select id="headOfDept" class="form-control m-bot15" name="headOfDept" style="display:inline;">
                  <option value=""><i style="color:grey;"> Select Head Of Department</i></option>
                  {{$option_list}}
                </select>
              </div>
              <div class="form-group"><b> SELECT ALL USERS:&nbsp;</b>
                {{ Form::checkbox('checkThis', '','', array('class' => 'input-group', 'style'=>'display:inline;','onclick'=>'toggle(this)')) }}
              </div>
                <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                  <tbody>
    
                  @for ($i = 0; $i < count($all_emps); $i=$i+4)
                   <tr>
                    <td>
                      <div class="form-group"><b>{{isset($all_emps[$i]['name'])?$all_emps[$i]['name']:''}}</b>
                      @if(isset($all_emps[$i]['id']))
                      {{ Form::checkbox('emp[]', $all_emps[$i]['id'],(in_array($all_emps[$i]['id'], $dept_users))?1:'', array('class' => 'input-group', 'style'=>'display:inline;')) }}
                      </div>
                      @endif
                    </td>
                    <td>
                      <div class="form-group"><b> {{isset($all_emps[$i+1]['name'])?$all_emps[$i+1]['name']:''}}</b>
                       @if(isset($all_emps[$i+1]['id']))
                       {{ Form::checkbox('emp[]', $all_emps[$i+1]['id'],(in_array($all_emps[$i+1]['id'], $dept_users))?1:'', array('class' => 'input-group', 'style'=>'display:inline;')) }}
                        @endif
                      </div>
                    </td>
                    <td>
                      <div class="form-group"><b> {{isset($all_emps[$i+2]['name'])?$all_emps[$i+2]['name']:''}}</b>
                       @if(isset($all_emps[$i+2]['id']))
                       {{ Form::checkbox('emp[]', $all_emps[$i+2]['id'],(in_array($all_emps[$i+2]['id'], $dept_users))?1:'', array('class' => 'input-group', 'style'=>'display:inline;')) }}
                       @endif
                      </div>
                    </td>
                    <td>
                      <div class="form-group"><b> {{isset($all_emps[$i+3]['name'])?$all_emps[$i+3]['name']:''}}</b>
                       @if(isset($all_emps[$i+3]['id']))
                        {{ Form::checkbox('emp[]', $all_emps[$i+3]['id'],(in_array($all_emps[$i+3]['id'], $dept_users))?1:'', array('class' => 'input-group', 'style'=>'display:inline;')) }}
                       @endif
                      </div>
                    </td>
                   </tr>
                   @endfor
                  </tbody>
                </table>
                  {{ Form::submit('Save / Eidt', array('class' => 'btn btn-success')) }}                               
              </section>
              </div>
              </div>
              {{ Form::close() }}
              </section>
              </div>
              </div>
              <!-- page end-->
     <script type="text/javascript">
      function toggle(source) {
            checkboxes = document.getElementsByName('emp[]');
            for(var i=0, n=checkboxes.length;i<n;i++) {
              checkboxes[i].checked = source.checked;
            }
      }
     </script>         
    <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop