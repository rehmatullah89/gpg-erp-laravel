@extends("layouts/dashboard_master")
@section('dashboard_panels')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                CONSUME CONTRACT EQP. INFO MANAGEMENT <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a></span>
            </header>
        </section>
        <section class="panel">
            <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                <b><i>CONSUME CONTRACT EQP. INFO MANAGEMENT</i></b>
            </header>
            <!-- search and filter form -->
            {{ Form::open(array('before'=>'csrf' ,'url'=>route('consumecontract/equipmentInfoList'), 'method'=>'post')) }}
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
                            <td data-title="Customer:">
                                {{ Form::label('optCustomer', 'Customer:', array('class' => 'control-label')) }}
                                {{ Form::select('optCustomer', ['' => 'ALL'] + $customersCombo, '', ['id' => 'optCustomer', 'class'=>'form-control m-bot15']) }}
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <span class="smallblack"><strong>Note:</strong> Leave blank for viewing records from all Dates.</span>
                            </td>
                        </tr>
                        <tr>
                            <td data-title="Location:">
                                {{ Form::label('optLocation', 'Location:', array('class' => 'control-label')) }}
                                {{ Form::text('optLocation','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'optLocation')) }}
                            </td>
                            <td data-title="Consum Contract Number:" colspan="2">
                                {{ Form::label('optContract', 'Contract Number:', array('class' => 'control-label')) }}
                                {{ Form::select('optContract', ['' => 'All Consum Contract'] + $consumeContractCombo, '', ['id' => 'optContract', 'class'=>'form-control m-bot15']) }}
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
                                <th>Edit</th>
                                <th>Id</th>
                                <th>Contract Number</th>
                                <th>Customer</th>
                                <th>Location</th>
                                <th>Address1</th>
                                <th>Address2</th>
                                <th>City</th>
                                <th>State</th>
                                <th>Phone</th>
                                <th>Zip</th>
                                <th>Attn</th>
                                <th>Make</th>
                                <th>Model</th>
                                <th>Serial</th>
                                <th>Spec</th>
                                <th>Kw</th>
                                <th>Phase</th>
                                <th>Volts</th>
                                <th>Amps</th>
                                <th>Air Belt</th>
                                <th>Air Belt Qty</th>
                                <th>Air Filter1</th>
                                <th>Air Filter1 Qty</th>
                                <th>Air Filter2</th>
                                <th>Air Filter2 Qty</th>
                                <th>Battery Charger</th>
                                <th>Block Heater</th>
                                <th>Battery Type</th>
                                <th>Battery Qty</th>
                                <th>Controller Model</th>
                                <th>Filter Changed On</th>
                                <th>Coolant Filter</th>
                                <th>Coolant Filter Qty</th>
                                <th>Fan Belt</th>
                                <th>Fan Belt Qty</th>
                                <th>Fuel Filter1</th>
                                <th>Fuel Filter1 Qty</th>
                                <th>Fuel Filter2</th>
                                <th>Fuel Filter2 Qty</th>
                                <th>Fuel Filter3</th>
                                <th>Fuel Filter3 Qty</th>
                                <th>Gen Misc</th>
                                <th>Governor</th>
                                <th>Lower Hose</th>
                                <th>Lower Hose Qty</th>
                                <th>Upper Hose</th>
                                <th>Upper Hose Qty</th>
                                <th>Oil Type</th>
                                <th>Oil Capacity</th>
                                <th>Oil Filter1</th>
                                <th>Oil Filter1 Qty</th>
                                <th>Oil Filter2</th>
                                <th>Oil Filter2 Qty</th>
                                <th>Oil Filter3</th>
                                <th>Oil Filter3 Qty</th>
                                <th>Oil Filter4</th>
                                <th>Oil Filter4 Qty</th>
                                <th>Water Separator Part No</th>
                                <th>Water Separator Qty</th>
                                <th>Waterpump Belt</th>
                                <th>Waterpump Belt Qty</th>
                                <th>Misc</th>
                                <th>Misc Qty</th>
                                <th>Misc1</th>
                                <th>Misc1 Qty</th>
                                <th>Misc2</th>
                                <th>Misc2 Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $key=>$getRow)
                            <tr>
                                <td>{{ Form::checkbox('delChk[]',$getRow->id,'', array('id'=>'delChk[]','class' => 'input-group') + $deletePermission) }}</td>
                                <td>{{ HTML::link('consumecontract/editEquipmentInfo/'.$getRow->id, '', array('class' => 'btn btn-primary btn-xs fa fa-pencil')) }}</td>
                                <td>{{ $getRow->id }}</td>
                                <td>{{ $getRow->consumContract }}</td>
                                <td>{{ $getRow->cusName }}</td>
                                <td>{{ $getRow->location }}</td>
                                <td>{{ $getRow->address1 }}</td>
                                <td>{{ $getRow->address2 }}</td>
                                <td>{{ $getRow->city }}</td>
                                <td>{{ $getRow->state }}</td>
                                <td>{{ $getRow->phone }}</td>
                                <td>{{ $getRow->zip }}</td>
                                <td>{{ $getRow->attn }}</td>
                                <td>{{ $getRow->make }}</td>
                                <td>{{ $getRow->model }}</td>
                                <td>{{ $getRow->serial }}</td>
                                <td>{{ $getRow->spec }}</td>
                                <td>{{ $getRow->kw }}</td>
                                <td>{{ $getRow->phase }}</td>
                                <td>{{ $getRow->volts }}</td>
                                <td>{{ $getRow->amps }}</td>
                                <td>{{ $getRow->air_belt }}</td>
                                <td>{{ $getRow->air_belt_qty }}</td>
                                <td>{{ $getRow->air_filter1 }}</td>
                                <td>{{ $getRow->air_filter1_qty }}</td>
                                <td>{{ $getRow->air_filter2 }}</td>
                                <td>{{ $getRow->air_filter2_qty }}</td>
                                <td>{{ $getRow->battery_charger }}</td>
                                <td>{{ $getRow->block_heater }}</td>
                                <td>{{ $getRow->battery_type }}</td>
                                <td>{{ $getRow->battery_qty }}</td>
                                <td>{{ $getRow->controller_model }}</td>
                                <td>{{ $getRow->filter_changed_on }}</td>
                                <td>{{ $getRow->coolant_filter }}</td>
                                <td>{{ $getRow->coolant_filter_qty }}</td>
                                <td>{{ $getRow->fan_belt }}</td>
                                <td>{{ $getRow->fan_belt_qty }}</td>
                                <td>{{ $getRow->fuel_filter1 }}</td>
                                <td>{{ $getRow->fuel_filter1_qty }}</td>
                                <td>{{ $getRow->fuel_filter2 }}</td>
                                <td>{{ $getRow->fuel_filter2_qty }}</td>
                                <td>{{ $getRow->fuel_filter3 }}</td>
                                <td>{{ $getRow->fuel_filter3_qty }}</td>
                                <td>{{ $getRow->gen_misc }}</td>
                                <td>{{ $getRow->governor }}</td>
                                <td>{{ $getRow->lower_hose }}</td>
                                <td>{{ $getRow->lower_hose_qty }}</td>
                                <td>{{ $getRow->upper_hose }}</td>
                                <td>{{ $getRow->upper_hose_qty }}</td>
                                <td>{{ $getRow->oil_type }}</td>
                                <td>{{ $getRow->oil_capacity }}</td>
                                <td>{{ $getRow->oil_filter1 }}</td>
                                <td>{{ $getRow->oil_filter1_qty }}</td>
                                <td>{{ $getRow->oil_filter2 }}</td>
                                <td>{{ $getRow->oil_filter2_qty }}</td>
                                <td>{{ $getRow->oil_filter3 }}</td>
                                <td>{{ $getRow->oil_filter3_qty }}</td>
                                <td>{{ $getRow->oil_filter4 }}</td>
                                <td>{{ $getRow->oil_filter4_qty }}</td>
                                <td>{{ $getRow->water_separator_part_no }}</td>
                                <td>{{ $getRow->water_separator_qty }}</td>
                                <td>{{ $getRow->waterpump_belt }}</td>
                                <td>{{ $getRow->waterpump_belt_qty }}</td>
                                <td>{{ $getRow->misc }}</td>
                                <td>{{ $getRow->misc_qty }}</td>
                                <td>{{ $getRow->misc1 }}</td>
                                <td>{{ $getRow->misc1_qty }}</td>
                                <td>{{ $getRow->misc2 }}</td>
                                <td>{{ $getRow->misc2_qty }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
                url: "{{URL('ajax/deleteConsumeContracts') }}",
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