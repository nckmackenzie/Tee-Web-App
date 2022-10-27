//prettier-ignore
import { receiptnoInput, currentInput, btnleft, btnright,form,paydateInput,savebtn } from './elements.js';
import {
  getReceiptNo,
  getFirstAndLastIds,
  saveGraduationPayment,
} from './ajax.js';
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

//set button state of navigation buttons
async function setNavButtonState() {
  //   if (receiptnoInput.value === '') return;
  const { first, last } = await getFirstAndLastIds(); //gt first and last ids
  const currentId = currentInput.value || 0;
  //if current matches both first and last
  if (+currentId === +last && +currentId === +first) {
    btnleft.disabled = btnright.disabled = true;
  }
  //if current matches both first but less last
  if (+currentId === +first && +currentId < +last) {
    btnleft.disabled = true;
    btnright.disabled = false;
  }
  //if not first and less than last
  if (+currentId > +first && +currentId < +last) {
    btnleft.disabled = btnright.disabled = false;
  }
  //if current equal to last and greater than first
  if (+currentId > +last) {
    btnleft.disabled = false;
    btnright.disabled = true;
  }
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
    setNavButtonState();
  }
});

clearOnChange(mandatoryFields);

setReceiptNo();
setNavButtonState();
