import { validatedate, setdatatable, updateColumnTotal } from '../utils.js';
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
    `${HOST_URL}/stockreports/movementsrpt?sdate=${sdate}&edate=${edate}`
  );
  const data = await res.json();
  let table = `
  <table class="table table-sm w-100 dt-responsive nowrap" id="table">
    <thead class="table-light">
      <tr>
        <th>Book Title</th>
        <th>Opening Bal</th>
        <th>Receipts</th>
        <th>Transfers</th>
        <th>Sales</th>
        <th>Balance</th>
      </tr>
    </thead>
    <tbody>`;
  if (data.length > 0) {
    data.forEach(dt => {
      table += `
          <tr>
            <td>${dt.bookTitle}</td>
            <td>${dt.openingBal}</td>
            <td>${dt.receipts}</td>
            <td>${dt.transfers}</td>
            <td>${dt.sales}</td>
            <td>${dt.balance}</td>
          </tr>
        `;
    });
  }
  table += `
    </tbody>
    <tfoot class="table-light">
      <tr>
        <th>Total</th>
        <th id="opening"></th>
        <th id="receipts"></th>
        <th id="transfers"></th>
        <th id="sales"></th>
        <th id="balance"></th>
      </tr>
    </tfoot>
  </table>
`;
  results.innerHTML = table;
  setdatatable('table');
  updateColumnTotal('table', 1, 'opening');
  updateColumnTotal('table', 2, 'receipts');
  updateColumnTotal('table', 3, 'transfers');
  updateColumnTotal('table', 4, 'sales');
  updateColumnTotal('table', 5, 'balance');
});
