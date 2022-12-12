import {
  clearOnChange,
  mandatoryFields,
  validation,
  validateDate,
  getRequest,
  HOST_URL,
} from '../../utils.js';
import { setdatatable, numberWithCommas, updateColumnTotal } from '../utils.js';

const startInput = document.querySelector('#start');
const endInput = document.querySelector('#end');
const previewBtn = document.querySelector('.preview');
const results = document.querySelector('#results');
const typeSelect = document.querySelector('#type');

previewBtn.addEventListener('click', async function () {
  if (validation() > 0) return;
  if (!validateDate(startInput, endInput)) return;
  const type = typeSelect.value || 'all';
  const sdate = startInput.value || new Date();
  const edate = endInput.value || new Date();
  const url = `${HOST_URL}/feereports/feepaymentsrpt?type=${type}&sdate=${sdate}&edate=${edate}`;
  const res = await getRequest(url);
  if (res && res.success) {
    results.innerHTML = settable(type, res.data);
    setdatatable('table');
    if (type === 'all') {
      updateColumnTotal('table', 3, 'amount');
    } else {
      updateColumnTotal('table', 1, 'amount');
    }
  }
});

function settable(type, data) {
  let html;
  if (type === 'all') {
    html = `
    <table class="table table-sm table-bordered w-100 dt-responsive nowrap" id="table">
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
        html += `
            <tr>
              <td>${dt.paymentDate}</td>
              <td>${dt.receiptNo}</td>
              <td>${dt.studentName}</td>
              <td>${numberWithCommas(dt.amount)}</td>
              <td>${dt.paymentReference}</td>
            </tr>
          `;
      });
    }
    html += `
      </tbody>
      <tfoot class="table-light">
        <tr>
          <th colspan="3" style="text-align:center">Total:</th>
          <th id="amount"></th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  `;
  } else {
    html = `
    <table class="table table-sm table-bordered w-100 dt-responsive nowrap" id="table">
      <thead class="table-light">
        <tr>
          <th>Course Name</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>`;
    if (data.length > 0) {
      data.forEach(dt => {
        html += `
            <tr>
              <td>${dt.course}</td>
              <td>${numberWithCommas(dt.value)}</td>
            </tr>
          `;
      });
    }
    html += `
      </tbody>
      <tfoot class="table-light">
        <tr>
          <th style="text-align:center">Total:</th>
          <th id="amount"></th>
        </tr>
      </tfoot>
    </table>
  `;
  }

  return html;
}

clearOnChange(mandatoryFields);
