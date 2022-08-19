import { formatcurrencyvalue } from '../utils.js';
const invoiceAmountInput = document.querySelector('#invoiceamount');
const totalAmountPaidInput = document.querySelector('#amountpaid');
const currentPaymentInput = document.querySelector('#currentamount');
const closingBalanceInput = document.querySelector('#currentbalance');

function calculateClosingBalance() {
  const invoiceAmount =
    parseFloat(formatcurrencyvalue(invoiceAmountInput.value)) || 0;
  const totalPaid =
    parseFloat(formatcurrencyvalue(totalAmountPaidInput.value)) || 0;
  const currentPayment =
    parseFloat(formatcurrencyvalue(currentPaymentInput.value)) || 0;
  const closingBalance = invoiceAmount - (totalPaid + currentPayment);
  closingBalanceInput.value = closingBalance.toFixed(2);
}

currentPaymentInput.addEventListener('blur', calculateClosingBalance);
