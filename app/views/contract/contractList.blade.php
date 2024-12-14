@extends("layouts/dashboard_master")
@section('content')
<section>
  
</section>
@stop
@section('dashboard_panels')
<div class="row">
  <div class="col-sm-12">
    <section class="panel">
      <header class="panel-heading">
        CONTRACT LISTING <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a></span>
      </header>
    </section>
    <section class="panel">
      <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
        <b><i>CONTRACT LISTING AND MANAGEMENT</i></b>
      </header>
      <!-- search and filter form -->
      {{ Form::open(array('before'=>'csrf' ,'url'=>route('contract/contractList'), 'files'=>true, 'method'=>'post')) }}
      <div id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
      <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
        <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
          <tbody>
            <tr>
              <td data-title="Start Date Start:">
                {{ Form::label('SDate2', 'Start Date Start:', array('class' => 'control-label')) }}
                {{ Form::text('SDate2','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate2')) }}
              </td>
              <td data-title="Start Date End:">
                {{ Form::label('EDate2', 'Start Date End:', array('class' => 'control-label')) }}
                {{ Form::text('EDate2','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate2')) }}
              </td>
              <td data-title="Customer:">
                {{ Form::label('optCustomer', 'Customer:', array('class' => 'control-label')) }}
                {{ Form::select('optCustomer', ['' => 'ALL'] + $customersCombo, '', ['id' => 'optCustomer', 'class'=>'form-control m-bot15']) }}
              </td>
              <td data-title="Contract Status:">
                {{ Form::label('optStatus', 'Contract Status:', array('class' => 'control-label')) }}
                {{ Form::select('optStatus', [""=>"ALL","Quote"=>"Quote","Won"=>"Won"], '', ['id' => 'optStatus', 'class'=>'form-control m-bot15']) }}
              </td>
              <td data-title="Status Renwed:">
                {{ Form::label('optStatusRenewd', 'Status Renwed:', array('class' => 'control-label')) }}
                {{ Form::select('optStatusRenewd', ["0"=>"ALL","1"=>"Renewed Only"], '0', ['id' => 'optStatusRenewd', 'class'=>'form-control m-bot15']) }}
              </td>
            </tr>
            <tr>
              <td colspan="5">
                <span class="smallblack"><strong>Note:</strong> Leave blank for viewing records from all days. Fill start date only if want to see the records for a perticular date. Same note for all date fields given below.</span><br/>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{ Form::checkbox('ignoreCostDate','1','', array('id'=>'ignoreCostDate','class' => 'input-group','style'=>'display:inline;')) }}
                Ignore Date stamp  on Material Cost and Labor Cost.<br />
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                {{ Form::checkbox('ignoreInvoiceDate','1','', array('id'=>'ignoreInvoiceDate','class' => 'input-group','style'=>'display:inline;')) }}
                Ignore Date stamp  on Invoice Amount.
              </td>
              <tr>
                <tr>
                  <td data-title="Contract Number:">
                    {{ Form::label('optJobNumber', 'Contract Number:', array('class' => 'control-label')) }}
                    {{ Form::text('optJobNumber','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'optJobNumber')) }}
                  </td>
                  <td data-title="Sales Person:">
                    {{ Form::label('optEmployee', 'Sales Person:', array('class' => 'control-label')) }}
                    {{ Form::select('optEmployee', ['' => 'ALL'] + $employeesCombo, '', ['id' => 'optEmployee', 'class'=>'form-control m-bot15']) }}
                  </td>
                  <td data-title="Attached Contracts:">
                    {{ Form::label('haveAttachedContract', 'Attached Contracts:', array('class' => 'control-label')) }}
                    {{ Form::select('haveAttachedContract', ["0"=>"ALL", "1"=>"Having Attached Contracts","2"=>"Not Having Attached Contracts"], '0', ['id' => 'haveAttachedContract', 'class'=>'form-control m-bot15']) }}
                  </td>
                  <td data-title="Attached Contract Number:" colspan="2">
                    {{ Form::label('attachedContractNumber', 'Attached Contract Number:', array('class' => 'control-label', 'style'=>'white-space:nowrap; font-weight: bold;')) }}
                    {{ Form::text('attachedContractNumber','', array('class' => 'form-control', 'id' => 'attachedContractNumber')) }}&nbsp;(e.g. SA, GA1444 etc)
                  </td>
                </tr>
              </tbody>
            </table>
            <br/>
            {{ Form::submit('Submit', array('class' => 'btn btn-info', 'style'=>'margin-top:-15px;')) }}            
            {{ Form::reset('Reset', array('class' => 'btn btn-danger', 'style'=>'margin-top:-15px;')) }}
          </section>
          {{ Form::close() }}
          <!-- search and filter form end -->
        </section>
        <!-- listing section -->
        <div class="panel-body">
          <div class="adv-table">
            <section id="flip-scroll">
              <table class="table table-bordered table-striped table-condensed cf">
                <thead class="cf">
                  <tr>
                    <th>Delete</th>
                    <th>Created Date</th>
                    <th>Customer</th>
                    <th>Location</th>
                    <th>Sales Person</th>
                    <th>Attached Contract Number</th>
                    <th>Lead Id</th>
                    <th>Contract Number</th>
                    <th>Contract Type</th>
                    <th>Type</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Serial Num</th>
                    <th>KW</th>
                    <th>Engine Make</th>
                    <th>Engine Model</th>
                    <th>Quoted Amount</th>
                    <th>Calculated Amount</th>
                    <th>Contact Name</th>
                    <th>Contact Phone</th>
                    <th>Generator KW</th>
                    <th>Status</th>
                    <th>Date Won</th>
                    <th>Job Number(s)</th>
                    <th>Invoice Amount</th>
                    <th>Sales Tax</th>
                    <th>Labor Cost</th>
                    <th>Marerial Cost</th>
                    <th>Total Cost</th>
                    <th>Net Margin</th>
                    <th>Comm. Owed</th>
                    <th>Comm. Paid</th>
                    <th>Date Comm. Paid</th>
                    <th>Comm. Balance</th>
                    <th>Attachments</th>
                    <th>Downloads</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($results as $key=>$getRow)
                  <tr>
                    <td>{{ Form::checkbox('delChk[]',$getRow->id,'', array('id'=>'delChk[]','class' => 'input-group') + $deletePermission) }}</td>
                    <td>{{ ($getRow->created_on != '-' ? date('m/d/Y',strtotime($getRow->created_on)) : '-') }}</td>
                    <td>{{ $getRow->customer }}</td>
                    <td>{{ $getRow->eqp_location }}</td>
                    <td>{{ $getRow->salesPerson }}</td>
                    <td>{{ HTML::link('#'.$getRow->GPG_attach_contract_number, (!empty($getRow->GPG_attach_contract_number)) ? $getRow->GPG_attach_contract_number : '', array('target'=>'_blank','class'=>'btn-link', 'SContractNumber'=>$getRow->GPG_attach_contract_number)) }}</td>
                    <td>{{ HTML::link('#'.$getRow->attachLeadId, $getRow->attachLeadId, array('target'=>'_blank','class'=>'btn-link', 'LeadNumberStart'=>$getRow->attachLeadId)) }}</td>
                    <td>{{ HTML::link('contract/consum_contract_frm/'.$getRow->id.'/'.$getRow->job_num.'',$getRow->job_num , array('target'=>'_blank','class'=>'btn btn-link btn-xs','style'=>'marigin:-7px', 'id'=>$getRow->id,'j_num'=>$getRow->job_num))}} </td>
                    <td>{{ (isset($CONTRACT_TYPE[$getRow->consum_contract_type]) && !empty($getRow->consum_contract_type)) ? $CONTRACT_TYPE[$getRow->consum_contract_type] : '' }}</td>
                    <td>{{ $getRow->eqp_type }}</td>
                    <td>{{ $getRow->eqp_make }}</td>
                    <td>{{ $getRow->eqp_model }}</td>
                    <td>{{ $getRow->eqp_serial }}</td>
                    <td>{{ $getRow->eqp_kw }}</td>
                    <td>{{ $getRow->engMake }}</td>
                    <td>{{ $getRow->engModel }}</td>
                    <td>{{ Generic::currency_format($getRow->manual_amount) }}</td>
                    <td>{{ Generic::currency_format($getRow->grand_list_total) }}</td>
                    <td>{{ $getRow->pri_contact_name }}</td>
                    <td>{{ $getRow->pri_contact_phone }}</td>
                    <td>{{ $getRow->gen_kw }}</td>
                    <td>{{ ($getRow->is_renewed == 1 ? "Renewed - " : "") }}{{ $getRow->consum_contract_status }}</td>
                    <td>{{ ($getRow->date_job_won != '-' ? date('m/d/Y',strtotime($getRow->date_job_won)) : '-') }}</td>
                    <td>
                      @if(!empty($getRow->GPG_attach_contract_number))
                      <u>{{ HTML::link('#viewJobsModal', 'View Jobs', array('data-toggle'=>'modal', 'contract_num'=>$getRow->GPG_attach_contract_number, 'SDate2'=>Input::get('SDate2'), 'EDate2'=>Input::get('EDate2'), 'name'=>'viewJobsModal')) }}</u>
                      @endif
                    </td>
                    <td>
                      @if(!empty($getRow->GPG_attach_contract_number))
                      {{ HTML::link('#viewJobsModal', Generic::currency_format($getRow->invoice_amount), array('data-toggle'=>'modal', 'contract_num'=>$getRow->GPG_attach_contract_number, 'SDate2'=>Input::get('SDate2'), 'EDate2'=>Input::get('EDate2'), 'name'=>'viewJobsModal')) }}
                      @else
                      {{ Generic::currency_format($getRow->invoice_amount) }}
                      @endif
                    </td>
                    <td>
                      @if(!empty($getRow->GPG_attach_contract_number))
                      {{ HTML::link('#viewJobsModal', Generic::currency_format($getRow->invoice_tax), array('data-toggle'=>'modal', 'contract_num'=>$getRow->GPG_attach_contract_number, 'SDate2'=>Input::get('SDate2'), 'EDate2'=>Input::get('EDate2'), 'name'=>'viewJobsModal')) }}
                      @else
                      {{ Generic::currency_format($getRow->invoice_tax) }}
                      @endif
                    </td>
                    <td>
                      @if(!empty($getRow->GPG_attach_contract_number))
                      {{ HTML::link('#viewJobsModal', Generic::currency_format($getRow->labor_cost), array('data-toggle'=>'modal', 'contract_num'=>$getRow->GPG_attach_contract_number, 'SDate2'=>Input::get('SDate2'), 'EDate2'=>Input::get('EDate2'), 'name'=>'viewJobsModal')) }}
                      @else
                      {{ Generic::currency_format($getRow->labor_cost) }}
                      @endif
                    </td>
                    <td>
                      @if(!empty($getRow->GPG_attach_contract_number))
                      {{ HTML::link('#viewJobsModal', Generic::currency_format($getRow->material_cost), array('data-toggle'=>'modal', 'contract_num'=>$getRow->GPG_attach_contract_number, 'SDate2'=>Input::get('SDate2'), 'EDate2'=>Input::get('EDate2'), 'name'=>'viewJobsModal')) }}
                      @else
                      {{ Generic::currency_format($getRow->material_cost) }}
                      @endif
                    </td>
                    <td>
                      @if(!empty($getRow->GPG_attach_contract_number))
                      {{ HTML::link('#viewJobsModal', Generic::currency_format($getRow->material_cost+$getRow->labor_cost), array('data-toggle'=>'modal', 'contract_num'=>$getRow->GPG_attach_contract_number, 'SDate2'=>Input::get('SDate2'), 'EDate2'=>Input::get('EDate2'), 'name'=>'viewJobsModal')) }}
                      @else
                      {{ Generic::currency_format($getRow->material_cost+$getRow->labor_cost) }}
                      @endif
                    </td>
                    <td>
                      @if(!empty($getRow->GPG_attach_contract_number))
                      {{ HTML::link('#viewJobsModal', Generic::currency_format($getRow->net_margin), array('data-toggle'=>'modal', 'contract_num'=>$getRow->GPG_attach_contract_number, 'SDate2'=>Input::get('SDate2'), 'EDate2'=>Input::get('EDate2'), 'name'=>'viewJobsModal')) }} <strong>[{{ $netMarginPercent = number_format(@($getRow->net_margin/$getRow->invoice_amount)*100,2) }}%]</strong>
                      @else
                      {{ Generic::currency_format($getRow->net_margin) }} <strong>[{{ $netMarginPercent = number_format(@($getRow->net_margin/$getRow->invoice_amount)*100,2) }}%]</strong>
                      @endif
                    </td>
                    <td>{{ Generic::currency_format($getRow->comm_owed) }}</td>
                    <td>{{ Generic::currency_format($getRow->comm_amount) }}</td>
                    <td>{{ ($getRow->comm_date != '-' ? date('m/d/Y',strtotime($getRow->comm_date)) : '-') }}</td>
                    <td>{{ Generic::currency_format($getRow->comm_balance) }}</td>                    
                    <td>{{ HTML::link('#manageFilesModal', 'Manage Files', array('data-toggle'=>'modal', 'id'=>$getRow->id,'job_num'=>$getRow->job_num, 'class' => 'btn btn-link', 'name'=>'manageFilesModal')) }}</td>
                    <td>{{ Generic::show_consum_contract_attactments($getRow->id) }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
              {{ HTML::link("contract/excelExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success'))}}                   
              {{ Form::button('Delete Selected Contracts', array('class' => 'btn btn-danger', 'id'=>'delete_records')) }}
            </section>
            {{ $results->appends(array_filter(Input::except('_token')))->links() }}
          </div>
        </div>
        <!-- listing section end -->
      </div>
    </div>

    <!-- View Jobs Modal Start -->
    <div id="viewJobsModal" class="modal fade">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Invoice Info: <span id="invoiceContractNumberSpan"></span></h4>
          </div>
          <div class="modal-body" style="max-height: calc(100vh - 210px); overflow-y: auto;">
            <div class="form-group" id="display_invoice_info">
              
            </div>
          </div>
          <div class="modal-footer">
            {{Form::button('Close', array('class' => 'btn btn-danger','data-dismiss'=>'modal'))}}
          </div>
        </div>
      </div>
    </div>
    <!-- View Jobs Modal End -->

    <!-- Manage Files Modal Start -->
    <div class="modal fade" id="manageFilesModal" tabindex="-1" role="dialog" aria-labelledby="manageFilesModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            {{Form::button('&times;', array('class'=>'close','data-dismiss'=>'modal', 'aria-hidden'=>'true'))}}
            <h4 class="modal-title">ATTACHMENT MANAGEMENT: [{{ $getRow->job_num }}]</h4>
          </div>
          <div class="modal-body">
            {{ Form::open(array('before'=>'csrf', 'id'=>'submit_file_form', 'url'=>route('contract/manageContractFiles'), 'files'=>true, 'method'=>'post')) }}
            {{ Form::hidden('fjob_id','',array('id' => 'change_job_id' )) }}
            {{ Form::hidden('fjob_num','',array('id' => 'change_job_num' )) }}
            <div class="form-group">
              <section id="no-more-tables"  style="padding:10px;">
                <table class="table table-bordered table-striped table-condensed cf">
                  <thead class="cf">
                    <tr>
                      <th>#</th>
                      <th>File Name </th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody class="cf" id="display_contract_files">
                  </tbody>
                </table>
              </section>
              <div style="display: inline;">
                {{ Form::file('attachment', array('style'=>'float: left !important; display:inline !important; width:50%;' ,'id' => 'attachment')) }}
              </div>
              {{ Form::close() }}
              <div class="btn-group" style="padding:20px;">
                {{ Form::button('Submit', array('class' => 'btn btn-success', 'id'=>'submit_attachments')) }}
                {{ Form::button('Cancel', array('class' => 'btn btn-danger','data-dismiss'=>'modal')) }}
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- Manage Files Modal End -->

<script type="text/javascript">
  
    /* apply datepicker */

    $('.default-date-picker').datepicker({
        format: 'yyyy-mm-dd'
    });

    /* show/hide search and filter form */

    $("section[mysection=hide_n_show]").hide();
    $('#togglerButton').click(function() {
        $("section[mysection=hide_n_show]").toggle("slow");
        if ($('#toggle_div_plus').attr("class") == "fa fa-plus")
            $('#toggle_div_plus').removeClass('fa fa-plus').addClass('fa fa-minus');
        else
            $('#toggle_div_plus').removeClass('fa fa-minus').addClass('fa fa-plus');
    });

    /* ajax request to contract invoice amount */

    $('a[name=viewJobsModal]').on('click', function() {
        $('#invoiceContractNumberSpan').html($(this).attr('contract_num'));
        $.ajax({
            url: "{{ URL('ajax/contractInvoiceAmount') }}",
            data: {
                'contract_num': $(this).attr('contract_num'),
                'SDate2': $(this).attr('SDate2'),
                'Edate2': $(this).attr('EDate2')
            },
            success: function(data) {
                $('#display_invoice_info').html(data);
            },
        });
    });

    /* request to delete bulk records */

    $("#delete_records").click(function() {

        var selectedIds = $('input[type=checkbox]:checked').map(function(_, el) {
            return $(el).val();
        }).get();

        if (selectedIds.length > 0) {
            var result = confirm("Are you sure! you want to delete this/these: " + selectedIds.length + " contract(s) ....?");
        } else {
            alert("No Item Selected");
            return;
        }

        if (result) {
            $.ajax({
                url: "{{URL('ajax/deleteContracts') }}",
                data: {
                    'selectedIds': selectedIds
                },
                success: function(data) {
                    if (data == 1) {
                        alert("Deleted Successfully!");
                        location.reload();
                    } else
                        alert('Error while deleting record(s)!')
                },
            });
        }

    });

    $('a[name=manageFilesModal]').click(function() {
        var job_num = $(this).attr('job_num');
        var job_id = $(this).attr('id');
        $('#change_job_id').val(job_id);
        $('#change_job_num').val(job_num);

        $.ajax({
            url: "{{URL('ajax/getContractFiles')}}",
            data: {
                'id': job_id,
                'num': job_num
            },
            success: function(data) {
                $('#display_contract_files').html(data);

                $('a[name=del_contract_file]').click(function() {
                    var result = confirm("Are you sure! you want to delete....?");
                    if (result) {
                        $.ajax({
                            url: "{{URL('ajax/deleteContractFile')}}",
                            data: {
                                'id': $(this).attr('id')
                            },
                            success: function(data) {
                                if (data == 1) {
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

    /* request to manage files modal box */

    $('a[name=manageFilesModal]').on('click', function() {
        $.ajax({
            url: "{{ URL('ajax/manageContractFiles') }}",
            data: {
                'id': $(this).attr('id'),
                'jobNum': $(this).attr('jobNum'),
                'rowId': $(this).attr('rowId')
            },
            success: function(data) {
                $('#display_manage_files').html(data);
            },
        });
    });

    $('#submit_attachments').click(function() {
        $('#submit_file_form').submit();
    });
</script>
<script src="{{asset('js/jquery.nicescroll.js') }}"></script>
<script src="{{asset('js/common-scripts.js') }}"></script>
@stop