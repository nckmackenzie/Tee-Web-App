import { validatedate, clearErrors } from '../utils.js';
import { getSupplierOrInvoice, getSupplierPayments } from './paymentsAjax.js';
import { numberWithCommas, setdatatable, updateColumnTotal } from '../utils.js';
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
  createTable();
});

async function getData() {
  let data;
  if (reportTypeSelect.value === 'byinvoice') {
    data = await getSupplierPayments(
      'byinvoice',
      null,
      null,
      String(criteriaSelect.value).trim()
    );
  } else if (reportTypeSelect.value === 'bysupplier') {
    data = await getSupplierPayments(
      'bysupplier',
      startInput.value,
      endInput.value,
      String(criteriaSelect.value).trim()
    );
  } else if (reportTypeSelect.value === 'bydate') {
    data = await getSupplierPayments(
      'bydate',
      startInput.value,
      endInput.value
    );
  }
  return data;
}

async function createTable() {
  const data = await getData();
  if (!data) return;
  let table = `
  <table class="table table-sm w-100 dt-responsive nowrap" id="table">
    <thead class="table-light">
      <tr>
        <th>Payment Date</th>
        <th>Invoice #</th>
        <th>Supplier</th>
        <th>Amount Paid</th>
        <th>Reference</th>
      </tr>
    </thead>
    <tbody>`;
  if (data.length > 0) {
    data.forEach(dt => {
      table += `
          <tr>
            <td>${dt.paymentDate}</td>
            <td>${dt.invoiceNo}</td>
            <td>${dt.supplierName}</td>
            <td>${numberWithCommas(dt.amount)}</td>
            <td>${dt.paymentReference}</td>
          </tr>
        `;
    });
  }
  table += `
    </tbody>
    <tfoot class="table-light">
      <th colspan="3" style="text-align:center">Total:</th>
      <th id="total"></th>
      <th></th>
    </tfoot>
  </table>
`;
  results.innerHTML = table;
  setdatatable('table');
  updateColumnTotal('table', 3, 'total');
}
