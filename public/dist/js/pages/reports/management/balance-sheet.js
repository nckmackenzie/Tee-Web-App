import { dateNotGreaterToday } from '../../../utils/utils.js';
import {
  validation,
  clearOnChange,
  mandatoryFields,
  getRequest,
  HOST_URL,
} from '../../utils.js';
import {
  clearLoadingSpinner,
  setLoadingSpinner,
  spinnerContainer,
} from '../utils.js';
const asofInput = document.querySelector('#asof');
const previewBtn = document.querySelector('.preview');
const tableContainer = document.querySelector('.table-responsive');

//preview click handler
previewBtn.addEventListener('click', async function () {
  if (validation() > 0) return;
  if (!dateNotGreaterToday(asofInput)) return;
  setLoadingSpinner(spinnerContainer, tableContainer);
  const data = await getBalanceSheet();
  clearLoadingSpinner(spinnerContainer);
  if (data && data.success) {
    tableContainer.innerHTML = data.markup;
  }
});

//get balance sheet
async function getBalanceSheet() {
  const date = asofInput.value;
  return await getRequest(
    `${HOST_URL}/managementreports/balancesheetrpt?date=${date}`
  );
}

clearOnChange(mandatoryFields);
