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
                <b><i>ADD EQUIPMENT INFO </i></b>
            </header>
            <!-- form section -->
            <div class="panel-body">
                <section id="flip-scroll">
                    {{ Form::open(array('before'=>'csrf' ,'url'=>route('consumecontract/editEquipmentInfo', ['id'=> $equipmentInfoId]), 'files'=>true, 'method'=>'post', 'class'=>'form-horizontal')) }}
                    <div class="form-group col-md-6">
                        {{ Form::label('gpg_customer_id', 'Customer:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::select('gpg_customer_id', ['' => 'Select Customer'] + $customersCombo, $data['gpg_customer_id'], ['id' => 'gpg_customer_id', 'class'=>'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('_gpg_consum_contract_id', 'Consum Contract:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::select('gpg_consum_contract_id', ['' => 'Select Consum Contract'] + $consumeContractCombo, $data['gpg_consum_contract_id'], ['id' => 'gpg_consum_contract_id', 'class'=>'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('location', 'Location:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('location', $data['location'] , array('class' => 'form-control', 'id' => 'location')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('address1', 'Address 1:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('address1', $data['address1'] , array('class' => 'form-control', 'id' => 'address1')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('address2', 'Address 2:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('address2', $data['address2'] , array('class' => 'form-control', 'id' => 'address2')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('city', 'City:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('city', $data['city'] , array('class' => 'form-control', 'id' => 'city')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('state', 'State:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('state', $data['state'] , array('class' => 'form-control', 'id' => 'state')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('zip', 'Zip:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('zip', $data['zip'] , array('class' => 'form-control', 'id' => 'zip')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('phone', 'Phone:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('phone', $data['phone'] , array('class' => 'form-control', 'id' => 'phone')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('attn', 'Attn:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('attn', $data['attn'] , array('class' => 'form-control', 'id' => 'attn')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('make', 'Make:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('make', $data['make'] , array('class' => 'form-control', 'id' => 'make')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('model', 'Model:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('model', $data['model'] , array('class' => 'form-control', 'id' => 'model')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('serial', 'Serial:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('serial', $data['serial'] , array('class' => 'form-control', 'id' => 'serial')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('spec', 'Spec:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('spec', $data['spec'] , array('class' => 'form-control', 'id' => 'spec')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('kw', 'KW:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('kw', $data['kw'] , array('class' => 'form-control', 'id' => 'kw')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('phase', 'Phase:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('phase', $data['phase'] , array('class' => 'form-control', 'id' => 'phase')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('volts', 'Volts:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('volts', $data['volts'] , array('class' => 'form-control', 'id' => 'volts')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('amps', 'Amps:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('amps', $data['amps'] , array('class' => 'form-control', 'id' => 'amps')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('air_belt', 'Air Belt:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('air_belt', $data['air_belt'] , array('class' => 'form-control', 'id' => 'air_belt')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('air_belt_qty', 'Air Belt Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('air_belt_qty', $data['air_belt_qty'] , array('class' => 'form-control', 'id' => 'air_belt_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('air_filter1', 'Air Filter 1:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('air_filter1', $data['air_filter1'] , array('class' => 'form-control', 'id' => 'air_filter1')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('air_filter1_qty', 'Air Filter 1 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('air_filter1_qty', $data['air_filter1_qty'] , array('class' => 'form-control', 'id' => 'air_filter1_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('air_filter2', 'Air Filter 2:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('air_filter2', $data['air_filter2'] , array('class' => 'form-control', 'id' => 'air_filter2')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('air_filter2_qty', 'Air Filter 2 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('air_filter2_qty', $data['air_filter2_qty'] , array('class' => 'form-control', 'id' => 'air_filter2_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('battery_charger', 'Battery Charger:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('battery_charger', $data['battery_charger'] , array('class' => 'form-control', 'id' => 'battery_charger')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('block_heater', 'Block Heater:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('block_heater', $data['block_heater'] , array('class' => 'form-control', 'id' => 'block_heater')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('battery_type', 'Battery Type:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('battery_type', $data['battery_type'] , array('class' => 'form-control', 'id' => 'battery_type')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('battery_qty', 'Battery Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('battery_qty', $data['battery_qty'] , array('class' => 'form-control', 'id' => 'battery_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('controller_model', 'Controller Model:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('controller_model', $data['controller_model'] , array('class' => 'form-control', 'id' => 'controller_model')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('filter_changed_on', 'Filter Changed On:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('filter_changed_on', $data['filter_changed_on'] , array('class' => 'form-control datepicker', 'id' => 'filter_changed_on')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('coolant_filter', 'Coolant Filter:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('coolant_filter', $data['coolant_filter'] , array('class' => 'form-control datepicker', 'id' => 'coolant_filter')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('coolant_filter_qty', 'Coolant Filter Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('coolant_filter_qty', $data['coolant_filter_qty'] , array('class' => 'form-control datepicker', 'id' => 'coolant_filter_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('fan_belt', 'Fan Belt:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('fan_belt', $data['fan_belt'] , array('class' => 'form-control datepicker', 'id' => 'fan_belt')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('fan_belt_qty', 'Fan Belt Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('fan_belt_qty', $data['fan_belt_qty'] , array('class' => 'form-control datepicker', 'id' => 'fan_belt_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('fuel_filter1', 'Fuel Filter1:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('fuel_filter1', $data['fuel_filter1'] , array('class' => 'form-control datepicker', 'id' => 'fuel_filter1')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('fuel_filter1_qty', 'Fuel Filter1 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('fuel_filter1_qty', $data['fuel_filter1_qty'] , array('class' => 'form-control datepicker', 'id' => 'fuel_filter1_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('fuel_filter2', 'Fuel Filter 2:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('fuel_filter2', $data['fuel_filter2'] , array('class' => 'form-control datepicker', 'id' => 'fuel_filter2')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('fuel_filter2_qty', 'Fuel Filter 2 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('fuel_filter2_qty', $data['fuel_filter2_qty'] , array('class' => 'form-control datepicker', 'id' => 'fuel_filter2_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('fuel_filter3', 'Fuel Filter 3:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('fuel_filter3', $data['fuel_filter3'] , array('class' => 'form-control datepicker', 'id' => 'fuel_filter3')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('fuel_filter3_qty', 'Fuel Filter 3 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('fuel_filter3_qty', $data['fuel_filter3_qty'] , array('class' => 'form-control datepicker', 'id' => 'fuel_filter3_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('gen_misc', 'Gen Misc:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('gen_misc', $data['gen_misc'] , array('class' => 'form-control datepicker', 'id' => 'gen_misc')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('governor', 'Governor:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('governor', $data['governor'] , array('class' => 'form-control datepicker', 'id' => 'governor')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('lower_hose', 'Lower Hose:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('lower_hose', $data['lower_hose'] , array('class' => 'form-control datepicker', 'id' => 'lower_hose')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('lower_hose_qty', 'Lower Hose Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('lower_hose_qty', $data['lower_hose_qty'] , array('class' => 'form-control datepicker', 'id' => 'lower_hose_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('upper_hose', 'Upper Hose:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('upper_hose', $data['upper_hose'] , array('class' => 'form-control datepicker', 'id' => 'upper_hose')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('upper_hose_qty', 'Upper Hose Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('upper_hose_qty', $data['upper_hose_qty'] , array('class' => 'form-control datepicker', 'id' => 'upper_hose_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_type', 'Oil Type:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_type', $data['oil_type'] , array('class' => 'form-control datepicker', 'id' => 'oil_type')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_capacity', 'Oil Capacity:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_capacity', $data['oil_capacity'] , array('class' => 'form-control datepicker', 'id' => 'oil_capacity')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_filter1', 'Oil Filter 1:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_filter1', $data['oil_filter1'] , array('class' => 'form-control datepicker', 'id' => 'oil_filter1')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_filter1_qty', 'Oil Filter 1 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_filter1_qty', $data['oil_filter1_qty'] , array('class' => 'form-control datepicker', 'id' => 'oil_filter1_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_filter2', 'Oil Filter 2:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_filter2', $data['oil_filter2'] , array('class' => 'form-control datepicker', 'id' => 'oil_filter2')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_filter2_qty', 'Oil Filter 2 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_filter2_qty', $data['oil_filter2_qty'] , array('class' => 'form-control datepicker', 'id' => 'oil_filter2_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_filter3', 'Oil Filter 3:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_filter3', $data['oil_filter3'] , array('class' => 'form-control datepicker', 'id' => 'oil_filter3')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_filter3_qty', 'Oil Filter 3 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_filter3_qty', $data['oil_filter3_qty'] , array('class' => 'form-control datepicker', 'id' => 'oil_filter3_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_filter4', 'Oil Filter 4:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_filter4', $data['oil_filter4'] , array('class' => 'form-control datepicker', 'id' => 'oil_filter4')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('oil_filter4_qty', 'Oil Filter 4 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('oil_filter4_qty', $data['oil_filter4_qty'] , array('class' => 'form-control datepicker', 'id' => 'oil_filter4_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('water_separator_part_no', 'Water Separator Part No:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('water_separator_part_no', $data['water_separator_part_no'] , array('class' => 'form-control datepicker', 'id' => 'water_separator_part_no')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('water_separator_qty', 'Water Separator Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('water_separator_qty', $data['water_separator_qty'] , array('class' => 'form-control datepicker', 'id' => 'water_separator_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('waterpump_belt', 'Water Belt:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('waterpump_belt', $data['waterpump_belt'] , array('class' => 'form-control datepicker', 'id' => 'waterpump_belt')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('waterpump_belt_qty', 'Water Belt Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('waterpump_belt_qty', $data['waterpump_belt_qty'] , array('class' => 'form-control datepicker', 'id' => 'waterpump_belt_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('misc', 'Misc:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('misc', $data['misc'] , array('class' => 'form-control datepicker', 'id' => 'misc')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('misc_qty', 'Misc Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('misc_qty', $data['misc_qty'] , array('class' => 'form-control datepicker', 'id' => 'misc_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('misc1', 'Misc 1:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('misc1', $data['misc1'] , array('class' => 'form-control datepicker', 'id' => 'misc1')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('misc1_qty', 'Misc 1 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('misc1_qty', $data['misc1_qty'] , array('class' => 'form-control datepicker', 'id' => 'misc1_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('misc2', 'Misc 2:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('misc2', $data['misc2'] , array('class' => 'form-control datepicker', 'id' => 'misc2')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        {{ Form::label('misc2_qty', 'Misc 2 Qty:', array('class' => 'col-lg-4 control-label')) }}
                        <div class="col-lg-8">
                            {{ Form::text('misc2_qty', $data['misc2_qty'] , array('class' => 'form-control default-date-picker', 'id' => 'misc2_qty')) }}
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <div class="col-lg-8">
                            {{Form::submit("Update Equipment Info", array('class' => 'btn btn-success'))}}                        
                        </div>
                    </div>                    
                    {{ Form::close() }}
                </section>            
            </div>
        </section>
        <!-- form section end -->
    </div>
</div>

<script type="text/javascript">
  
    /* apply datepicker */

    $('.default-date-picker').datepicker({
        format: 'yyyy-mm-dd'
    });
    
</script>
<script src="{{asset('js/jquery.nicescroll.js') }}"></script>
<script src="{{asset('js/common-scripts.js') }}"></script>
@stop