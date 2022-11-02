import {
  spinnerContainer,
  tableContainer,
  previewBtn,
  setLoadingSpinner,
  clearLoadingSpinner,
  setdatatable,
  updateColumnTotal,
} from '../utils.js';
import { clearErrorState, numberWithCommas } from '../../../utils/utils.js';
import {
  clearOnChange,
  mandatoryFields,
  validateDate,
  validation,
  HOST_URL,
  getRequest,
} from '../../utils.js';
const sdateInput = document.querySelector('#start');
const edateInput = document.querySelector('#end');

previewBtn.addEventListener('click', async function () {
  clearErrorState(mandatoryFields); //clear error state on all elements
  //validations
  if (validation() > 0) return;
  if (!validateDate(sdateInput, edateInput)) return;
  //set loading spinner
  setLoadingSpinner(spinnerContainer, tableContainer);
  const sdate = sdateInput.value || new Date();
  const edate = edateInput.value || new Date();
  const data = await getRequest(
    `${HOST_URL}/feereports/graduationfeesrpt?sdate=${sdate}&edate=${edate}`
  );
  clearLoadingSpinner(spinnerContainer); //clear loading spinner
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results);
    setdatatable('table');
    updateColumnTotal('table', 3, 'amounttotal');
  }
});

//create html table
function createTable(data) {
  let html = `
      <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
          <thead class="table-light">
              <tr>
                  <th>Payment Date</th>
                  <th>Receipt No</th>
                  <th>Student</th>
                  <th class="text-center">Amount Paid</th>
                  <th>Reference</th>
              </tr>
          </thead>
          <tbody>`;
  data.forEach(dt => {
    html += `
           <tr>
              <td>${dt.paymentDate}</td>
              <td>${dt.receiptNo}</td>
              <td>${dt.studentName}</td>
              <td class="text-center">${numberWithCommas(dt.amount)}</td>
              <td>${dt.paymentReference}</td>
           </tr>
      `;
  });
  html += `
          </tbody>
          <tfoot>
              <tr>
                  <th colspan="3">Total</th>
                  <th class="text-center" id="amounttotal"></th>
                  <th></th>
              </tr>
          </tfoot>
      </table>
    `;

  return html;
}

clearOnChange(mandatoryFields); // clear error state on element change
