@extends("layouts/dashboard_master")
@section('dashboard_panels')
<div class="row">
    <div class="col-sm-12">
        <section class="panel">
            <header class="panel-heading">
                CONSUM CONTRACTS TENURE REPORT <span class="tools pull-right"> <a href="javascript:;" class="fa fa-chevron-down"></a></span>
            </header>
        </section>
        <section class="panel">
            <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                <b><i>CONSUM CONTRACTS TENURE REPORT</i></b>
            </header>
            <!-- search and filter form -->
            {{ Form::open(array('before'=>'csrf' ,'url'=>route('contractTenure/contractTenureList'), 'method'=>'post')) }}
            <div id="togglerButton">Show / Hide Search Box <i id="toggle_div_plus" class='fa fa-plus'></i></div>
            <section id="no-more-tables" style="padding:10px;" mySection="hide_n_show">
                <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                    <tbody>
                        <tr>
                            <td data-title="Contract Number:">
                                {{ Form::label('jobnumber', 'Contract Number:', array('class' => 'control-label')) }}
                                {{ Form::text('jobnumber','', array('class' => 'form-control form-control-inline input-medium', 'id' => 'jobnumber')) }}
                            </td>
                            <td data-title="" colspan="3"></td>
                        </tr>
                        <tr>
                            <td data-title="Contract Start Date Range Start:">
                                {{ Form::label('SSDate', 'Contract Start Date Range Start:', array('class' => 'control-label')) }}
                                {{ Form::text('SSDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SSDate')) }}
                            </td>
                            <td data-title="Contract Start Date Range End:">
                                {{ Form::label('SEDate', 'Contract Start Date Range End:', array('class' => 'control-label')) }}
                                {{ Form::text('SEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'SEDate')) }}
                            </td>
                            <td data-title="Contract End Date Range Start:">
                                {{ Form::label('ESDate', 'Contract End Date Range Start:', array('class' => 'control-label')) }}
                                {{ Form::text('ESDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'ESDate')) }}
                            </td>
                            <td data-title="Contract End Date Range End:">
                                {{ Form::label('EEDate', 'Contract End Date Range End:', array('class' => 'control-label')) }}
                                {{ Form::text('EEDate','', array('class' => 'form-control form-control-inline input-medium default-date-picker', 'id' => 'EEDate')) }}
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
                              <td>Contract Number</td>
                              <td>Customer Name</td>
                              <td>Start Date</td>
                              <td>End Date</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($results as $key=>$getRow)
                            <tr>
                              <td>{{ HTML::link('contract/contractList?optJobNumber='.$getRow->job_num, $getRow->job_num, array('target'=>'_blank','class'=>'btn-link')) }}</td>
                              <td>{{ $getRow->cus_name }}</td>
                              <td>{{ ($getRow->consum_contract_start_date != '-' ? date(Config::get('settings._DateFormat'),strtotime($getRow->consum_contract_start_date)) : '-') }}</td>
                              <td>{{ ($getRow->consum_contract_end_date != '-' ? date(Config::get('settings._DateFormat'),strtotime($getRow->consum_contract_end_date)) : '-') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ HTML::link("contractTenure/excelExport?".http_build_query(array_filter(Input::except('_token', 'page'))), 'Export Excel' , array('class'=>'btn btn-success')) }}                    
                </section>
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
</script>
<script src="{{asset('js/jquery.nicescroll.js') }}"></script>
<script src="{{asset('js/common-scripts.js') }}"></script>
@stop