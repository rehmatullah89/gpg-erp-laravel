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
                  Admin Account's Management
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
              <section class="panel">
                       <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <b><i>View/ Edit/ Delete: accounts. </i></b>
                          </header>
              </section>
             <!-- ////////////////////////////////////////// -->
              <div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
                {{ HTML::link("account/excelAccountsExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success btn-xs'))}}
                  <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                        <thead class="cf">
                          <tr>
                            <th style="text-align:center;">#</th>
                            <th style="text-align:center;">Full Name</th>
                            <th style="text-align:center;">Email</th>
                            <th style="text-align:center;">Country</th>
                            <th style="text-align:center;">Login</th>
                            <th style="text-align:center;">Admin Type</th>
                            <th style="text-align:center;">Allowed Employees</th>
                            <th style="text-align:center;">Action</th>
                          </tr>
                        </thead>
                      <tbody>
                        @foreach($query_data as $row)
                          <tr>
                          <td height="30" bgcolor="#FFFFFF">{{$row['ad_id']}}</td>
                          <td bgcolor="#FFFFFF">{{$row['fname']." ".$row['lname']}}</td>
                          <td bgcolor="#FFFFFF">{{$row['email']}}</td>
                          <td bgcolor="#FFFFFF">{{@DB::table('gpg_country')->where('country_id','=',$row['country_id'])->pluck('country')}}</td>
                          <td bgcolor="#FFFFFF">{{$row['uname']}}</td>
                          <td bgcolor="#FFFFFF" align="center"><?php
                          $perVal = DB::select(DB::raw("select count(a.id) as mod_count, count(b.GPG_module_id) as perm_count from gpg_module a left join gpg_mod_perm b on (a.id = b.GPG_module_id and b.GPG_ad_acc_id = '".$row['ad_id']."')"));
                          if($perVal[0]->mod_count==$perVal[0]->perm_count || $row['uname']=="admin") 
                            echo "<strong>Super Admin (All Rights)</strong>";
                          else {
                            $modsPerm = DB::select(DB::raw("select a.module_name from gpg_module a , gpg_mod_perm b where a.id = b.GPG_module_id and b.GPG_ad_acc_id = '".$row['ad_id']."'"));
                            foreach ($modsPerm as $key => $modsRows) {
                              echo "<span class=\"smallGray\">".$modsRows->module_name.", </span>";      
                            }
                          } ?></td>
                          <td align="center" bgcolor="#FFFFFF"><?php
                          $emp_row = DB::select(DB::raw("select * from gpg_ad_acc where ad_id = '".$row['ad_id']."'"));
                          $allowed_emps = explode(",",$emp_row[0]->allowed_employees);
                          for($i=0; $i<count($allowed_emps)-1; $i++){
                            $allowed_emp_name = DB::table('gpg_employee')->where('id','=',$allowed_emps[$i])->pluck('NAME');
                            echo '<span class="smallGray">'.$allowed_emp_name.', </span>';
                          }?>
                          </td>
                          <td align="center" nowrap="nowrap" bgcolor="#FFFFFF">
                          <a href="#myModal" data-toggle="modal" id="{{$row['ad_id']}}">
                          {{Form::button('<i class="fa fa-key"></i>', array('class' => 'btn btn-success btn-xs','id'=>$row['ad_id'],'name'=>'chnage_pas_id'))}}
                          </a>
                          <a href="{{URL::route('account.edit', array('id'=>$row['ad_id']))}}">
                          {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}}
                          </a>  
                          <?php  if ($row['ad_id']!=1) { ?>
                            {{ Form::open(array('method' => 'DELETE','id'=>'myForm'.$row['ad_id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('account.destroy', $row['ad_id']))) }}
                            {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$row['ad_id'].'").submit()')) }}
                            {{ Form::close() }}
                            <?php  } ?></td>
                        </tr>
                        @endforeach
                      </tbody>
                  </table>
                  {{ $query_data->links() }}                                  
              </section>
              </div>
              </div>
              </section>
              </div>
              </div>
               <!-- Modal -->
                          <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                  <div class="modal-dialog">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                            {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                                              <h4 class="modal-title">Change Password:</h4>
                                          </div>
                                          <div class="modal-body">
                                            {{ Form::open(array('before' => 'csrf' ,'url'=>route('account/changePass'), 'id'=>'frmid1', 'files'=>true, 'method' => 'post')) }} 
                                             <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                               <tbody>
                                                 <Input type="hidden" name="hidden_id" id="hidden_id">
                                                 <tr><th>Password:</th><td>{{ Form::text('newpass','', array('class' => 'form-control','id'=>'newpass')) }}</td></tr>
                                                 <tr><th>Re-Password:</th><td>{{ Form::text('repass','', array('class' => 'form-control','id'=>'repass')) }}</td></tr>
                                               </tbody>
                                              </table>
                                            {{ Form::close() }}  
                                          </div>
                                          <div class="btn-group" style="padding:20px;">
                                          {{Form::button('Save', array('class' => 'btn btn-success','data-dismiss'=>'modal'))}}
                                          {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                                      </div>
                                  </div>
                              </div>
                          </div>
                        <!-- modal -->
              <!-- page end-->
    <script type="text/javascript">
      $('.default-date-picker').datepicker({
          format: 'yyyy-mm-dd',
          minDate: new Date()
      });
      $('button[name=chnage_pas_id]').click(function(){
        var id = $(this).attr('id');
        $('#hidden_id').val(id);
      });
    </script>
@stop