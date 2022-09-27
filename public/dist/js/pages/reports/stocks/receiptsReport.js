import { validatedate, setdatatable } from '../utils.js';
import { HOST_URL } from '../../utils.js';
const start = document.querySelector('#start');
const startspan = document.querySelector('.startspan');
const endspan = document.querySelector('.endspan');
const end = document.querySelector('#end');
const type = document.querySelector('#type');
const preview = document.querySelector('.preview');
const results = document.querySelector('#results');

preview.addEventListener('click', async () => {
  if (!validatedate(start, end, startspan, endspan)) return;
  const sdate = start.value;
  const edate = end.value;
  const res = await fetch(
    `${HOST_URL}/reports/feepaymentsrpt?sdate=${sdate}&edate=${edate}`
  );
  const data = await res.json();
  let table = `
  <table class="table table-sm w-100 dt-responsive nowrap" id="table">
    <thead class="table-light">
      <tr>
        <th>Payment Date</th>
        <th>Receipt No</th>
        <th>Student</th>
        <th>Amount</th>
        <th>Reference</th>
      </tr>
    </thead>
    <tbody>`;
  if (data.length > 0) {
    data.forEach(dt => {
      table += `
          <tr>
            <td>${dt.paymentDate}</td>
            <td>${dt.receiptNo}</td>
            <td>${dt.studentName}</td>
            <td>${dt.amount}</td>
            <td>${dt.paymentReference}</td>
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
