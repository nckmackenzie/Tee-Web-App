import { receiptnoInput, currentInput, btnleft, btnright } from './elements.js';
import { getReceiptNo, getFirstAndLastIds } from './ajax.js';

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
}

setReceiptNo();
setNavButtonState();
