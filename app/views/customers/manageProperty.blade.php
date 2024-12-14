@extends("layouts/dashboard_master")
@section('content')
 
@stop
@section('dashboard_panels')
    <!-- page start--> 
<?
    $action = "add";  
    $breadCrumb = "MANAGE LOCATION AREA & ASSET";
    if(isset($GpgCustomerData) && !empty($GpgCustomerData)){
         $action = "update";
         $breadCrumb = "MANAGE LOCATION AREA & ASSET";
    }
?>

    <style>
        .property-input{
         
          float:left;
          display:inline-block;
          padding-right:5px;
            
        }
        .property-input-icons{
            padding:5px 0px 0px 10px !important;
           
        }
    </style>
    <div class="row">
      <div class="col-sm-12">
    <section class="panel">
    <header class="panel-heading">
     
        {{$breadCrumb}}
      
    </header>
    <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->
    <section class="panel">
        <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
            <i>  Fixing Details: </i>
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
            
        @if($action == 'add')
            {{ Form::open(array('before' => 'csrf' ,'url'=>route('customers.create'), 'id'=>'customerForm', 'files'=>true, 'method' => 'post')) }}
        @elseif($action == 'update')
            {{ Form::open(array('before' => 'csrf' ,'url'=>URL::route('customers.update', array('id'=>$GpgCustomerData->id)), 'id'=>'customerForm', 'files'=>true, 'method' => 'put')) }}
        @endif
        
          <section id="no-more-tables" style="padding:10px;">
          <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
            <tbody>
                <tr>
                    <td colspan="3">
                      <label for="prop_manager">Select Property Manager</label>  
                      {{Form::select('propertyManager', $managersArray, @$GpgCustomerData->status, ['id' => 'propertyManager','onchange' => 'get_data(\'location\',this.value,\'"\'); return false;', 'class'=>'form-control l-bot6'])}}
                    </td>    
                </tr>
                
                <tr>
                    <td colspan="3">
                        <label for="prop_manager">Manage Location Area Assert</label>   
                            
                    </td>    
                </tr>
                
                <tr>
                    <td>
                        <strong>Location</strong>
                    </td>
                    <td>
                        <strong>Area</strong>
                    </td>
                    <td>
                        <strong>Asset</strong>
                    </td>
                </tr>
                
                <tr>
                    <td>
                        <div>
                            <div class="property-input" >
                                <input type="text" id="locationtxt" name="locationtxt" class="form-control">&nbsp;&nbsp;
                            </div>                             
                            
                            <div class="property-input-icons" >
                                
                                <a id="locationBtnSave" href="javascript:void(0);" onclick="con_to('location');">
                                    <button class="btn btn-success btn-xs" type="button">
                                        <i class="fa fa-plus-square"></i>
                                    </button>
                                </a>

                                <a id="locationBtnDelete" href="javascript:void(0);" onclick="del_data('location')">
                                    <button class="btn btn-danger btn-xs" type="button">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </a>    
                                
                                <a id="locationBtnUpdate" href="javascript:void(0);" onclick="update_data('location');" style="display:none">
                                    <button class="btn btn-success btn-xs" type="button">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </a>
                                
                                
                                <a id="locationBtnEdit" href="javascript:void(0);" onclick="pre_edit('location');">
                                    <button class="btn btn-warning btn-xs" type="button">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </a>

                                <a id="locationBtnCancel" href="javascript:void(0);" onclick="cancel_edit('location')" style="display:none">
                                    <button class="btn btn-danger btn-xs" type="button">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                </a>    
                                <input type="hidden" name="locationEditId" id="locationEditId" />
                            </div>
                        </div>
                        
                        
                        <select onchange="get_data('area',this.value,'')" id="locationList" size="10" name="locationList" class="form-control">
                            
                        </select>
                    </td>
                    <td>
                        
                        <div>
                            <div class="property-input" >
                                <input type="text" id="areatxt" name="areatxt" class="form-control">&nbsp;&nbsp;
                            </div>                             
                            
                            <div class="property-input-icons" >
                                
                                <a id="areaBtnSave" href="javascript:void(0);" onclick="con_to('area');">
                                    <button class="btn btn-success btn-xs" type="button">
                                        <i class="fa fa-plus-square"></i>
                                    </button>
                                </a>

                                <a id="areaBtnDelete" href="javascript:void(0);" onclick="del_data('area')">
                                    <button class="btn btn-danger btn-xs" type="button">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </a>    
                                
                                <a id="areaBtnUpdate" href="javascript:void(0);" onclick="update_data('area');" style="display:none">
                                    <button class="btn btn-success btn-xs" type="button">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </a>
                                
                                
                                <a id="areaBtnEdit" href="javascript:void(0);" onclick="pre_edit('area');">
                                    <button class="btn btn-warning btn-xs" type="button">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </a>

                                <a id="areaBtnCancel" href="javascript:void(0);" onclick="cancel_edit('area')" style="display:none">
                                    <button class="btn btn-danger btn-xs" type="button">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                </a>    
                                <input type="hidden" name="areaEditId" id="areaEditId" />
                            </div>
                        </div>
                        
                        <select onchange="get_data('asset',this.value,'')" id="areaList" size="10" name="areaList" class="form-control">
                            
                        </select>
                        
                    </td>
                    
                    <td>
                        
                        <div>
                            <div class="property-input" >
                                <input type="text" id="assettxt" name="assettxt" class="form-control">&nbsp;&nbsp;
                            </div>                             
                            
                            <div class="property-input-icons" >
                                
                                <a id="assetBtnSave" href="javascript:void(0);" onclick="con_to('asset');">
                                    <button class="btn btn-success btn-xs" type="button">
                                        <i class="fa fa-plus-square"></i>
                                    </button>
                                </a>

                                <a id="assetBtnDelete" href="javascript:void(0);" onclick="del_data('asset')">
                                    <button class="btn btn-danger btn-xs" type="button">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </a>    
                                
                                <a id="assetBtnUpdate" href="javascript:void(0);" onclick="update_data('asset');" style="display:none">
                                    <button class="btn btn-success btn-xs" type="button">
                                        <i class="fa fa-save"></i>
                                    </button>
                                </a>
                                
                                
                                <a id="assetBtnEdit" href="javascript:void(0);" onclick="pre_edit('asset');">
                                    <button class="btn btn-warning btn-xs" type="button">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                </a>

                                <a id="assetBtnCancel" href="javascript:void(0);" onclick="cancel_edit('asset')" style="display:none">
                                    <button class="btn btn-danger btn-xs" type="button">
                                        <i class="fa fa-ban"></i>
                                    </button>
                                </a>    
                                <input type="hidden" name="assetEditId" id="assetEditId" />
                            </div>
                            
                        </div>
                        
                        <select id="assetList" size="10" name="assetList" class="form-control">
                            
                        </select>
                        
                    </td>
                        
                </tr>
                
              <br/>
              <input type="hidden" name="_token" id="_token" value="<?php echo csrf_token(); ?>">
            </section>
        {{ Form::close() }}
    </section>
<!-- ////////////////////////////////////////// -->
 </section>
 </div>
 </div>
 <!-- page end-->

      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
      <script src="{{asset('js/common-scripts.js')}}"></script>

      <script language="javascript">
        var adPath = '';
        var comp = new Array;
        comp[0] = 'locationList';
        comp[1] = 'areaList';
        comp[2] = 'assetList';

        function showD() {
            DG("loadingDIV").style.width = screen.width + 'px';
                DG("loadingDIV").style.height = (screen.height-200) + 'px';
                DG("loadingDIV").style.top = 0;
                DG("loadingDIV").style.left = 0;
                DG("loadingDIV").style.display="block";
                DG("loadingFRM").style.width = screen.width + 'px';
                DG("loadingFRM").style.height = (screen.height-200) + 'px';
                DG("loadingFRM").style.top = 0;
                DG("loadingFRM").style.left = 0;
                DG("loadingFRM").style.display="block";
        }
        function hideD() {
            DG("loadingDIV").style.display = "none";
                DG("loadingFRM").style.display="none";
        }
        function Validate_country(){
        var frm = document.getElementById('frmNewUser');
                        var cname = Trim(frm.cname.value);
                        var error_str = '';
                        if (cname.length < 1)
                        {
                                error_str+="Error : Category Name is required<br>";
                        }
                        if (error_str) {
                                 document.getElementById("ERR_DISP").style.display = "block";
                                 document.getElementById("Error_Label").innerHTML = error_str;
                                 return false;
                        } 
                return true;
                }

        function get_val() {
          if (http.readyState==4) {
            var filecont = http.responseText;
                 var ptr = /~#~/;
                 sp1 = filecont.split(ptr);
                 DG(sp1[3]+'txt').value = '';
                 if (sp1[2]=="FOUND") { 
                   DG(sp1[3]+'List').value = sp1[0];
                 } else {
                    var optn = document.createElement ("OPTION");
                optn.text = sp1[1];
                optn.value = sp1[0];
                DG(sp1[3]+'List').options[DG(sp1[3]+'List').length] = optn;
                 }
                hideD();   
          } else {
             showD();   
          } 
        }
        
        function con_to(type) {
            if($('#propertyManager').val() == "") {
                alert('Please Select Property Manger'); return false;
            }
        
           var regEx = new RegExp ('[+]', 'gi');
           var token = $('#_token').val();
           
                 
            if(type == 'location'){
                data = $('#locationtxt').val();
                appendElement = 'locationList';
                Id = $('#propertyManager');
                $('#locationtxt').val('')
                
            } else if(type == 'area'){
                 data = $('#areatxt').val();
                 appendElement = 'areaList';
                 Id = $('#locationList');
                 $('#areatxt').val('')
                 
            } else if(type == 'asset'){
                data = $('#assettxt').val();
                appendElement = 'assetList';
                Id = $('#areaList');
                $('#assettxt').val('');
            }
            
        if (Id.val() !="") {
                
            
            if (data!='') {
                 
                $.ajax({
                        type:'POST',          
                        url: "{{URL('ajax/setAjaxLocationAreaAsset')}}",
                            data: {
                              'type' : type,
                              'Id' : Id.val(),
                              'data' : escape(data).replace("%20"," "),
                              '_token' :token, 
                            },
                            success: function (data) {
                                
                                cleaned_data = data.replace("%20"," ");
                                $('#'+appendElement).append(cleaned_data);
                                
                          },
                     });
                        
                } else {
                  alert("Please Add Some Text");
                }	
                
            } else {
               alert("Please Select a " + Id.attr('name'));
           }   	  
        }

        function get_list_data() {
          var i,j,fg=0;
          if (http.readyState==4) {
             var filecont = http.responseText;
                 filecont+=' ';
                 var typ = filecont.split(/###/);	 
                 if (typ[2]){
                        typ[2] = trimStr(typ[2])
                        if (typ[2]=='del_q_0') alert('Unable to Delete! Internal Error');
                        if (typ[2]=='del_q_1') alert('Deleted Successfully');
                        if (typ[2]=='del_p_0') alert('Unable to Delete! This property is in use.');
                 }
                 var ptr = /~#~/;
                 sp1 = typ[1].split(ptr);
                 for (j=0; j<comp.length; j++) {
                   if (fg==1 || (typ[0]+'List')==comp[j] ) {
                     DG(comp[j]).length = 0;
                     fg = 1;
                   }
                 }	 
                 DG(typ[0]+'txt').value = '';
                 for (i=0; i<sp1.length; i++) {
                    if (sp1[i]!= ' ') {
                        var optn = document.createElement ("OPTION");
                        sp11 = sp1[i].split(/~~/);
                optn.text = sp11[1];
                        optn.value = sp11[0];        
                        DG(typ[0]+'List').options[DG(typ[0]+'List').length] = optn;
                        }
                 }	
                cancel_edit('location'); 
                cancel_edit('area'); 
                cancel_edit('asset'); 
                hideD();   
          } else {
           showD();
          } 
        }
        
        function del_data(type){
            
            var token = $('#_token').val();
             if($('#propertyManager').val() == "") {
                alert('Please Select Property Manger'); return false;
            }
        
            if(type == 'location'){
               Id = $('#propertyManager');
               delmsg = "DELETING LOCATION will also DELETE the AREAS and ASSETS in it.\n Do you want to continue...?";
            } else if(type == 'area'){
                 Id = $('#locationList');
                 delmsg = "DELETING AREA will also DELETE the ASSETS in it.\n Do you want to continue...?";
            } else if(type == 'asset'){
                Id = $('#areaList');
                delmsg = "It will DELETE the Selected ASSET.\n Do you want to continue...?";
            }
            
            var fg = $('#'+type + 'List option:selected').val();
            
            if (typeof ($('#'+type + 'List option:selected').val()) !== "undefined" ) {
                var cnfrm = confirm(delmsg);
                
                if(cnfrm){ // del
                   $.ajax({
                        type:'POST',          
                        url: "{{URL('ajax/deleteAjaxAreaAsset')}}",
                            data: {
                              'type' : type,
                              'Id' : Id.val(),
                              'fg' : fg,
                              '_token' :token, 
                            },
                            success: function (data) {
                               console.log(data);
                                if(type == 'location'){
                                    $('#'+type+'List option[value="' + fg + '"]').remove();
                                    $('#areaList').html('');
                                    $('#assetList').html('');
                                    
                                } else if(type == 'area'){
                                    $('#'+type+'List option[value="' + fg + '"]').remove();
                                    $('#assetList').html('');
                                    
                                } else if(type == 'asset'){
                                     $('#'+type+'List option[value="' + fg + '"]').remove();
                                }

                          },
                    }); 
                }
                
            } else {
                 alert('Please Select Some ' + type.toUpperCase());
            };
        }
        function get_data(type,Id,fg) {
        
            var token = $('#_token').val();
            if (Id) {
            
                $.ajax({
                    type:'POST',          
                    url: "{{URL('ajax/getAjaxAreaAsset')}}",
                        data: {
                          'type' : type,
                          'Id' : Id,
                          'fg' : fg,
                          '_token' :token, 
                        },
                        success: function (data) {
                            data = data.replace("%20"," ")
                            if(type == 'location'){
                                $('#locationList').html('');
                                $('#areaList').html('');
                                $('#assetList').html('');
                                $('#locationList').html(data);
                            } else if(type == 'area'){
                                $('#areaList').html(''); 
                                $('#assetList').html('');
                                $('#areaList').html(data);
                            } else if(type == 'asset'){
                                $('#assetList').html('');                    
                                $('#assetList').html(data);
                            }
                            
                      },
                });
            
            } else {
                $('#locationList').html('');
                $('#areaList').html('');
                $('#assetList').html('');
                alert("Please Select a Property Manager");
                
            }   	  
        }
        
        
        function update_data(type) {
           
            var dataUpdate = $('#'+type+'txt').val();
            var updateId = $('#'+type+'EditId').val();
            var token = $('#_token').val();
           
            if(type == 'location'){
               Id = $('#propertyManager');
            } else if(type == 'area'){
                 Id = $('#locationList');
            } else if(type == 'asset'){
                Id = $('#areaList');
            }
            
            if (dataUpdate!='') {
                
                $.ajax({
                    type:'POST',          
                    url: "{{URL('ajax/setAjaxLocationAreaAsset')}}",
                        data: {
                          'type' : type,
                          'Id' : Id.val(),
                          'updateId': updateId,
                          'data' : escape(dataUpdate).replace("%20"," "),
                          '_token' :token, 
                        },
                        success: function (data) {
                            
                            $('#'+type+'List option[value="' + updateId + '"]').text(dataUpdate.replace("%20"," "));
                            $('#'+type+'txt').val('');
                            
                            $('#'+type + 'EditId').val('');
                            
                            $('#'+type + 'BtnDelete').show();
                            $('#'+type + 'BtnSave').show();
                            $('#'+type + 'BtnEdit').show();
                            
                            $('#'+type + 'BtnCancel').hide();
                            $('#'+type + 'BtnUpdate').hide();
                            
                            

                      },
                 });
            } else{
                alert('Please Add Some Text');
          }
        
        }
        
        function pre_edit(type) {
        //alert($('#'+type + 'List').val());return false;  
        if($('#propertyManager').val() == "") {
            alert('Please Select Property Manger'); return false;
        }
        if ($('#'+type + 'List option:selected').text()!='') {
                         //DG(type + 'EditDiv').style.display = "none";
                         //DG(type + 'SaveDiv').style.display = "inline";"#myselect option:selected"
                         $('#'+type + 'BtnDelete').hide();
                         $('#'+type + 'BtnSave').hide();
                         $('#'+type + 'BtnEdit').hide();
                         
                         $('#'+type + 'txt').val($('#'+type + 'List option:selected').text()); 
                         $('#'+type + 'EditId').val($('#'+type + 'List option:selected').val()) 
                         $('#'+type + 'BtnUpdate').show();
                         $('#'+type + 'BtnCancel').show();
                         
             } else {
                        alert('Please Select Some ' + type.toUpperCase());
                 }
        }

        function cancel_edit(type) {
            
            $('#'+type + 'BtnUpdate').hide();
            $('#'+type + 'BtnEdit').show();
            $('#'+type + 'txt').val('');   
            $('#'+type + 'EditId').val('');
            $('#'+type + 'BtnDelete').show();
            $('#'+type + 'BtnSave').show();
            $('#'+type + 'BtnCancel').hide();
        } 
    </script>
@stop