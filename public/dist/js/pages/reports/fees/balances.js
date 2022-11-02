import {
  spinnerContainer,
  tableContainer,
  previewBtn,
  setLoadingSpinner,
  clearLoadingSpinner,
  numberWithCommas,
  setdatatable,
  updateColumnTotal,
} from '../utils.js';
import {
  mandatoryFields,
  clearOnChange,
  validation,
  getRequest,
  HOST_URL,
} from '../../utils.js';
const semisterSelect = document.querySelector('#semister');
//click handler
previewBtn.addEventListener('click', async function () {
  if (validation() > 0) return; //validate and set error state
  //set loading spinner
  setLoadingSpinner(spinnerContainer, tableContainer);
  const semister = semisterSelect.value || 0;
  const data = await getRequest(
    `${HOST_URL}/feereports/getbalancerpt?semister=${semister}`
  );
  clearLoadingSpinner(spinnerContainer);
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results);
    setdatatable('table');
    updateColumnTotal('table', 1, 'openingbal');
    updateColumnTotal('table', 2, 'semfees');
    updateColumnTotal('table', 3, 'paid');
    updateColumnTotal('table', 4, 'balance');
  }
});

//create html table
function createTable(data) {
  let html = `
    <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
        <thead class="table-light">
            <tr>
                <th>Student Name</th>
                <th class="text-center">Opening Bal</th>
                <th class="text-center">Semister Fees</th>
                <th class="text-center">Amount Paid</th>
                <th class="text-center">Balance</th>
            </tr>
        </thead>
        <tbody>`;
  data.forEach(dt => {
    html += `
         <tr>
            <td>${dt.studentName}</td>
            <td class="text-center">${numberWithCommas(dt.openingBal)}</td>
            <td class="text-center">${numberWithCommas(dt.semisterFees)}</td>
            <td class="text-center">${numberWithCommas(dt.amountPaid)}</td>
            <td class="text-center">${numberWithCommas(dt.balance)}</td>
         </tr>
    `;
  });
  html += `
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th class="text-center" id="openingbal"></th>
                <th class="text-center" id="semfees"></th>
                <th class="text-center" id="paid"></th>
                <th class="text-center" id="balance"></th>
            </tr>
        </tfoot>
    </table>
  `;

  return html;
}
clearOnChange(mandatoryFields); //clear error state
