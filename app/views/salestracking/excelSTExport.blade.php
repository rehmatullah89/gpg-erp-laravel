        <table class="table table-bordered table-striped table-condensed cf" >
              <thead class="cf">
              <tr>
                  <th>Created Date</th>
                  <th>Lead ID</th>
                  <th>Customer</th>
                  <th >Location</th>
                  <th >Sales Person</th>
                  <th >Days As of Today</th>
                  <th >Contact Date</th>
                  <th >Activity</th>
                  <th >Out Come of Activity</th>
                  <th >Days Since Created Date</th>
                  <th >Days Since Last Contact Date</th>
              </tr>
              </thead>
              <tbody class="cf">
                <?php
                  $preLead = '';
                  $preContactDate = '';
                  $fg = false;
                ?>
                  @foreach($query_data as $salesTrackingRow)
                  <?php
                    if ($preLead!=$salesTrackingRow['lead_id']){
                      $fg= !$fg;
                    }
                  ?>
                  <tr>
                  <?php if ($preLead!=$salesTrackingRow['lead_id']){
                      $preContactDate ='';
                  ?>
                    <td>{{date('m/d/Y',strtotime($salesTrackingRow['lead_entered']))}}</td>
                    <td>{{$salesTrackingRow['lead_id']}}</td>
                    <td>{{$salesTrackingRow['customer']}}</td>
                    <td>{{$salesTrackingRow['lead_loaction']}}</td>
                    <td>{{$salesTrackingRow['salesPerson']}}</td>
                    <td>{{$salesTrackingRow['daysAsOfToday']}}</td>
                  <? } else {
                   ?>
                    <td align="center" colspan="6">-</td>
                  <? }?>
                    <td>{{date('m/d/Y',strtotime($salesTrackingRow['contact_entered']))}}</td>
                    <td>{{$salesTrackingRow['contact_details']}}</td>
                    <td>{{$salesTrackingRow['contact_note']}}</td>
                    <td>{{$salesTrackingRow['daysSinceCreated']}}</td>
                    <td><?php if(!empty($preContactDate) && !empty($salesTrackingRow['contact_entered'])) { 
                      $date1 = new DateTime($preContactDate);
                      $date2 = new DateTime($salesTrackingRow['contact_entered']);
                      $interval = $date1->diff($date2);
                      echo $interval->d;
                      }
                    ?></td>
                  </tr>
                  <?php 
                    $preContactDate = $salesTrackingRow['contact_entered'];
                    $preLead = $salesTrackingRow['lead_id'];
                  ?>
                  @endforeach
              </tbody>