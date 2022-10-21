//prettier-ignore
import {studentSelect,semisterSelect,semisterFeeInput,balanceBfInput,totalPaidInput,
        balanceInput,form,saveBtn,calculateOpeningBalance,paymentDate,receiptNoInput} from './elements-calculations.js';
//prettier-ignore
import { getRequest,validation, HOST_URL,sendHttpRequest,displayAlert,alerBox,clearOnChange,
         mandatoryFields,setLoadingState,resetLoadingState } from '../utils.js';
//prettier-ignore
import { numberWithCommas, dateNotGreaterToday,clearValues } from '../../utils/utils.js';

//get fee payment details
async function getPreviousTermDetails() {
  const studentVal = studentSelect.value;
  const semisterVal = semisterSelect.value;
  if (!studentVal || !semisterVal || studentVal === '' || semisterVal === '')
    return;
  const data = await getRequest(
    `${HOST_URL}/fees/getfeepaymentdetails?semister=${semisterVal}&student=${studentVal}`
  );
  if (data) {
    balanceBfInput.value = numberWithCommas(data.balanceBf);
    semisterFeeInput.value = numberWithCommas(data.semisterFee);
    totalPaidInput.value = numberWithCommas(data.semisterPaid);
    balanceInput.value = numberWithCommas(calculateOpeningBalance());
  }
}

//change/blur events for controls
studentSelect.addEventListener('change', getPreviousTermDetails);
semisterSelect.addEventListener('change', getPreviousTermDetails);

//form submit
form.addEventListener('submit', async function (e) {
  e.preventDefault();
  //validation
  if (validation() > 0) return;
  if (!dateNotGreaterToday(paymentDate)) return;

  setLoadingState(saveBtn, 'Saving...');
  const res = await savePayment();
  resetLoadingState(saveBtn, 'Save');
  if (res && res.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    clearValues();
    receiptNoInput.value = await getNewId();
    studentSelect.disabled = false;
    semisterSelect.disabled = false;
  }
});

clearOnChange(mandatoryFields);

async function savePayment() {
  const formData = Object.fromEntries(new FormData(form).entries());
  const res = await sendHttpRequest(
    `${HOST_URL}/fees/createupdate`,
    'POST',
    JSON.stringify(formData),
    { 'Content-Type': 'application/json' },
    alerBox
  );

  return res;
}

async function getNewId() {
  return await getRequest(`${HOST_URL}/fees/newid`);
}
