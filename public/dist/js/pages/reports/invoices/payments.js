import { validatedate, clearErrors } from '../utils.js';
import { getSupplierOrInvoice } from './paymentsAjax.js';
const reportTypeSelect = document.getElementById('type');
const criteriaSelect = document.getElementById('criteria');
const startInput = document.getElementById('start');
const endInput = document.getElementById('end');
const criteriaLabel = document.getElementById('criteriaLabel');
const previewBtn = document.querySelector('.preview');
const startspan = document.querySelector('.startspan');
const endspan = document.querySelector('.endspan');
const typespan = document.querySelector('.typespan');
const criteriaspan = document.querySelector('.criteriaspan');

reportTypeSelect.addEventListener('change', e => {
  clearErrors();
  startInput.value = endInput.value = criteriaSelect.value = '';
  criteriaLabel.textContent = 'Suppliers';
  if (String(e.target.value) === 'bydate') {
    startInput.disabled = endInput.disabled = false;
    criteriaSelect.disabled = true;
  } else if (String(e.target.value) === 'bysupplier') {
    startInput.disabled = endInput.disabled = false;
    criteriaSelect.disabled = false;
    updateCriteriaSelect('bysupplier');
  } else if (String(e.target.value) === 'byinvoice') {
    startInput.disabled = endInput.disabled = true;
    criteriaSelect.disabled = false;
    criteriaLabel.textContent = 'Invoices';
    updateCriteriaSelect('byinvoice');
  }
});

async function updateCriteriaSelect(type) {
  const data = await getSupplierOrInvoice(type);
  criteriaSelect.innerHTML = '';
  data.forEach(dt => {
    let html = `
        <option value="${dt.id}">${dt.field}</option>
    `;
    criteriaSelect.insertAdjacentHTML('afterbegin', html);
  });
  criteriaSelect.insertAdjacentHTML(
    'afterbegin',
    '<option value="" selected disabled>Select option</option>'
  );
}

previewBtn.addEventListener('click', function () {
  clearErrors();
  if (reportTypeSelect.value === '') {
    reportTypeSelect.classList.add('is-invalid');
    typespan.textContent = 'Select report type';
    return;
  } else if (reportTypeSelect.value === 'bydate') {
    if (!validatedate(startInput, endInput, startspan, endspan)) return;
  } else if (
    reportTypeSelect.value === 'bysupplier' &&
    criteriaSelect.value == ''
  ) {
    criteriaSelect.classList.add('is-invalid');
    criteriaspan.textContent = 'Select supplier';
    return;
  } else if (
    reportTypeSelect.value === 'bysupplier' &&
    criteriaSelect.value !== ''
  ) {
    if (!validatedate(startInput, endInput, startspan, endspan)) return;
  } else if (
    reportTypeSelect.value === 'byinvoice' &&
    criteriaSelect.value == ''
  ) {
    criteriaSelect.classList.add('is-invalid');
    criteriaspan.textContent = 'Select invoice';
    return;
  }
});
