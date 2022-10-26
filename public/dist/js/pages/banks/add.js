import { clearValues } from '../../utils/utils.js';
import {
  clearOnChange,
  displayAlert,
  alerBox,
  validation,
  setLoadingState,
  resetLoadingState,
  sendHttpRequest,
  HOST_URL,
} from '../utils.js';
const form = document.querySelector('#addbank');
const saveBtn = document.querySelector('.save');
const formControls = document.querySelectorAll('input');

//form submit
form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (validation() > 0) return;
  if (!validateAsOfDate()) return;
  const formData = Object.fromEntries(new FormData(this).entries());
  setLoadingState(saveBtn, 'Saving...');
  const res = await saveDetails(formData);
  resetLoadingState(saveBtn, 'Save');
  if (res && res.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    clearValues();
  }
});

//validate date
function validateAsOfDate() {
  const isedit = document.querySelector('#isedit').value;
  if (isedit !== '') return true;
  const balanceInput = document.querySelector('#openingbal');
  const asOfInput = document.querySelector('#asof');
  if (balanceInput.value !== '' && asOfInput.value === '') {
    asOfInput.classList.add('is-invalid');
    asOfInput.nextSibling.nextSibling.textContent = 'Field is required';
    return false;
  }
  if (new Date(asOfInput.value).getTime() > new Date().getTime()) {
    asOfInput.classList.add('is-invalid');
    asOfInput.nextSibling.nextSibling.textContent = 'Invalid date selected';
    return false;
  }
  return true;
}

async function saveDetails(formdata) {
  const response = await sendHttpRequest(
    `${HOST_URL}/banks/createupdate`,
    'POST',
    JSON.stringify(formdata),
    { 'Content-Type': 'application/json' },
    alerBox
  );

  return response;
}

clearOnChange(formControls);
