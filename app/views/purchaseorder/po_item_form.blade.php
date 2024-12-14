<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Mosaddek">
    <meta name="keyword" content="FlatLab, Dashboard, Bootstrap, Admin, Template, Theme, Responsive, Fluid, Retina">
    <link rel="shortcut icon" href="img/favicon.png">

    <title>Global Power Group</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/bootstrap-reset.css')}}" rel="stylesheet">
    <!--external css-->
    <link rel="stylesheet" href="{{ asset('css/table-responsive.css') }}">
    <link href="{{asset('assets/font-awesome/css/font-awesome.css')}}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-datepicker/css/datepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/bootstrap-timepicker/compiled/timepicker.css') }}" />
    <!--<link href="css/navbar-fixed-top.css" rel="stylesheet">-->

    <!-- Custom styles for this template -->
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
    <link href="{{asset('css/style-responsive.css')}}" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
    <script src="{{asset('js/jquery.js')}}"></script>
    <script src="{{ asset('js/jquery-ui-1.9.2.custom.min.js') }}"></script>
    <style>
div#change_width{
      width: 900px;
}
@media (max-width: 800px){
  div#change_width{
      width: 100%;
    }
}
.ui-autocomplete {
    width: 300px;
    margin-top:-15000px;
    background-color:red;
}  
</style>
  </head>
  <body class="full-width">

  <section id="container" class="">
      <!--header start-->
      <header class="header white-bg">
          <div class="navbar-header" style="display:inline;">
              <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                  <span class="fa fa-bars"></span>
              </button>

              <!--logo start-->
               <div class="col-lg-6">
                  <section class="panel">
                  {{ HTML::image(asset('img/gpglogo.jpg'), 'GPG Logo', array('style' => 'display:inline; width:100px; height:70px;')) }}
               </section>
                </div>
              <!--logo end-->
            <div class="col-lg-6">
              <div class="top-nav" style="display:inline;">
              <ul class="nav pull-right top-menu">
                <li class="dropdown">
                      <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                          <img alt="" src="{{asset('img/avatar1_small.jpg')}}">
                          <span class="username"><?php echo Auth::user()->fname." ".Auth::user()->lname?></span>
                          <b class="caret"></b>
                      </a>
                      <ul class="dropdown-menu extended logout">
                          <div class="log-arrow-up"></div>
                          <li><a href="logout"><i class="fa fa-key"></i> Log Out</a></li>
                      </ul>
                  </li>
            </ul>
          </div>
        </div>

          </div>

      </header>
      <!--header end-->
      <!--sidebar start-->

      <!--sidebar end-->
      <!--main content start-->
     <br/><br/><br/><br/><br/><br/>
       {{Form::open(array('method' => 'POST','id'=>'update_po_items','files'=>true,'route' => array('purchaseorder/po_item_form','id'=>$po_id)))}} 
     <section id="main-content">
      <section id="wrapper">
      {{ Form::hidden('po_id',$po_id)}}
                <div class="row">
                  <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Vendor Billing Address 
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">Vendor:</td><td>{{Form::select('vendorId',array(''=>'Select')+$vendors,isset($poTblRow['GPG_vendor_id'])?$poTblRow['GPG_vendor_id']:'', ['class'=>'form-control','id'=>'vendorId'])}}</td><td style="background-color:#FFFFCC;">PO Note:</td><td>{{ Form::text('POnote',isset($poTblRow['po_note'])?$poTblRow['po_note']:'', array('class' => 'form-control', 'id' => 'POnote')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Address:</td><td>{{ Form::text('venAddress','', array('class' => 'form-control', 'id' => 'venAddress')) }}</td><td style="background-color:#FFFFCC;">PO Estimated Receipt Date:</td><td>{{ Form::text('poEstRecptDate',isset($poTblRow['po_est_recpt_date'])?$poTblRow['po_est_recpt_date']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'poEstRecptDate')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">City:</td><td>{{ Form::text('venCity','', array('class' => 'form-control', 'id' => 'venCity')) }}</td><td style="background-color:#FFFFCC;">State:</td><td>{{ Form::text('venState','', array('class' => 'form-control', 'id' => 'venState')) }}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Zip:</td><td>{{ Form::text('venZip','', array('class' => 'form-control', 'id' => 'venZip')) }}</td><td style="background-color:#FFFFCC;">Phone:</td><td>{{ Form::text('venPhone','', array('class' => 'form-control', 'id' => 'venPhone')) }}</td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>        
                          </div>
                        </div>
                      </section>
                    </div>
                     <div class="col-lg-6">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div class="panel-heading">
                                  <h4 class="panel-title">
                                      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion">
                                         Purchase Order Detail 
                                      </a>
                                  </h4>
                              </div>
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <tbody class="cf">
                                        <tr><td style="background-color:#FFFFCC;">PO Number:</td><td colspan="3">{{Form::text('po_number',($po_id=='0')?'New PO':$po_id, ['class'=>'form-control','id'=>'po_number','readOnly'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Date:</td><td>{{ Form::text('poDate',isset($poTblRow['po_date'])?$poTblRow['po_date']:'', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'poDate','readOnly')) }}</td><td style="background-color:#FFFFCC;">Form Of Payment:</td><td>{{Form::select('POpayForm',$payTypeArray,isset($poTblRow['payment_form'])?$poTblRow['payment_form']:'', ['class'=>'form-control','id'=>'POpayForm'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Requested By:</td><td>{{Form::select('POreqBy',array(''=>'Select')+$emps,isset($poTblRow['request_by_id'])?$poTblRow['request_by_id']:'', ['class'=>'form-control','id'=>'POreqBy'])}}</td><td style="background-color:#FFFFCC;">PO Writer:</td><td>{{Form::select('POwriter',array(''=>'Select')+$emps,isset($poTblRow['po_writer_id'])?$poTblRow['po_writer_id']:'', ['class'=>'form-control','id'=>'POwriter'])}}</td></tr>
                                        <tr><td style="background-color:#FFFFCC;">Quoted Amount For PO:</td><td>{{Form::text('POquoteAmt',isset($poTblRow['po_quoted_amount'])?number_format($poTblRow['po_quoted_amount'],2):'', ['class'=>'form-control','id'=>'POquoteAmt'])}}</td><td style="background-color:#FFFFCC;">Sales/Order #:</td><td>{{Form::text('POsalesOrd',isset($poTblRow['sales_order_number'])?$poTblRow['sales_order_number']:'', ['class'=>'form-control','id'=>'POsalesOrd'])}}</td></tr>
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                        </div>
                      </section>
                    </div>
                  </div>  

              <div class="row">
                  <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf" id="myTable">
                                        <thead class="cf">
                                        <tr><th>{{Form::button('<i class="fa fa-plus-circle"></i>', array('class' => 'btn btn-success btn-xs','id'=>'add_new_row'))}}&nbsp;&nbsp;Del/Edit</th><th>Job#</th><th>GL Code</th><th>Description</th><th>Qty (Zero(0) Qty is Ignored)</th><th>Rate</th><th>Amount</th><th>Rcv'd?</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                        <?php $i=0;
                                        ?>
                                          @foreach($getPORows as $getPORow)
                                            <tr>
                                              <td>
                                                
                                                {{Form::button('<i class="fa fa-pencil"></i>', array('class' => 'btn btn-primary btn-xs','name'=>'edit_porow','id'=>$i))}}
                                                {{ Form::open(array('method' => 'post','id'=>'theForm'.$getPORow['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('purchaseorder/delPORow', $getPORow['id']))) }}
                                                {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("theForm'.$getPORow['id'].'").submit()')) }}
                                                {{Form::close()}}
                                              </td>
                                              <td>{{ Form::text('POjobNum_'.$i,$getPORow['job_num'], array('class' => 'form-control', 'id' => 'POjobNum_'.$i,'readOnly'))}}</td>
                                              <td>{{Form::select('POglCode_'.$i,array(''=>'Select')+$gcodeArr,$getPORow['glCode'], ['class'=>'form-control','id'=>'POglCode_'.$i,'disabled'])}}</td>
                                              <td>{{ Form::text('PODesc_'.$i,$getPORow['description'], array('class' => 'form-control', 'id' => 'PODesc_'.$i,'readOnly'))}}</td>
                                              <td>{{ Form::text('POQty_'.$i,$getPORow['quantity'], array('class' => 'form-control', 'id' => 'POQty_'.$i,'readOnly'))}}</td>
                                              <td>{{ Form::text('PORate_'.$i,number_format($getPORow['rate'],2), array('class' => 'form-control', 'id' => 'PORate_'.$i,'readOnly'))}}</td>
                                              <td>{{ Form::text('POAmount_'.$i,number_format($getPORow['amount'],2), array('class' => 'form-control', 'id' => 'POAmount_'.$i,'readOnly'))}}</td>
                                              <td>{{ Form::checkbox('PORcvd_'.$i, 1, $getPORow['po_received'], ['class' => 'field','id'=>'PORcvd_'.$i,'disabled']) }}</td>
                                            </tr>
                                            <?php $i++;?>
                                          @endforeach
                                        {{Form::hidden('poCounter',$i,['id'=>'poCounter'])}}
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </section>
                  </div>
                    <?php
                      $str_options = ''; 
                      foreach ($gcodeArr as $key => $value) {
                          $str_options .='<option value='.$key.'>'.$value.'</option>'; 
                      }?>
              <div class="col-lg-12">
                    <section class="panel">
                      <div class="panel-group m-bot20" id="accordion">
                          <div class="panel panel-default">
                              <div id="collapseOne" class="panel-collapse collapse in">
                                  <div class="panel-body">
                                   <section id="no-more-tables">
                                     <table class="table table-bordered table-striped table-condensed cf">
                                        <thead class="cf">
                                          <tr><th> DEL</th><th>Vendor Invoice#</th><th>Date </th><th > Amount</th></tr>
                                        </thead>
                                        <tbody class="cf">
                                          <?php $j=0;?>
                                          @foreach($getPOHistRows as $getPOHistRow)
                                            <tr>
                                              <td>
                                                {{ Form::open(array('method' => 'post','id'=>'myForm'.$getPOHistRow['id'].'','style'=>'display:inline; margin:0px; padding:0px;', 'route' => array('purchaseorder/delPOHistRow', $getPOHistRow['id']))) }}
                                                {{ Form::button('<i class="fa fa-trash-o"></i>', array('style'=>'display:inline;','class' => 'btn btn-danger btn-xs','onclick'=>'if(confirm("Are you sure you want to delete this..."))document.getElementById("myForm'.$getPOHistRow['id'].'").submit()')) }}
                                                {{ Form::close() }}
                                              </td>
                                              <td>{{$getPOHistRow['vendor_invoice_number']}}</td>
                                              <td>{{date('m/d/Y',strtotime($getPOHistRow['date']))}}</td>
                                              <td>{{'$'.number_format($getPOHistRow['amount'],2)}}</td>
                                            </tr>
                                          @endforeach
                                        </tbody>
                                     </table>
                                   </section>
                                  </div>
                              </div>
                          </div>
                      </div>
                    </section>
                  </div>
              </div>       
    </section>
  </section>
    <div class="btn-group" style="padding:20px;">
      {{Form::button('Save/Update Changes', array('class' => 'btn btn-primary', 'id'=>'submit_main_form'))}}
      {{HTML::link('#myModal4', 'Manage Files' , array('class' => 'btn btn-success','data-toggle'=>'modal','name'=>'manage_files','id'=>$po_id))}}
      {{ HTML::link('purchaseorder/getPOPdffile/'.$po_id.'', 'PDF ExPORT' , array('class'=>'btn btn-danger'))}}
      {{ HTML::link('purchaseorder/getPOPdfslip/'.$po_id.'', 'PACKING SLIP' , array('class'=>'btn btn-warning'))}}
    </div>
  {{Form::close()}}
</section>
          <!-- Modal4 -->
           <div class="modal fade" id="myModal4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    {{Form::button('&times;', array('class' => 'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
                      <h4 class="modal-title">ATTACHMENT MANAGEMENT</h4>
                      </div>
                    <div class="modal-body">
                 {{ Form::open(array('before' => 'csrf' ,'id'=>'submit_file_form','url'=>route('purchaseorder/managePOFiles'),'files'=>true, 'method' => 'post')) }}   {{Form::hidden('fjob_id','',array('id' => 'change_job_id' ))}}
                 <div class="form-group">
                                               <section id="no-more-tables"  style="padding:10px;">
                                               <table class="table table-bordered table-striped table-condensed cf">
                                                <thead class="cf">
                                                  <tr><th>#</th><th>Category Name </th><th>Action</th></tr>
                                                </thead>
                                                <tbody class="cf" id="display_quote_files">
                                                </tbody>
                                                </table>
                                              </section> 
                  <div style="display: inline;">
                   {{ Form::file('attachment', array('style'=>'float: left !important; display:inline !important; width:50%;' ,'id' => 'attachment')) }}
                          </div> </div>
                   {{Form::close()}}
                  <div class="btn-group" style="padding:20px;">
                    {{Form::button('Submit', array('class' => 'btn btn-success', 'id'=>'submit_attachments'))}}
                   {{Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
                  </div>
                </div>
              </div>
            </div>
        </div>
        <!-- modal4 end--> 
<!-- Modal Paste Here.... -->
  <!-- js placed at the end of the document so the pages load faster -->
  <script src="{{asset('js/bootstrap.min.js')}}"></script>
  <script class="include" type="text/javascript" src="{{asset('js/jquery.dcjqaccordion.2.7.js')}}"></script>
  <script type="text/javascript" src="{{asset('js/hover-dropdown.js')}}"></script>
  <script src="{{asset('js/jquery.scrollTo.min.js')}}"></script>
  <script src="{{asset('js/jquery.nicescroll.js')}}" type="text/javascript"></script>
  <script src="{{asset('js/respond.min.js')}}" ></script>
  <script src="{{ asset('assets/bootstrap-datepicker/js/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js') }}"></script>
  <script src="{{ asset('assets/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
  <script type="text/javascript" src="{{asset('js/form-component.js')}}"></script>
  <link href="{{asset('assets/advanced-datatable/media/css/demo_page.css')}}" rel="stylesheet" />
  <link href="{{asset('assets/advanced-datatable/media/css/demo_table.css')}}" rel="stylesheet" />
  <link rel="stylesheet" href="{{asset('assets/data-tables/DT_bootstrap.css')}}" />
  <script type="text/javascript" language="javascript" src="{{asset('assets/advanced-datatable/media/js/jquery.dataTables.js')}}"></script>
  <script type="text/javascript" src="{{asset('assets/data-tables/DT_bootstrap.js')}}"></script>
  <script type="text/javascript" src="{{ asset('js/dynamic_table_init.js') }}"></script>
  <!-- morrsi -->
  <link href="{{asset('assets/morris.js-0.4.3/morris.css')}}" rel="stylesheet" />
  <script src="{{asset('assets/morris.js-0.4.3/raphael-min.js')}}" type="text/javascript"></script>
  <script src="{{asset('js/morris.js')}}" type="text/javascript"></script>
  <!--right slidebar-->
  <script src="{{asset('js/slidebars.min.js')}}"></script>
  <!--common script for all pages-->
  <script src="{{asset('js/common-scripts.js')}}"></script>
  <script type="text/javascript">
    $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
        });
    $( document ).ready(function() {
      var id = $('#vendorId  option:selected').val();    
      $.ajax({
        url: "{{URL('ajax/getVendorInfo')}}",
          data: {
            'id' : id
          },
          success: function (data) {
            $('#venAddress').val(data.address);    
            $('#venCity').val(data.city);    
            $('#venState').val(data.state);    
            $('#venZip').val(data.zipcode);    
            $('#venPhone').val(data.phone_no);    
          },
        });
    });

     $('select[name=vendorId]').change(function(){
      var id = $(this).val();
      $.ajax({
        url: "{{URL('ajax/getVendorInfo')}}",
          data: {
            'id' : id
          },
          success: function (data) {
            $('#venAddress').val(data.address);    
            $('#venCity').val(data.city);    
            $('#venState').val(data.state);    
            $('#venZip').val(data.zipcode);    
            $('#venPhone').val(data.phone_no);    
          },
        });
     });

  var counter=parseInt('0')+parseInt($('#poCounter').val());
  $('#add_new_row').click(function(){
      var str = '<tr><td>-</td>';
          str += '<td><input type="text" value="" name="POjobNum_'+counter+'" id="POjobNum_'+counter+'" class="form-control"></td>';
          str += '<td><select name="POglCode_'+counter+'" id="POglCode_'+counter+'" class="form-control">{{$str_options}}</select></td>';
          str += '<td><input type="text" value="" name="PODesc_'+counter+'" id="PODesc_'+counter+'" class="form-control"></td>';
          str += '<td><input type="text" value="" name="POQty_'+counter+'" id="POQty_'+counter+'" class="form-control"></td>';
          str += '<td><input type="text" value="" name="PORate_'+counter+'" id="PORate_'+counter+'" class="form-control"></td>';
          str += '<td><input type="text" value="" name="POAmount_'+counter+'" id="POAmount_'+counter+'" class="form-control"></td>';
          str += '<td><input type="checkbox" value="1" name="PORcvd_'+counter+'" id="PORcvd_'+counter+'" class="field"></td></tr>';

      $('#myTable > tbody:last').append(str);
      $('#poCounter').val(parseInt($('#poCounter').val())+parseInt('1'));
      counter = parseInt(counter) + parseInt("1");
  });

  $('#submit_main_form').click(function(){
    $('#update_po_items').submit();
  });

$('a[name=manage_files]').click(function(){
        var job_id = $(this).attr('id');
        $('#change_job_id').val(job_id);
        $.ajax({
              url: "{{URL('ajax/getPOFiles')}}",
              data: {
                'id' : job_id
              },
            success: function (data) {
              $('#display_quote_files').html(data);
               $('a[name=del_quote_file]').click(function(){
                var result = confirm("Are you sure! you want to delete....?");
                if(result){
                  $.ajax({
                        url: "{{URL('ajax/deletePOFile')}}",
                        data: {
                          'id' : $(this).attr('id')
                        },
                        success: function (data) {
                          if (data == 1){     
                            alert("Deleted Successfully!");
                            location.reload();
                          }
                      },
                  });
                }  
               });
            },
        });
      });
     $('#submit_attachments').click(function(){
        $('#submit_file_form').submit();
      });

     $('button[name=edit_porow]').click(function(){
       var id = $(this).attr('id');
       document.getElementById('POjobNum_'+id).readOnly = false;
       document.getElementById('POglCode_'+id).disabled = false;
       document.getElementById('POQty_'+id).readOnly = false;
       document.getElementById('PORate_'+id).readOnly = false;
       document.getElementById('PODesc_'+id).readOnly = false;
       document.getElementById('POAmount_'+id).readOnly = false;
       document.getElementById('PORcvd_'+id).disabled = false;
     });

  </script>

  </body>
</html>
