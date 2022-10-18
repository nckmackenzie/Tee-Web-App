import { numberFormatter } from '../../utils/utils.js';
export const receiptNoInput = document.querySelector('#receiptno');
export const studentSelect = document.querySelector('#student');
export const semisterSelect = document.querySelector('#semister');
export const balanceBfInput = document.querySelector('#balancebf');
export const semisterFeeInput = document.querySelector('#semisterfees');
export const totalPaidInput = document.querySelector('#totalpaid');
export const balanceInput = document.querySelector('#balance');
export const currentPaymentInput = document.querySelector('#amount');
export const paymentDate = document.querySelector('#pdate');
export const form = document.querySelector('#feePayForm');
export const saveBtn = document.querySelector('.save');

//calculate balance
export function calculateOpeningBalance() {
  const balanceBf = parseFloat(numberFormatter(balanceBfInput.value)) || 0;
  const semisterFee = parseFloat(numberFormatter(semisterFeeInput.value)) || 0;
  const totalPaid = parseFloat(numberFormatter(totalPaidInput.value)) || 0;
  return balanceBf + semisterFee - totalPaid;
}
