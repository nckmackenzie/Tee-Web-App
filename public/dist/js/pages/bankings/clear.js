import {
  mandatoryFields,
  clearOnChange,
  validateDate,
  validation,
  displayAlert,
  alerBox,
  setLoadingState,
  resetLoadingState,
} from '../utils.js';
import { getTransactions, clearBankings } from './ajax.js';
import { clearValues, numberWithCommas } from '../../utils/utils.js';
//var declarations
const previewBtn = document.querySelector('.preview');
const sdateInput = document.querySelector('#sdate');
const typeSelect = document.querySelector('#type');
const edateInput = document.querySelector('#edate');
const bankingsDiv = document.querySelector('#bankings');
const spinnerContainer = document.querySelector('.spinner-container');
const saveBtn = document.querySelector('.save');
const form = document.querySelector('#clear-form');
const table = document.querySelector('#table');
const tbody = table.getElementsByTagName('tbody')[0];

//preview button click handler
previewBtn.addEventListener('click', async function () {
  mandatoryFields.forEach(control => control.classList.remove('is-invalid'));
  if (validation() > 0) return;
  if (!validateDate(sdateInput, edateInput)) return;
  loadingState();
  const type = typeSelect.value || 0;
  const sdate = sdateInput.value || new Date();
  const edate = edateInput.value || new Date();
  const data = await getTransactions(type, sdate, edate);
  resetState();
  if (data && data.success) {
    bankingsDiv.classList.remove('d-none');
    tbody.innerHTML = createTable(data.results);
  }
});

//table event listener
table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('chkbx')) return;
  const chkbx = e.target;
  const tr = chkbx.closest('tr');
  const input = tr.querySelector('.cleardate');
  if (chkbx.checked) {
    input.readOnly = false;
  } else {
    input.readOnly = true;
    input.value = '';
  }
});

form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (Number(tbody.rows.length) === 0) {
    displayAlert(alerBox, 'No transactions to clear');
    return;
  }
  if (getTableData().length === 0) {
    displayAlert(alerBox, 'No transactions to clear');
    return;
  }
  setLoadingState(saveBtn, 'Saving...');
  const formdata = { details: getTableData() };
  const res = await clearBankings(formdata);
  resetLoadingState(saveBtn, 'Save');
  if (res && res.success) {
    displayAlert(alerBox, 'Saved successfully', 'success');
    clearValues();
    bankingsDiv.classList.add('d-none');
    tbody.innerHTML = '';
  }
});

clearOnChange(mandatoryFields);

function loadingState() {
  bankingsDiv.classList.add('d-none');
  spinnerContainer.innerHTML = '<div class="spinner md"></div>';
  tbody.innerHTML = '';
}

function resetState() {
  spinnerContainer.innerHTML = '';
}

function createTable(data) {
  let html = ``;
  data.forEach(dt => {
    html += `
            <tr>
                <td class="d-none id">${dt.id}</td>
                <td>
                    <div class="form-check">
                        <input type="checkbox" name="active" class="form-check-input chkbx">
                    </div>
                </td>
                <td><input type="date" class="form-control form-control-sm cleardate" readonly></td>
                <td class="tdate">${dt.transactionDate}</td>
                <td>${numberWithCommas(dt.amount)}</td>
                <td>${dt.reference}</td>
                <td>${dt.type}</td>
            </tr>
        `;
  });
  return html;
}

function getTableData() {
  const tableData = [];
  const trs = tbody.querySelectorAll('tr');
  if (+trs.length > 0) {
    trs.forEach(tr => {
      const chkbx = tr.querySelector('.chkbx');
      if (chkbx.checked) {
        const id = tr.querySelector('.id').innerText;
        const clearDate = tr.querySelector('.cleardate').value;
        const tdate = tr.querySelector('.tdate').innerText;
        tableData.push({ id, clearDate, tdate });
      }
    });
  }

  return tableData;
}
