import { validatedate } from '../utils.js';
import { loadDueDate } from './dueInvoicesTable.js';
import { HOST_URL } from '../../utils.js';
const reportTypeSelect = document.getElementById('type');
const supplierSelect = document.getElementById('supplier');
const startInput = document.getElementById('start');
const endInput = document.getElementById('end');
const previewBtn = document.querySelector('.preview');
const startspan = document.querySelector('.startspan');
const endspan = document.querySelector('.endspan');
const typespan = document.querySelector('.typespan');
const supplierspan = document.querySelector('.supplierspan');

reportTypeSelect.addEventListener('change', e => {
  clearErrors();
  startInput.value = endInput.value = supplierSelect.value = '';
  if (String(e.target.value) === 'bydate') {
    startInput.disabled = endInput.disabled = false;
    supplierSelect.disabled = true;
  } else if (String(e.target.value) === 'bysupplier') {
    startInput.disabled = endInput.disabled = true;
    supplierSelect.disabled = false;
  } else {
    startInput.disabled = endInput.disabled = supplierSelect.disabled = true;
  }
});

previewBtn.addEventListener('click', function () {
  clearErrors();
  if (reportTypeSelect.value === '') {
    reportTypeSelect.classList.add('is-invalid');
    typespan.textContent = 'Select report type';
    return;
  } else if (reportTypeSelect.value === 'bysupplier') {
    supplierSelect.classList.add('is-invalid');
    supplierspan.textContent = 'Select report type';
    return;
  } else if (reportTypeSelect.value === 'bydate') {
    if (!validatedate(startInput, endInput, startspan, endspan)) return;
  }
  loadDueDate();
});

export async function fetchReport() {
  let res;
  if (reportTypeSelect.value === 'due') {
    res = await fetch(`${HOST_URL}/invoicereports/duerpt`);
  }

  return await res.json();
}

function clearErrors() {
  document.querySelectorAll('.control').forEach(select => {
    select.classList.remove('is-invalid');
  });
  document.querySelectorAll('.invalid-feedback').forEach(span => {
    span.textContent = '';
  });
}
