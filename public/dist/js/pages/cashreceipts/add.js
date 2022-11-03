import {
  validation,
  mandatoryFields,
  clearOnChange,
  HOST_URL,
  sendHttpRequest,
  setLoadingState,
  resetLoadingState,
  alerBox,
  displayAlert,
  getRequest,
} from '../utils.js';
import {
  clearErrorState,
  dateNotGreaterToday,
  clearValues,
} from '../../utils/utils.js';

const receiptDateInput = document.querySelector('#receiptdate');
const receiptNoInput = document.querySelector('#receiptno');
const form = document.querySelector('#receipt-form');
const saveBtn = document.querySelector('.save');

//form submission
form.addEventListener('submit', async function (e) {
  e.preventDefault();
  //validation
  clearErrorState(mandatoryFields);
  if (validation() > 0) return;
  if (!dateNotGreaterToday(receiptDateInput)) return;
  //form submission
  setLoadingState(saveBtn, 'Saving...');
  const data = await saveCashReceipt();
  resetLoadingState(saveBtn, 'Save');
  if (data && data.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    clearValues();
    receiptNoInput.value = await getNewReceiptNo();
  }
});

async function saveCashReceipt() {
  const formdata = Object.fromEntries(new FormData(form).entries());
  const res = await sendHttpRequest(
    `${HOST_URL}/pettycashreceipts/createupdate`,
    'POST',
    JSON.stringify(formdata),
    { 'Content-Type': 'application/json' },
    alerBox
  );

  return res;
}

async function getNewReceiptNo() {
  const res = await getRequest(`${HOST_URL}/pettycashreceipts/getnewid`);
  if (res && res.success) {
    return res.newid;
  } else {
    return null;
  }
}
clearOnChange(mandatoryFields); //clear error state on element change
