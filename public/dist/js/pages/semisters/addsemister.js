import {
  validation,
  clearOnChange,
  mandatoryFields,
  validateDate,
  setLoadingState,
  resetLoadingState,
  sendHttpRequest,
  alerBox,
  displayAlert,
  HOST_URL,
} from '../utils.js';

const sdateInput = document.getElementById('startdate');
const edateInput = document.getElementById('enddate');
const form = document.getElementById('semisterForm');
const saveBtn = document.querySelector('.save');

//for sumbit
form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (validation() > 0) return;
  if (!validateDate(sdateInput, edateInput)) return;
  setLoadingState(saveBtn, 'Saving...');
  const res = await saveSemister();
  resetLoadingState(saveBtn, 'Save');
  if (res && res.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    mandatoryFields.forEach(field => (field.value = '')); //reset form controls
  }
});

clearOnChange(mandatoryFields);

async function saveSemister() {
  const formData = Object.fromEntries(new FormData(form).entries());
  const res = await sendHttpRequest(
    `${HOST_URL}/semisters/createupdate`,
    'POST',
    JSON.stringify(formData),
    { 'Content-Type': 'application/json' },
    alerBox
  );
  return res;
}
