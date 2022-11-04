import {
  previewBtn,
  setLoadingSpinner,
  clearLoadingSpinner,
  spinnerContainer,
  tableContainer,
  setdatatable,
  updateColumnTotal,
} from '../utils.js';
import {
  validateDate,
  validation,
  getRequest,
  HOST_URL,
  mandatoryFields,
  clearOnChange,
  displayAlert,
  alerBox,
} from '../../utils.js';
import { numberWithCommas, clearErrorState } from '../../../utils/utils.js';
const sdateInput = document.querySelector('#from');
const edateInput = document.querySelector('#to');
const typeSelect = document.querySelector('#type');
const accountSelect = document.querySelector('#account');

//report type on change handler
typeSelect.addEventListener('change', function (e) {
  if (e.target.value === '') return;
  if (e.target.value === 'all') {
    accountSelect.disabled = true;
    accountSelect.value = '';
    accountSelect.classList.remove('mandatory');
  } else if (e.target.value === 'byaccount') {
    accountSelect.disabled = false;
    accountSelect.value = '';
    accountSelect.classList.add('mandatory');
  }
});

//previewBtn event handler
previewBtn.addEventListener('click', async function () {
  //input validation
  clearErrorState(mandatoryFields);
  if (validation() > 0) return;
  if (!validateDate(sdateInput, edateInput)) return;
  //report loading
  setLoadingSpinner(spinnerContainer, tableContainer);
  const sdateVal = sdateInput.value || new Date();
  const edateVal = edateInput.value || new Date();
  const typeVal = typeSelect.value || 'all';
  const accountVal =
    typeSelect.value === 'byaccount' ? accountSelect.value : null;
  const data = await getRequest(
    `${HOST_URL}/expensereports/expenserpt?type=${typeVal}&account=${accountVal}&sdate=${sdateVal}&edate=${edateVal}`
  );
  clearLoadingSpinner(spinnerContainer);
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results);
    setdatatable('table');
    updateColumnTotal('table', 3, 'totals');
    return;
  }
});

function createTable(data) {
  let html = `
    <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
      <thead class="table-light">
          <tr>
              <th>Date</th>
              <th>Voucher No</th>
              <th>Account</th>
              <th class="text-center">Amount</th>
              <th>Reference</th>
              <th>Narration</th>
          </tr>
      </thead>
      <tbody>`;
  data.forEach(dt => {
    html += `
          <tr>
             <td>${dt.expenseDate}</td>
             <td>${dt.voucherNo}</td>
             <td>${dt.account}</td>
             <td class="text-center">${numberWithCommas(dt.amount)}</td>
             <td>${dt.reference}</td>
             <td>${dt.narration}</td>
          </tr>
    `;
  });
  html += `
      </tbody>
      <tfoot>
        <tr>
          <th colspan="3">Total</th>
          <th class="text-center" id="totals"></th>
          <th colspan="2"></th>
        </tr>
      </tfoot>
    </table>
  `;
  return html;
}

//clearErrorState
clearOnChange(mandatoryFields);
