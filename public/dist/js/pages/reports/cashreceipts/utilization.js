import {
  spinnerContainer,
  tableContainer,
  setLoadingSpinner,
  clearLoadingSpinner,
  previewBtn,
  setdatatable,
  updateColumnTotal,
} from '../utils.js';
import {
  mandatoryFields,
  clearOnChange,
  getRequest,
  HOST_URL,
  validateDate,
  validation,
} from '../../utils.js';
import { clearErrorState, numberWithCommas } from '../../../utils/utils.js';
const sdateInput = document.querySelector('#from');
const edateInput = document.querySelector('#to');

//preview button handler
previewBtn.addEventListener('click', async function () {
  clearErrorState(mandatoryFields);
  if (validation() > 0) return;
  if (!validateDate(sdateInput, edateInput)) return;
  setLoadingSpinner(spinnerContainer, tableContainer);
  const sdateVal = sdateInput.value || new Date();
  const edateVal = edateInput.value || new Date();
  //ajax request to get report
  const data = await getRequest(
    `${HOST_URL}/pettycashreports/utilizationrpt?sdate=${sdateVal}&edate=${edateVal}`
  );
  clearLoadingSpinner(spinnerContainer);
  //loading done
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results);
    setdatatable('table');
    updateColumnTotal('table', 2, 'debits');
    updateColumnTotal('table', 3, 'credits');
  }
});

function createTable(data) {
  let html = `
    <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
      <thead class="table-light">
          <tr>
              <th>Date</th>
              <th>Reference</th>
              <th class="text-center">Debit</th>
              <th class="text-center">Credit</th>
              <th>Narration</th>
          </tr>
      </thead>
      <tbody>`;
  data.forEach(dt => {
    html += `
          <tr>
             <td>${dt.date}</td>
             <td>${dt.reference}</td>
             <td class="text-center">${
               parseFloat(dt.debit) === 0 ? '-' : numberWithCommas(dt.debit)
             }</td>
             <td class="text-center">${
               parseFloat(dt.credit) === 0 ? '-' : numberWithCommas(dt.credit)
             }</td>
             <td>${dt.narration}</td>
          </tr>
    `;
  });
  html += `
      </tbody>
      <tfoot>
        <tr>
          <th colspan="2">Total</th>
          <th class="text-center" id="debits"></th>
          <th class="text-center" id="credits"></th>
          <th></th>
        </tr>
      </tfoot>
    </table>
  `;
  return html;
}

clearOnChange(mandatoryFields);
