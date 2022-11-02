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
    `${HOST_URL}/budgetreports/detailedrpt?year=${yearVal}`
  );
  clearLoadingSpinner(spinnerContainer);
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results);
    setdatatable('table');
    updateColumnTotal('table', 1, 'budgeted');
    updateColumnTotal('table', 2, 'oct');
    updateColumnTotal('table', 3, 'nov');
    updateColumnTotal('table', 4, 'dec');
    updateColumnTotal('table', 5, 'jan');
    updateColumnTotal('table', 6, 'feb');
    updateColumnTotal('table', 7, 'mar');
    updateColumnTotal('table', 8, 'apr');
    updateColumnTotal('table', 9, 'may');
    updateColumnTotal('table', 10, 'jun');
    updateColumnTotal('table', 11, 'jul');
    updateColumnTotal('table', 12, 'aug');
    updateColumnTotal('table', 13, 'sep');
  }
});

function createTable(data) {
  let html = `
      <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
          <thead class="table-light">
              <tr>
                  <th>Expense Account</th>
                  <th>Budgeted Amount</th>
                  <th>Oct</th>
                  <th>Nov</th>
                  <th>Dec</th>
                  <th>Jan</th>
                  <th>Feb</th>
                  <th>Mar</th>
                  <th>Apr</th>
                  <th>May</th>
                  <th>Jun</th>
                  <th>Jul</th>
                  <th>Aug</th>
                  <th>Sep</th>
              </tr>
          </thead>
          <tbody>`;
  data.forEach(dt => {
    html += `
          <tr>
              <td>${dt.expenseAccount}</td>
              <td class="text-center">${numberWithCommas(
                dt.budgetedAmount
              )}</td>
              <td class="text-center">${numberWithCommas(dt.oct)}</td>
              <td class="text-center">${numberWithCommas(dt.nov)}</td>
              <td class="text-center">${numberWithCommas(dt.dec)}</td>
              <td class="text-center">${numberWithCommas(dt.jan)}</td>
              <td class="text-center">${numberWithCommas(dt.feb)}</td>
              <td class="text-center">${numberWithCommas(dt.mar)}</td>
              <td class="text-center">${numberWithCommas(dt.apr)}</td>
              <td class="text-center">${numberWithCommas(dt.may)}</td>
              <td class="text-center">${numberWithCommas(dt.jun)}</td>
              <td class="text-center">${numberWithCommas(dt.jul)}</td>
              <td class="text-center">${numberWithCommas(dt.aug)}</td>
              <td class="text-center">${numberWithCommas(dt.sep)}</td>
          </tr>
      `;
  });
  html += `
          </tbody>
          <tfoot>
              <tr>
                  <th>Totals</th>
                  <th class="text-center fw-bolder" id="budgeted"></th>
                  <th class="text-center fw-bolder" id="oct"></th>
                  <th class="text-center fw-bolder" id="nov"></th>
                  <th class="text-center fw-bolder" id="dec"></th>
                  <th class="text-center fw-bolder" id="jan"></th>
                  <th class="text-center fw-bolder" id="feb"></th>
                  <th class="text-center fw-bolder" id="mar"></th>
                  <th class="text-center fw-bolder" id="apr"></th>
                  <th class="text-center fw-bolder" id="may"></th>
                  <th class="text-center fw-bolder" id="jun"></th>
                  <th class="text-center fw-bolder" id="jul"></th>
                  <th class="text-center fw-bolder" id="aug"></th>
                  <th class="text-center fw-bolder" id="sep"></th>
              </tr>
          </tfoot>
      </table>
    `;
  return html;
}

clearOnChange(mandatoryFields);
