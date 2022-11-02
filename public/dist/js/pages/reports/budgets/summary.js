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
  //   setLoadingSpinner(spinnerContainer, tableContainer);
  //   const yearVal = yearSelect.value || 0;
  //   const data = await getRequest(`${HOST_URL}/budgetreports?year=${yearVal}`);
  //   clearLoadingSpinner(spinnerContainer);
  //   if (data && data.success) {
  //     tableContainer.innerHTML = createTable(data.results);
  //     setdatatable('table');
  //     updateColumnTotal('table', 1, 'budgeted');
  //     updateColumnTotal('table', 2, 'expensed');
  //     updateColumnTotal('table', 3, 'variance');
  //   }
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
            <td>${numberWithCommas(dt.budgetedAmount)}</td>
            <td>${numberWithCommas(dt.expensedAmount)}</td>
            <td>${numberWithCommas(dt.variance)}</td>
        </tr>
    `;
  });
  html += `
        </tbody>
        <tfoot>
            <tr>
                <th>Totals</th>
                <th id="budgeted"></th>
                <th id="expensed"></th>
                <th id="variance"></th>
            </tr>
        </tfoot>
    </table>
  `;
  return html;
}

clearOnChange(mandatoryFields);
