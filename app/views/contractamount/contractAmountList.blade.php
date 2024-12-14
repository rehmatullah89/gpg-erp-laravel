@extends("layouts/dashboard_master")
@section('dashboard_panels')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                CONTRACT IMPORT LIST <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a></span>
            </header>
        </section>
        <section class="panel">
            <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                <b><i>CONTRACT IMPORT LIST</i></b>
            </header>
            <!-- search and filter form -->
            {{ Form::open(array('before'=>'csrf' ,'url'=>route('contractAmount/contractAmountList'), 'method'=>'post')) }}
            <div id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
            <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <tbody>
                        <tr>
                            <td data-title="Start Date Start:">
                                {{ Form::label('SDate', 'Start Date Start:', array('class' => 'control-label')) }}
                                {{ Form::text('SDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SDate')) }}
                            </td>
                            <td data-title="Start Date End:">
                                {{ Form::label('EDate', 'Start Date End:', array('class' => 'control-label')) }}
                                {{ Form::text('EDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EDate')) }}
                            </td>
                            <td data-title="Expiration Date Start:">
                                {{ Form::label('ExpirationSDate', 'Expiration Date Start:', array('class' => 'control-label')) }}
                                {{ Form::text('ExpirationSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ExpirationSDate')) }}
                            </td>
                            <td data-title="Expiration Date End:">
                                {{ Form::label('ExpirationEDate', 'Expiration Date End:', array('class' => 'control-label')) }}
                                {{ Form::text('ExpirationEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ExpirationEDate')) }}
                            </td>                            
                        </tr>
                        <tr>
                            <td colspan="4">                                
                                <span class="smallblack"><strong>Note:</strong> Leave blank for viewing records from all days. Fill start date only if want to see the records for a perticular date. Same note for all date fields given below.</span>
                            </td>
                        </tr>
                        <tr>
                            <td data-title="Contract Number Start:">
                                {{ Form::label('SContractNumber', 'Contract Number Start:', array('class' => 'control-label')) }}
                                {{ Form::text('SContractNumber','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'SContractNumber')) }}
                            </td>
                            <td data-title="Contract Number End (optional):">
                                {{ Form::label('EContractNumber', 'Contract Number End (optional):', array('class' => 'control-label')) }}
                                {{ Form::text('EContractNumber','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'EContractNumber')) }}
                            </td>
                            <td data-title="Customer:">
                                {{ Form::label('optCustomer', 'Customer:', array('class' => 'control-label')) }}
                                {{ Form::select('optCustomer', ['' => 'ALL'] + $customersCombo, '', ['id' => 'optCustomer', 'class'=>'form-control m-bot15']) }}
                            </td>
                            <td data-title="Type:">
                                {{ Form::label('optType', 'Type:', array('class' => 'control-label')) }}
                                {{ Form::select('optType', ['' => 'ALL'] + $typesCombo, '', ['id' => 'optType', 'class'=>'form-control m-bot15']) }}
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
                                <td>Delete</td>
                                <td>Customer</td>
                                <td>Contract Number</td>
                                <td>Contract Prefix</td>
                                <td>Contract Digits</td>
                                <td>Location</td>
                                <td>Type</td>
                                <td>Start Date</td>
                                <td>Expiration Date</td>
                                <td>Price per Year</td>
                                <td>Billing Cycle</td>
                                <td>Price per Visit</td>
                                <td>Sales Person</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $key=>$getRow)
                            <tr>
                                <td>{{ Form::checkbox('delChk[]',$getRow->id,'', array('id'=>'delChk[]','class' => 'input-group') + $deletePermission) }}</td>
                                <td>{{ $getRow->customer }}</td>
                                <td>{{ $getRow->contract_number }}</td>
                                <td>{{ $getRow->contract_prefix }}</td>
                                <td>{{ $getRow->contract_digits }}</td>
                                <td>{{ $getRow->location }}</td>
                                <td>{{ $getRow->type }}</td>
                                <td>{{ ($getRow->start_date != '-' ? date(Config::get('settings._DateFormat'),strtotime($getRow->start_date)) : '-') }}</td>
                                <td>{{ ($getRow->end_date != '-' ? date(Config::get('settings._DateFormat'),strtotime($getRow->end_date)) : '-') }}</td>
                                <td>{{ Generic::currency_format($getRow->price_per_year) }}</td>
                                <td>{{ $getRow->billing_cycle }}</td>
                                <td>{{ Generic::currency_format($getRow->price_per_visit) }}</td>
                                <td>{{ $getRow->salesPerson }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ HTML::link("contractAmount/excelExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success')) }}
                    {{ Form::button('Delete Selected Contracts', array('class' => 'btn btn-danger', 'id'=>'delete_records')) }}
                </section>
                {{ $results->appends(array_filter(Input::except('_token')))->links() }}
            </div>
        </div>
        <!-- listing section end -->
    </div>
</div>

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

    /* request to delete bulk records */

    $("#delete_records").click(function() {

        var selectedIds = $('input[type=checkbox]:checked').map(function(_, el) {
            return $(el).val();
        }).get();

        if (selectedIds.length > 0) {
            var result = confirm("Are you sure! you want to delete this/these: " + selectedIds.length + " record(s) ....?");
        } else {
            alert("No Item Selected");
            return;
        }

        if (result) {
            $.ajax({
                url: "{{URL('ajax/deleteContractAmount') }}",
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
</script>
<script src="{{asset('js/jquery.nicescroll.js') }}"></script>
<script src="{{asset('js/common-scripts.js') }}"></script>
@stop