import {
  clearOnChange,
  mandatoryFields,
  validation,
  formatcurrencyvalue,
  setLoadingState,
  resetLoadingState,
  alerBox,
  HOST_URL,
  sendHttpRequest,
  displayAlert,
} from '../utils.js';

import { clearValues } from '../../utils/utils.js';

const form = document.getElementById('form');
const btn = document.querySelector('.save');

form.addEventListener('submit', async e => {
  e.preventDefault();
  if (validation() > 0) return;
  const balance = Number(
    formatcurrencyvalue(document.getElementById('balance').value)
  );
  //data
  const formData = Object.fromEntries(new FormData(form).entries());
  formData.balance = balance;
  setLoadingState(btn, 'Saving...');
  const res = await save(formData);
  resetLoadingState(btn, 'Save');
  if (res && res.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    clearValues();
    // loadSemisters();
  }
});

async function save(formdata) {
  const res = await sendHttpRequest(
    `${HOST_URL}/sales/receivepayment`,
    'POST',
    JSON.stringify(formdata),
    { 'Content-Type': 'application/json' },
    alerBox
  );
  return res;
}

clearOnChange(mandatoryFields);
// console.log('first');
