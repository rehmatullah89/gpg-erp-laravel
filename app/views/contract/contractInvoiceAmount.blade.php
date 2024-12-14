<?php $grandTotal = 0; ?>
<section id="no-more-tables">
  <table class='table table-bordered table-striped table-condensed cf' align='center'>
    @foreach($attachContractJobs as $row)
    <tr>
      <td><strong>{{ $row->job_num }}</strong></td>
    </tr>
    <tr>
      <th>Tax Amt</th>
      <th>Labor Cost</th>
      <th>Material Cost</th>
      <th>Sales Comm.</th>
      <th>&nbsp;</th>
    </tr>
    <tr>
      <td data-title="Tax Amt">{{ Generic::currency_format($row->tax_amount) }}</td>
      <td data-title="Labor Cost">{{ Generic::currency_format($row->labor_cost) }}</td>
      <td data-title="Material Cost">{{ Generic::currency_format($row->material_cost) }}</td>
      <td data-title="Sales Comm.">{{ Generic::currency_format($row->sales_commission) }}</td>
      <td data-title="">&nbsp;</td>
    </tr>
    <tr>
      <th>Invoice#</th>
      <th>Invoice Date</th>
      <th>Invoice Amount</th>
      <th>Sales Tax Amount</th>
      <th>Net Invoice Amount</th>
    </tr>
    @foreach($row->invoiceData as $invoiceDataRow)
    <tr>
      <td data-title="Invoice#">{{ $invoiceDataRow->invoice_number }}</td>
      <td data-title="Invoice Date">{{ date(Config::get('settings._DateFormat'), strtotime($invoiceDataRow->invoice_date)) }}</td>
      <td data-title="Invoice Amount">{{ Generic::currency_format($invoiceDataRow->invoice_amount) }}</td>
      <td data-title="Sales Tax Amount">{{ Generic::currency_format($invoiceDataRow->tax_amount) }}</td>
      <td data-title="Net Invoice Amount">{{ $invAmt = ($invoiceDataRow->invoice_amount != 0) ? Generic::currency_format($invoiceDataRow->invoice_amount - $invoiceDataRow->tax_amount) : 0 }}  <?php $grandTotal += $invoiceDataRow->invoice_amount - $invoiceDataRow->tax_amount?></td>
    </tr>
    @endforeach
    <tr>
      <td colspan="5">&nbsp;</td>
    </tr>
    @endforeach
    <tr>
      <td colspan="4"><strong>G R A N D &nbsp;&nbsp;&nbsp;&nbsp;T O T A L</strong></td>
      <td>{{ Generic::currency_format($grandTotal) }}</td>
    </tr>
  </table>
</section>