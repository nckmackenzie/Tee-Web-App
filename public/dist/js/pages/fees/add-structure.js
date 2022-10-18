import {
  clearOnChange,
  mandatoryFields,
  validation,
  HOST_URL,
  sendHttpRequest,
  alerBox,
  setLoadingState,
  resetLoadingState,
  getRequest,
  displayAlert,
} from '../utils.js';
const form = document.querySelector('#structureForm');
const saveBtn = document.querySelector('.save');
const semisterSelect = document.querySelector('#semister');
const amountInput = document.querySelector('#amount');

//validate semister
semisterSelect.addEventListener('change', async function (e) {
  if (!e.target.value || e.target.value === '') return;
  const semisterFeeDefined = await getRequest(
    `${HOST_URL}/fees/checksemister?semister=${e.target.value}`
  );
  if (+semisterFeeDefined > 0) {
    this.classList.add('is-invalid');
    this.nextSibling.nextSibling.textContent =
      'Semister fee structure already defined';
  }
});

form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (validation() > 0) return;
  //get form data
  const formData = Object.fromEntries(new FormData(this).entries());

  setLoadingState(saveBtn, 'Saving...');

  //POST REQUEST
  const res = await sendHttpRequest(
    `${HOST_URL}/fees/createupdatestructure`,
    'POST',
    JSON.stringify(formData),
    { 'Content-Type': 'application/json' },
    alerBox
  );

  resetLoadingState(saveBtn, 'Save');
  if (res && res.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    semisterSelect.value = amountInput.value = '';
  }
});

clearOnChange(mandatoryFields);
