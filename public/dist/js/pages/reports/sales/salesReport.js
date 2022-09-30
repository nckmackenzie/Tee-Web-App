import {
  validatedate,
  setdatatable,
  updateColumnTotal,
  numberWithCommas,
} from '../utils.js';
import { HOST_URL } from '../../utils.js';
const start = document.querySelector('#start');
const startspan = document.querySelector('.startspan');
const endspan = document.querySelector('.endspan');
const end = document.querySelector('#end');
const preview = document.querySelector('.preview');
const results = document.querySelector('#results');

preview.addEventListener('click', async () => {
  if (!validatedate(start, end, startspan, endspan)) return;
  const sdate = start.value;
  const edate = end.value;
  const res = await fetch(
    `${HOST_URL}/reports/salesrpt?sdate=${sdate}&edate=${edate}`
  );
  const data = await res.json();
  let table = `
    <table class="table table-sm w-100 dt-responsive nowrap" id="table">
      <thead class="table-light">
        <tr>
          <th>Sale ID</th>
          <th>Sales Date</th>
          <th>Sold To</th>
          <th>Sub Total</th>
          <th>Discount</th>
          <th>Net Amount</th>
          <th>Reference</th>
        </tr>
      </thead>
      <tbody>`;
  if (data.length > 0) {
    data.forEach(dt => {
      table += `
            <tr>
              <td>${dt.saleId}</td>
              <td>${dt.salesDate}</td>
              <td>${dt.soldTo}</td>
              <td>${numberWithCommas(dt.subTotal)}</td>
              <td>${numberWithCommas(dt.discount)}</td>
              <td>${numberWithCommas(dt.netAmount)}</td>
              <td>${dt.reference}</td>
            </tr>
          `;
    });
  }
  table += `
      </tbody>
      <tfoot class="table-light">
        <th colspan="3" style="text-align:center">Total:</th>
        <th id="subtotal"></th>
        <th id="discount"></th>
        <th id="netamount"></th>
        <th></th>
      </tfoot>
    </table>
  `;
  results.innerHTML = table;
  setdatatable('table');
  updateColumnTotal('table', 3, 'subtotal');
  updateColumnTotal('table', 4, 'discount');
  updateColumnTotal('table', 5, 'netamount');
});
