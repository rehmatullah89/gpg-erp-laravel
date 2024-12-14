@extends("layouts/dashboard_master")
@section('content')
  <section>
    
  </section>
@stop
@section('dashboard_panels')
<?php
function getAllInvalidRecords($table){
  if($table == 'gpg_job'){
    $query = "SELECT id, job_num as name FROM ".$table." WHERE length(job_num) > 41";
  }else if($table == 'gpg_expense_gl_code'){
    $query = "SELECT id, (expense_gl_code · description) as name FROM ".$table." WHERE length(expense_gl_code · description) > 41";
  }else {
    $query = "SELECT id, name FROM ".$table." WHERE length(name) > 41";
  }
  $data = DB::select(DB::raw($query));
  return $data;
}
?>
              <!-- page start-->
              <div class="row">
                <div class="col-sm-12">
              <section class="panel">
              <header class="panel-heading">
               QUICKBOOK ANALYZER
             <span class="tools pull-right">
                <a href="javascript:;" class="fa fa-chevron-down"></a>
             </span>
              </header>
              <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->  
              <section class="panel">
                          <header class="panel-heading" style="background: white !important;  border-color: solid grey; color:grey;">
                              <i><b>Import :</b>via csv file.</i>
                          </header>
                             {{ Form::open(array('before' => 'csrf' ,'url'=>route('qc_reports/quickbook_analyzer'), 'files'=>true, 'method' => 'post')) }}
                                <section id="no-more-tables" style="padding:10px;">
                                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                                    <tbody>
                                    <tr>
                                      <td>
                                        <b>* Analyze:</b>
                                        {{Form::select('method',array(''=>'Select Export Type','gpg_vendor'=>'Vendors','gpg_customer'=>'Customers','gpg_expense_gl_code'=>'Accounts','gpg_job'=>'Jobs'),'', ['id' => 'method', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td>
                                        <b>* Analyze method:</b>
                                        {{Form::select('comparison',array('QtoG'=>'Quickbook To GPG','GtoQ'=>'GPG to Quickbook'),'', ['id' => 'comparison', 'class'=>'form-control m-bot15'])}}
                                        <sub> Quickbook to GPG - Importing data into Quickbook <br/>
                                              GPG to Quickbook - Importing data into GPG
                                        </sub>
                                      </td>
                                      <td>
                                        <b>* Data source (CSV supported):</b>
                                        {{Form::file('file', ['id' => 'file', 'class'=>'form-control m-bot15'])}}
                                      </td>
                                      <td>
                                        {{Form::submit('Click to Map', array('class' => 'btn btn-primary'))}}
                                        {{Form::button('Reset', array('class' => 'btn btn-danger', 'id'=>'reset_search_form'))}} 
                                      </td>
                                    </tr>
                                    </tbody>
                                    </table>
                                    <br/>
                                  </section>
                               {{ Form::close() }}
              </section> 
                <section id="no-more-tables" style="padding:10px;">
                  <!-- *******Start******* -->
                  <table class="table table-bordered table-striped table-condensed cf" id="mytable" align="center">
                  <tbody>
                   <?php 
                   $correctionName = '';
                   $submit = Input::get('method');
                   if(!empty($submit)){ ?>
                    <tr><td colspan="2">
                        <?php
                        $inValidRecords = getAllInvalidRecords(Input::get('method'));
                        $totalInvalid = count($inValidRecords);
                        if($submit == 'gpg_vendor' && !empty($inValidRecords)){
                            $correctionName = 'Vendors';
                            $linkMain = 'vendors/';
                            echo $totalInvalid.' Vendors name are Exceed 40 character limit(invalid character limit).';
                        }
                        elseif($submit == 'gpg_customer' && !empty($inValidRecords)){
                            $correctionName = 'Customers';
                            $linkMain = 'customers/';
                            echo $totalInvalid.' Customers name are Exceed 40 character limit(invalid character limit).';
                        }
                        elseif($submit == 'gpg_job' && !empty($inValidRecords)){
                            $correctionName = 'Jobs';
                            $linkMain = 'glcode/';
                            echo $totalInvalid.' Jobs name are Exceed 40 character limit(invalid character limit).';
                        }
                        elseif($submit == 'gpg_expense_gl_code' && !empty($inValidRecords)){
                            $correctionName = 'Accounts';
                            $linkMain = 'glcode/';
                            echo $totalInvalid.' Accounts name are Exceed 40 character limit(invalid character limit).';
                        }
                        ?>
                        <?php if($submit && !empty($inValidRecords)){ ?>
                            <input type="button" value="Click to correct." class="btn btn-info" id="click-correct"/>
                        <?php } ?>
                    </td></tr>
                <?php } ?>
                </tbody>
            </table>
            <?php if(!empty($submit)){ ?>
                <div>
                    <div>
                        <?php echo $correctionName. ' List for correction'; ?>
                    </div>
                    <div >
                        <table>
                        <?php $count = 1; 
                        foreach($inValidRecords as $key=>$value){ ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td>
                                  {{ HTML::link($linkMain.$value->id.'/edit',$value->name, array('target'=>'_blank','class'=>'btn btn-link btn-xs'))}}
                                </td>
                            </tr>
                        <?php $count++; } ?>
                        </table>
                    </div>
                </div>
            <?php } ?>
            <!-- #######END######### -->
                </section>  
              </section>
              </div>
              </div>
         <script>
           $('.default-date-picker').datepicker({
            format: 'yyyy-mm-dd'
          });

          $('#reset_search_form').click(function(){
              $('#method').val("");
              $('#comparison').val("");
          });
          
          $("#file").change(function() {
            var item = $("#file").val(); 
            if (item.split(".").pop(-1) != 'csv'){
              $("#file").val("");
              alert("Use Only Excel(.csv) files to upload!");
            }
          });
          $('#click-correct').click(function(){
                $('.show-correction').slideToggle();
          });
        </script>
      <script src="{{asset('js/jquery.nicescroll.js')}}"></script>
    <script src="{{asset('js/common-scripts.js')}}"></script>
@stop