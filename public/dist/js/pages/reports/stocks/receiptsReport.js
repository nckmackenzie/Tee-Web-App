import { validatedate, setdatatable } from '../utils.js';
import { HOST_URL } from '../../utils.js';
const start = document.querySelector('#start');
const startspan = document.querySelector('.startspan');
const endspan = document.querySelector('.endspan');
const end = document.querySelector('#end');
const typeSelect = document.querySelector('#type');
const preview = document.querySelector('.preview');
const results = document.querySelector('#results');

preview.addEventListener('click', async () => {
  if (!validatedate(start, end, startspan, endspan)) return;
  const sdate = start.value;
  const edate = end.value;
  const typeValue = +typeSelect.value;
  const res = await fetch(
    `${HOST_URL}/stockreports/receiptsrpt?sdate=${sdate}&edate=${edate}&type=${typeValue}`
  );
  const data = await res.json();
  let table = `
  <table class="table table-sm w-100 dt-responsive nowrap" id="table">
    <thead class="table-light">
      <tr>
        <th>Receipt Date</th>
        <th>Receipt Type</th>
        <th>GRN</th>
        <th>Center From</th>
        <th>Book Title</th>
        <th>Qty</th>
      </tr>
    </thead>
    <tbody>`;
  if (data.length > 0) {
    data.forEach(dt => {
      table += `
          <tr>
            <td>${dt.receiptDate}</td>
            <td>${dt.receiptType}</td>
            <td>${dt.grnNo}</td>
            <td>${dt.fromCenter}</td>
            <td>${dt.book}</td>
            <td>${dt.qty}</td>
          </tr>
        `;
    });
  }
  table += `
    </tbody>
  </table>
`;
  results.innerHTML = table;
  setdatatable('table');
});
