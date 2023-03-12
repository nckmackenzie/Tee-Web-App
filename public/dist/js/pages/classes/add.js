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
const form = document.querySelector('#addclass');
const saveBtn = document.querySelector('.save');
const formControls = document.querySelectorAll('input');

//form submit
form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (validation() > 0) return;
  const formData = Object.fromEntries(new FormData(this).entries());
  setLoadingState(saveBtn, 'Saving...');
  const res = await saveDetails(formData);
  resetLoadingState(saveBtn, 'Save');
  if (res && res.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    clearValues();
  }
});

async function saveDetails(formdata) {
  const response = await sendHttpRequest(
    `${HOST_URL}/classes/createupdate`,
    'POST',
    JSON.stringify(formdata),
    { 'Content-Type': 'application/json' },
    alerBox
  );

  return response;
}

clearOnChange(formControls);
