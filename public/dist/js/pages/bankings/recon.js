import { clearErrorState, numberWithCommas } from '../../utils/utils.js';
import { setdatatable } from '../reports/utils.js';
import {
  clearOnChange,
  HOST_URL,
  mandatoryFields,
  validateDate,
  validation,
} from '../utils.js';
import { getBankingValues } from './ajax.js';
const previewBtn = document.querySelector('.preview');
const sdateInput = document.querySelector('#sdate');
const edateInput = document.querySelector('#edate');
const balanceInput = document.querySelector('#balance');
const spinnerContainer = document.querySelector('.spinner-container');
const tableContainer = document.querySelector('.table-responsive');

//preview button click handler
previewBtn.addEventListener('click', async function () {
  clearErrorState(mandatoryFields);
  if (validation() > 0) return;
  if (!validateDate(sdateInput, edateInput)) return;
  loadingStatus();
  const sdateVal = sdateInput.value || new Date();
  const edateVal = edateInput.value || new Date();
  const data = await getBankingValues(sdateVal, edateVal);
  clearLoadingState();
  if (data && data.success) {
    const { values } = data;
    tableContainer.innerHTML = createTable(values, sdateVal, edateVal);
    setdatatable('table');
  }
});

clearOnChange(mandatoryFields);

function loadingStatus() {
  tableContainer.innerHTML = '';
  spinnerContainer.innerHTML = '<div class="spinner md"></div>';
}

function createTable(data, sdate, edate) {
  const {
    cleareddeposits,
    clearedwithdrawals,
    uncleareddeposits,
    unclearedwithdrawals,
  } = data;
  const balance = balanceInput.value;
  const variance = balance - (cleareddeposits - clearedwithdrawals);
  let html = `
    <table class="table table-sm table-bordered" id="table">
        <thead class="table-light">
            <tr>
                <th>Description</th>
                <th class="text-center">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Balance</td>
                <td class="text-center">${numberWithCommas(balance)}</td>
            </tr>
            <tr>
                <td>Cleared Deposits</td>
                <td class="text-center">${
                  cleareddeposits == 0 ? '-' : numberWithCommas(cleareddeposits)
                }</td>
            </tr>
            <tr>
                <td>Cleared Withdrawals</td>
                <td class="text-center">${
                  clearedwithdrawals == 0
                    ? '-'
                    : numberWithCommas(clearedwithdrawals)
                }</td>
            </tr>
            <tr class="text-info fw-bolder">
                <td>Variance</td>
                <td class="text-center">${numberWithCommas(variance)}</td>
            </tr>
            <tr>
                <td>Uncleared Deposits</td>
                <td class="text-center">${
                  uncleareddeposits === 0
                    ? '-'
                    : `<a target="_blank" href="${HOST_URL}/bankings/uncleared?type=${`deposits`}&sdate=${sdate}&edate=${edate}">${numberWithCommas(
                        uncleareddeposits
                      )}</a>`
                }</td>
            </tr>
            <tr>
                <td>Uncleared Withdrawals</td>
                <td class="text-center">${
                  unclearedwithdrawals === 0
                    ? '-'
                    : `<a target="_blank" href="${HOST_URL}/bankings/uncleared?type=${`withdrawals`}&sdate=${sdate}&edate=${edate}">${numberWithCommas(
                        unclearedwithdrawals
                      )}</a>`
                }</td>
            </tr>
        </tbody>
    </table>
  `;

  return html;
}

function clearLoadingState() {
  spinnerContainer.innerHTML = '';
}
