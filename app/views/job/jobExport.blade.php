<table>
<tr>
  <td>Created Date</td>
  <td>Job Type</td>
  <td>Start</td>
  <td>Regarding</td>
  <td>Company</td>
  <td>Location</td>
  <td>Job#</td>
  <td>Address</td>
  <td>Zip</td>
  <td>State</td>
  <td>City</td>
  <td>Scheduled With</td>
  <td>Contact Phn/Ext</td>
  <td>Special Instructions</td>
  <td>Contract Amount</td>
  <td>Invoice Amount</td>
  <td>Tax</td>
  <td>Invoice Amt (net)</td>
  <td>Invoice Number</td>
  <td>Invoice Date</td>
  <td>Material Budgeted</td>
  <td>Material Actual</td>
  <td>Labor Budgeted Hours</td>
  <td>Labor Actual Hours</td>
  <td>Labor Budgeted</td>
  <td>Labor Actual</td>
  <td>Date Job Won</td>
  <td>Date Equ. Ord.</td>
  <td>Date Equ. Eng.</td>
  <td>Date Permit Ord.</td>
  <td>Date Permit Exp.</td>
  <td>Exp. Compl. Date</td>
  </tr>
 @foreach($jobRecord as $jobRow )
 <tr>
  <td>{{(($jobRow['created_on']!="" && $jobRow['created_on']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['created_on'])):'')}}</td>
  <td>{{$jobRow['job_type']}}</td>
  <td>{{(($jobRow['schedule_date']!="" && $jobRow['schedule_date']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['schedule_date'])):'')}}</td>
  <td> {{$jobRow['task']}}</td>
  <td> {{$jobRow['cus_name']}}</td>
  <td> {{$jobRow['location']}}</td>
  <td> {{$jobRow['job_num']}}</td>
  <td> {{$jobRow['address1']}}</td>
  <td>{{$jobRow['zip']}}</td>
  <td>{{$jobRow['state']}}</td>
  <td>{{$jobRow['city']}}</td>
  <td></td>
  <td>{{$jobRow['phone']}}</td>
  <td>&nbsp;</td>
  <td></td>
  <td></td>
  <td></td>
  <td></td>
  <td>{{$jobRow['invoice_number']}}</td>
  <td>{{(($jobRow['invoice_date']!="" && $jobRow['invoice_date']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['invoice_date'])):'')}}</td>
  <td></td>
  <td></td>
  <td>{{$jobRow['budgeted_hours']}}</td>
  <td></td>
  <td></td>
  <td></td>
  <td>{{(($jobRow['date_job_won']!="" && $jobRow['date_job_won']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['date_job_won'])):'')}}</td>
  <td>{{(($jobRow['date_eqp_ordered']!="" && $jobRow['date_eqp_ordered']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['date_eqp_ordered'])):'')}}</td>
  <td>{{(($jobRow['date_eqp_engaged']!="" && $jobRow['date_eqp_engaged']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['date_eqp_engaged'])):'')}}</td>
  <td>{{(($jobRow['date_permit_ordered']!="" && $jobRow['date_permit_ordered']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['date_permit_ordered'])):'')}}</td>
  <td>{{(($jobRow['date_permit_expected']!="" && $jobRow['date_permit_expected']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['date_permit_expected'])):'')}}</td>
  <td>{{(($jobRow['date_completion']!="" && $jobRow['date_completion']!="0000-00-00")?date('m/d/Y',strtotime($jobRow['date_completion'])):'')}}</td>
  </tr>
 @endforeach
 </table>