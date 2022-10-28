//prettier-ignore
import { receiptnoInput, currentInput, form,paydateInput,savebtn,
         spinnerContainer,idInput,iseditInput,studentSelect,groupSelect,amountInput
        ,paymethodSelect,referenceInput,searchForm,accountSelect,resetbtn } from './elements.js';
import { getReceiptNo, saveGraduationPayment, getTransaction } from './ajax.js';
import {
  validation,
  clearOnChange,
  mandatoryFields,
  displayAlert,
  setLoadingState,
  resetLoadingState,
  alerBox,
} from '../utils.js';
import { clearValues, dateNotGreaterToday } from '../../utils/utils.js';

async function setReceiptNo() {
  const currentReceiptNo = await getReceiptNo();
  receiptnoInput.value = currentInput.value = currentReceiptNo;
}

//form submit
form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (validation() > 0) return;
  if (!dateNotGreaterToday(paydateInput)) return;
  const formdata = Object.fromEntries(new FormData(this).entries());
  setLoadingState(savebtn, 'Saving...');
  const res = await saveGraduationPayment(formdata);
  resetLoadingState(savebtn, 'Save');
  if (res && res.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    clearValues();
    setReceiptNo();
  }
});

searchForm.addEventListener('submit', async function (e) {
  e.preventDefault();
  const searchfield = this.querySelector('input[type=search]');
  if (searchfield.value === '') return;
  loadingState();
  const res = await getTransaction(+searchfield.value);
  resetState();
  if (res && res.success) {
    bindValues(res.results);
    searchfield.value = '';
    return;
  }
  clearValues();
  setReceiptNo();
});

resetbtn.addEventListener('click', function () {
  clearValues();
  setReceiptNo();
});

function loadingState() {
  form.classList.add('d-none');
  spinnerContainer.innerHTML = '<div class="spinner md"></div>';
}

function resetState() {
  spinnerContainer.innerHTML = '';
  form.classList.remove('d-none');
}

function bindValues(result) {
  receiptnoInput.value = result.receiptNo;
  paydateInput.value = result.paymentDate;
  studentSelect.value = result.student;
  groupSelect.value = result.group || '';
  amountInput.value = result.amount;
  accountSelect.value = result.account;
  paymethodSelect.value = result.paymethod;
  referenceInput.value = result.payreference;
  idInput.value = result.id;
  iseditInput.value = 1;
  savebtn.disabled = !result.allowEdit;
}

clearOnChange(mandatoryFields);

setReceiptNo();
