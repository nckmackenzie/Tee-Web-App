import {
  previewBtn,
  spinnerContainer,
  tableContainer,
  setLoadingSpinner,
  clearLoadingSpinner,
  numberWithCommas,
  setdatatable,
  updateColumnTotal,
} from '../utils.js';
import {
  validation,
  clearOnChange,
  mandatoryFields,
  getRequest,
  HOST_URL,
} from '../../utils.js';

const yearSelect = document.querySelector('#year');
//preview button handler
previewBtn.addEventListener('click', async function () {
  if (validation() > 0) return;
  setLoadingSpinner(spinnerContainer, tableContainer);
  const yearVal = yearSelect.value || 0;
  const data = await getRequest(
    `${HOST_URL}/budgetreports/summaryrpt?year=${yearVal}`
  );
  clearLoadingSpinner(spinnerContainer);
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results);
    setdatatable('table');
    updateColumnTotal('table', 1, 'budgeted');
    updateColumnTotal('table', 2, 'expensed');
    updateColumnTotal('table', 3, 'variance');
  }
});

function createTable(data) {
  let html = `
    <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
        <thead class="table-light">
            <tr>
                <th>Expense Account</th>
                <th>Budgeted Amount</th>
                <th>Expensed Amount</th>
                <th>Variance</th>
            </tr>
        </thead>
        <tbody>`;
  data.forEach(dt => {
    html += `
        <tr>
            <td>${dt.expenseAccount}</td>
            <td class="text-center">${numberWithCommas(dt.budgetedAmount)}</td>
            <td class="text-center">${numberWithCommas(dt.expensedAmount)}</td>
            <td class="text-center fw-bolder ${
              parseFloat(dt.variance) > 0 ? 'text-success' : 'text-danger'
            }">${numberWithCommas(dt.variance)}</td>
        </tr>
    `;
  });
  html += `
        </tbody>
        <tfoot>
            <tr>
                <th>Totals</th>
                <th class="text-center fw-bolder" id="budgeted"></th>
                <th class="text-center fw-bolder" id="expensed"></th>
                <th class="text-center fw-bolder" id="variance"></th>
            </tr>
        </tfoot>
    </table>
  `;
  return html;
}

clearOnChange(mandatoryFields);
