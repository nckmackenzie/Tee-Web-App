import { fetchReport } from './dueInvoices.js';
import { setdatatable, updateColumnTotal } from '../utils.js';
export async function loadDueDate() {
  const data = await fetchReport();
  let table = `
    <table class="table table-sm w-100 dt-responsive nowrap" id="table">
      <thead class="table-light">
        <tr>
          <th>Invoice Date</th>
          <th>Due Date</th>
          <th>Invoice #</th>
          <th>Supplier</th>
          <th>Invoice Value</th>
          <th>Amount Paid</th>
          <th>Balance</th>
        </tr>
      </thead>
      <tbody>`;
  if (data.length > 0) {
    data.forEach(dt => {
      table += `
            <tr>
              <td>${dt.invoiceDate}</td>
              <td>${dt.dueDate}</td>
              <td>${dt.invoiceNo}</td>
              <td>${dt.supplierName}</td>
              <td>${dt.invoiceValue}</td>
              <td>${dt.amountPaid}</td>
              <td>${dt.balance}</td>
            </tr>
          `;
    });
  }
  table += `
      </tbody>
      <tfoot class="table-light">
        <th colspan="4" style="text-align:center">Total:</th>
        <th id="ivalue"></th>
        <th id="amountpaid"></th>
        <th id="balance"></th>
      </tfoot>
    </table>
  `;
  results.innerHTML = table;
  setdatatable('table');
  updateColumnTotal('table', 4, 'ivalue');
  updateColumnTotal('table', 5, 'amountpaid');
  updateColumnTotal('table', 6, 'balance');
}
