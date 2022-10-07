import {
  subtotalInput,
  discountInput,
  netInput,
  paidInput,
  balanceInput,
} from './calculations.js';
export const saleTypeSelect = document.getElementById('saletype');
export const saleIdInput = document.getElementById('saleid');
export const studentSelect = document.getElementById('studentorgroup');
export const studentTable = document.getElementById('studentList');
export const selectAllBtn = document.getElementById('selectAll');
export const paymethodSelect = document.getElementById('paymethod');
export const referenceInput = document.getElementById('reference');
export const table = document.getElementById('addsale');
export const addBtn = document.querySelector('.btnadd');
export const bookSelect = document.getElementById('book');
export const stockInput = document.getElementById('stock');
export const selectedDate = document.getElementById('sdate');
export const payDate = document.getElementById('sdate');
export const alertBox = document.getElementById('message');
export const form = document.querySelector('form');
export const btn = document.querySelector('#submitBtn');
export const mandatoryFields = document.querySelectorAll('.mandatory');
const idInput = document.getElementById('id');
const isEditInput = document.getElementById('isedit');

export function headerDetails() {
  return {
    sdate: selectedDate.value,
    pdate: payDate.value,
    saleType: saleTypeSelect.value,
    buyer: studentSelect.value,
    paymethod: paymethodSelect.value,
    reference: referenceInput.value,
    subtotal: subtotalInput.value,
    discount: discountInput.value,
    net: netInput.value,
    paid: paidInput.value,
    balance: balanceInput.value,
    id: idInput.value,
    isEdit: isEditInput.value,
  };
}
