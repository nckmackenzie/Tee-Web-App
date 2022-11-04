import {
  previewBtn,
  spinnerContainer,
  tableContainer,
  setLoadingSpinner,
  clearLoadingSpinner,
  setdatatable,
  updateColumnTotal,
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
const edateInput = document.querySelector('#edate');

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
    `${HOST_URL}/managementreports/trialbalancerpt?sdate=${sdateVal}&edate=${edateVal}`
  );
  clearLoadingSpinner(spinnerContainer);
  //success
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results, sdateVal, edateVal);
    setdatatable('table', [], false, false, false);
    document.getElementById('debits').innerText = numberWithCommas(
      data.debitstotal
    );
    document.getElementById('credits').innerText = numberWithCommas(
      data.creditstotal
    );
  }
});

//create table
function createTable(data, sdate, edate) {
  let html = `
      <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
        <thead class="table-light">
            <tr>
                <th>Account</th>
                <th class="text-center">Debit</th>
                <th class="text-center">Credit</th>
            </tr>
        </thead>
        <tbody>
  `;
  data.forEach(dt => {
    const account = String(dt.account).trim().toLocaleLowerCase();
    html += `
          <tr>
            <td>${dt.account}</td>
            <td class="text-center"><a target="_blank" href="${HOST_URL}/managementreports/tbdetailed?acc=${account}?sdate=${sdate}?edate=${edate}">${numberWithCommas(
      dt.debit
    )}</a></td>
            <td class="text-center"><a target="_blank" href="${HOST_URL}/managementreports/tbdetailed?acc=${account}?sdate=${sdate}?edate=${edate}">${numberWithCommas(
      dt.credit
    )}</a></td>
          </tr>
    `;
  });
  html += `
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th id="debits"></th>
                <th id="credits"></th>
            </tr>
        </tfoot>
  `;
  return html;
}

clearOnChange(mandatoryFields);
