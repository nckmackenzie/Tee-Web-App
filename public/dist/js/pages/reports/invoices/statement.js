import {
  clearErrors,
  setdatatable,
  numberWithCommas,
  updateColumnTotal,
} from '../utils.js';
import { getSupplierStatement } from './paymentsAjax.js';

const startInput = document.getElementById('start');
const endInput = document.getElementById('end');
const supplierSelect = document.getElementById('supplier');
const startspan = document.querySelector('.startspan');
const endspan = document.querySelector('.endspan');
const previewBtn = document.querySelector('.preview');
const controls = document.querySelectorAll('.control');

//preview button click handler
previewBtn.addEventListener('click', function () {
  clearErrors(); //clear error state
  let errorCount = 0;
  //validate
  controls.forEach(cntrl => {
    if (cntrl.value === '') {
      cntrl.classList.add('is-invalid');
      cntrl.nextSibling.nextSibling.textContent = 'field is required';
      errorCount++;
    }
  });
  if (errorCount > 0) return;
  //check if start > end
  if (
    new Date(startInput.value).getTime() > new Date(endInput.value).getTime()
  ) {
    startInput.classList.add('is-invalid');
    startspan.textContent = 'cannot be greater than end date';
    endInput.classList.add('is-invalid');
    endspan.textContent = 'cannot be less than start date';
    return;
  }
  createTable();
});

async function createTable() {
  const supplier = +supplierSelect.value;
  const sdate = startInput.value;
  const edate = endInput.value;
  const data = await getSupplierStatement(supplier, sdate, edate);
  if (!data) {
    alert("Couldn't fetch supplier statement");
    return;
  }
  let table = `
    <table class="table table-sm w-100 dt-responsive nowrap" id="table">
      <thead class="table-light">
        <tr>
          <th>Transaction Date</th>
          <th>Description</th>
          <th>Reference</th>
          <th>Credit</th>
          <th>Debit</th>
        </tr>
      </thead>
      <tbody>`;
  if (data.constructor === Array && data.length > 0) {
    data.forEach(dt => {
      table += `
            <tr>
              <td>${dt.transactionDate}</td>
              <td>${dt.narration}</td>
              <td>${dt.reference}</td>
              <td>${numberWithCommas(dt.credit)}</td>
              <td>${numberWithCommas(dt.debit)}</td>
            </tr>
          `;
    });
  }
  table += `
      </tbody>
      <tfoot class="table-light">
        <th colspan="3" style="text-align:center">Total:</th>
        <th id="debits"></th>
        <th id="credits"></th>
      </tfoot>
    </table>
  `;
  results.innerHTML = table;
  setdatatable('table');
  updateColumnTotal('table', 3, 'debits');
  updateColumnTotal('table', 4, 'credits');
}
