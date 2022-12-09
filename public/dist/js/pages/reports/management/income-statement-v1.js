import {
  previewBtn,
  spinnerContainer,
  tableContainer,
  setLoadingSpinner,
  clearLoadingSpinner,
  setdatatable,
} from '../utils.js';
import {
  clearOnChange,
  getRequest,
  mandatoryFields,
  validateDate,
  validation,
  HOST_URL,
} from '../../utils.js';
import { clearErrorState, numberWithCommas } from '../../../utils/utils.js';
const sdateInput = document.querySelector('#sdate');
const edateInput = document.querySelector('#to');

//preview
previewBtn.addEventListener('click', async function () {
  //validation
  clearErrorState(mandatoryFields);
  if (validation() > 0) return;
  if (!validateDate(sdateInput, edateInput)) return;
  //loading report
  setLoadingSpinner(spinnerContainer, tableContainer);
  const sdateVal = sdateInput.value || new Date();
  const edateVal = edateInput.value || new Date();
  const data = await getRequest(
    `${HOST_URL}/managementreports/incomestatementvalues?sdate=${sdateVal}&edate=${edateVal}`
  );
  clearLoadingSpinner(spinnerContainer);
  //success
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results);
    setdatatable('table', [], false, false, false);
  }
});

//create table
function createTable(data) {
  const { fee, gradfee, generalExpenses, purchases, sales } = data;
  const revenueTotal = fee + gradfee + sales;
  const expensesTotal = generalExpenses + purchases;
  const profitLoss = revenueTotal - expensesTotal;
  let html = `
      <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
          <thead>
              <tr class="">
                  <th class="border-0">Income Statement</th>
                  <th class="border-0"></th>
              </tr>
          </thead>
          <tbody>
              <tr class="bg-success text-white fw-bolder fs-5">
                  <td class="border-0">Revenues</td>
                  <td class="border-0"></td>
              </tr>
              <tr>
                  <td>Sales</td>
                  <td class="text-center">${numberWithCommas(sales)}</td>
              </tr>
              <tr>
                  <td>Fees Payments</td>
                  <td class="text-center">${numberWithCommas(fee)}</td>
              </tr>
              <tr>
                  <td>Graducation Fees</td>
                  <td class="text-center">${numberWithCommas(gradfee)}</td>
              </tr>
              <tr class="fw-bolder fs-5 text-success">
                  <td>Revenue Totals</td>
                  <td class="text-center">${numberWithCommas(revenueTotal)}</td>
              </tr>
              <tr class="bg-danger text-white fw-bolder fs-5">
                  <td class="border-0">Expenditure</td>
                  <td class="border-0"></td>
              </tr>
              <tr>
                  <td>General Expenses</td>
                  <td class="text-center">${numberWithCommas(
                    generalExpenses
                  )}</td>
              </tr>
              <tr>
                  <td>Purchases</td>
                  <td class="text-center">${numberWithCommas(purchases)}</td>
              </tr>
              <tr class="fw-bolder fs-5 text-danger">
                  <td>Expenditure Totals</td>
                  <td class="text-center">${numberWithCommas(
                    expensesTotal
                  )}</td>
              </tr>
              <tr class="fs-5 fw-bolder">
                  <td>Profit/Loss</td>
                  <td class="text-center ${
                    +profitLoss < 0 ? 'text-danger' : 'text-success'
                  }">${numberWithCommas(profitLoss)}</td>
              </tr>
          </tbody>
      </table>
    `;

  return html;
}

clearOnChange(mandatoryFields);
