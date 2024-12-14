@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$sdate = "";
$edate = "";
?>
 <header class="panel-heading">
                 Vendors Management
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
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

 {{ Form::open(array('before' => 'csrf' ,'action' => 'VendorController@index', 'files'=>true, 'method' => 'post', 'id' => 'customer_filters_form')) }}
                 <div style="margin:10px; color:red; cursor:pointer;" id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
                  <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                            <tbody>
                              <tr>
                                  <td data-title="Start Date:">
                                    {{Form::label('SDate', 'Start Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                                  </td><td data-title="End Date:">
                                    {{Form::label('EDate', 'End Date:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                                  </td>                                  
                                 
                                  <td data-title="Filter:">
                                    {{Form::label('fliter', 'Filter:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{Form::select('Filter', array(''=>'Select Filter','name' => 'Vendor Name', 'ven_type' => 'Subcontractor'), null, ['id' => 'Filter','onchange'=>'set_option(this.value);', 'class'=>'form-control m-bot15'])}}
                                  </td>
                                  <td data-title="Filter Value:">
                                    {{Form::label('filterValue', 'Filter Value:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;'))}}
                                    {{ Form::text('FVal','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'filterValue')) }}
                                    {{Form::select('status', array('A' => 'Active Members', 'B' => 'Inactive Members'), null, ['id' => 'status','style'=>"display:none", 'class'=>'form-control m-bot15'])}}
                                  </td>
                                </tr>
                             
                                <tr>
                                <td colspan="8">
                                  <span class="smallblack"><strong>Note:</strong> 
                                     </span><br/>
                                     <span>
                                          1.Please leave empty the End date if you want to search for a perticular date.
                                     </span><br>
                                      <span>
                                         2.Please leave empty the Start date if you want to search from very start to End Date
                                      </span>
                                  
                                </td>                                
                              </tbody>
                          </table>
                                    <br/>
                                  {{Form::submit('Submit', array('class' => 'btn btn-info submit-form', 'style'=>'margin-top:-15px;'))}}
                                  {{Form::reset('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;', 'id'=>'reset_search_form'))}} 
                      </section>
                               {{ Form::close() }}
                               
<div class="panel-body">
              <div class="adv-table">
              <section id="no-more-tables" >
     <table class="table table-bordered table-striped table-condensed cf" style="text-align:center;">
                                      <thead class="cf">

                <tr>
                    <th style="text-align:center;">ID#</th>
                    <th style="text-align:center;">Name</th>
                    <th style="text-align:center;">Login</th>
                    <th style="text-align:center;">Email Address</th>
                    <th style="text-align:center;">Phone</th>
                    <th style="text-align:center;">Type</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:center;">Action</th>
                </tr>
                                      </thead>
                                      <tbody>
                @foreach($query_data as $data)
                <tr>
                  <td data-title="#ID:">{{ $data['id'] }}</td>
                  <td data-title="Job#:">{{($data['name'] != "")? strtoupper($data['name']): "-"}}</td>
                  <td data-title="County:">{{($data['login'] != "")? $data['login']: ""}}</td>
                  <td data-title="Contract:">{{($data['email_add'] != "")? $data['email_add'] :"-"}}</td>
                  <td data-title="Job Regarding:">{{($data['phone_no'] != "")? $data['phone_no'] : "-"}}</td>
                  <td data-title="hours:">
                      <?php
                        if($data['ven_type'] =="S"){
                            $customer_type = "<strong><font color='blue'>Subcontractor</font></strong>";
                        } else {
                            $customer_type = "Vendor";
                        }
                        
                        echo $customer_type;
                      ?>
                      
                      
                  </td>
                  
                  <td>
                      {{CustomerController::$UserStatus[$data['status']]}}
                  </td>
                  <td data-title="Action:">
                  @if($data['ven_type'] =="S")
                    <a data-toggle="modal" style="display:inline;" href="#myModal4" class="get-perm-html" id="{{$data['id']}}">
                    {{Form::button('<i class="fa fa-lock"></i>', array('class' => 'btn btn-success btn-xs'))}} 
                    </a>
                  @endif
                  <a data-toggle="modal" style="display:inline;" href="{{URL::route('vendors.edit', array('id'=>$data['id']))}}">
                  {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs'))}} 
                  </a>                                        
                  {{ Form::open(array('method' => 'DELETE', 'id'=>'myForm'.$data['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('vendors.destroy', $data['id']))) }}
                    {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$data['id'].'").submit()')) }}
                  {{ Form::close() }}
                  </td>
                </tr>
                @endforeach
                                      </tbody>
                                  </table>
                                  {{ $query_data->links() }}
              </section>
              </div>
              </div>

          <!-- Modal# -->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">::National Account Permissions.::</h4>
                      </div>
                    <div class="modal-body">
                        <p></p>
                </div>
              </div>
            </div>
        </div>
        <!-- modal# end--> 
          <?php 
            $uriSegment = Request::segment(2);
            if ($uriSegment == 'index') { ?>
             <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
             <script src="{{asset('js/common-scripts.js')}}"></script> 
        <?php } ?>
        <script type="text/javascript">
       
        $('.default-date-picker').datepicker({
              format: 'yyyy-mm-dd'
        });
        
        $("section[mysection=hide_n_show]").hide();
        $('#togglerButton').click(function(){
           $("section[mysection=hide_n_show]").toggle("slow");
           if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
              $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
           else 
              $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
        }); 
      
        $('.submit-form').click(function(){ 
            $('#customer_filters_form').submit();
        });
      
        $('.get-perm-html').click(function(){
    	
        var id = this.id;
        //console.log(id);
        //return false;
        $.ajax({
                type:'GET',          
                url: "{{URL('ajax/getAjaxPermissionHTML')}}",
                        data: {
                          'id' : id,
                        },
                        success: function (data) {
                         	$('#myModal4 .modal-body p').html(data);
				//$('#myModal').modal();
				$('#myModal4').modal('show');
//				 $('.default-date-picker').datepicker({
//			            format: 'mm/dd/yyyy'
//			        });
                      },
            });

    	});     
   function set_option(val){
        if(val == 'ven_type'){
            $('#filterValue').hide();
            $('#status').show();
            
        } else {
            $('#filterValue').show();
            $('#status').hide();
        }
    }
</script>
@stop

              
