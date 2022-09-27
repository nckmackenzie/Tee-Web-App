import { validatedate, setdatatable } from '../utils.js';
import { HOST_URL } from '../../utils.js';
const start = document.querySelector('#start');
const startspan = document.querySelector('.startspan');
const endspan = document.querySelector('.endspan');
const end = document.querySelector('#end');
const centerToSelect = document.querySelector('#centerto');
const preview = document.querySelector('.preview');
const results = document.querySelector('#results');

preview.addEventListener('click', async () => {
  if (!validatedate(start, end, startspan, endspan)) return;
  const sdate = start.value;
  const edate = end.value;
  const centerValue = +centerToSelect.value;
  const res = await fetch(
    `${HOST_URL}/stockreports/transfersrpt?sdate=${sdate}&edate=${edate}&center=${centerValue}`
  );
  const data = await res.json();
  let table = `
  <table class="table table-sm w-100 dt-responsive nowrap" id="table">
    <thead class="table-light">
      <tr>
        <th>Transfer Date</th>
        <th>MTN No</th>
        <th>Center To</th>
        <th>Book Title</th>
        <th>Qty</th>
      </tr>
    </thead>
    <tbody>`;
  if (data.length > 0) {
    data.forEach(dt => {
      table += `
          <tr>
            <td>${dt.transferDate}</td>
            <td>${dt.mtnNo}</td>
            <td>${dt.centerTo}</td>
            <td>${dt.bookTitle}</td>
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
