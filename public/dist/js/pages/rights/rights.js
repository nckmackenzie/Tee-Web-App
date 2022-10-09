import {
  sendHttpRequest,
  displayAlert,
  HOST_URL,
  setLoadingState,
  resetLoadingState,
} from '../utils.js';
const form = document.getElementById('rights-form');
const userSelect = document.getElementById('user');
const table = document.getElementById('rights');
const span = document.querySelector('.invalid-feedback');
const spinnerContainer = document.querySelector('.spinner-container');
const tableContainer = document.querySelector('.rights-container');
const alertBox = document.getElementById('alertBox');
const btn = document.querySelector('.btn-block');
let user;
// const checkBox = document.querySelectorAll('access');

userSelect.addEventListener('change', async function (e) {
  if (e.target.value == '' || !e.target.value) return;
  user = e.target.value;
  setLoadingSpinner();
  const data = await sendHttpRequest(
    `${HOST_URL}/userrights/getrightsassigned?userid=${user}`,
    'GET',
    undefined,
    {},
    alertBox
  );
  removeLoadingSpinner();
  if (data && data.length > 0) {
    setTable(data);
  }
});

function setLoadingSpinner() {
  tableContainer.querySelector('.card').classList.add('d-none');
  table.getElementsByTagName('tbody')[0].innerHTML = '';
  const html = `<div class="spinner md"></div>`;
  spinnerContainer.innerHTML = html;
}

function setTable(forms) {
  const tbody = table.getElementsByTagName('tbody')[0];
  forms.forEach(form => {
    let html = `
        <tr>
            <td class="d-none formid">${form.ID}</td>
            <td>${form.FormName}</td>
            <td>${form.Module}</td>
            <td>
              <div class="form-check mb-2">
                <input type="checkbox" name="active" class="form-check-input" ${
                  +form.access === 1 && 'checked'
                }>
              </div>
            </td>
        </tr>
    `;
    tbody.insertAdjacentHTML('beforeend', html);
  });
}

function removeLoadingSpinner() {
  tableContainer.querySelector('.card').classList.remove('d-none');
  table.getElementsByTagName('tbody')[0].innerHTML = '';
  spinnerContainer.innerHTML = '';
}

function tableData() {
  const trs = table.getElementsByTagName('tbody')[0].querySelectorAll('tr');
  const tableData = [];
  trs.forEach(tr => {
    const formId = tr.querySelector('.formid').textContent;
    const access = tr.querySelector('.form-check-input').checked;
    tableData.push({ formId, access });
  });
  return tableData;
}

form.addEventListener('submit', async function (e) {
  e.preventDefault();

  const formData = { user: user, tableData: tableData() };
  setLoadingState(btn, 'Saving...');
  const response = await sendHttpRequest(
    `${HOST_URL}/userrights/createupdate`,
    'POST',
    JSON.stringify(formData),
    { 'Content-Type': 'application/json' },
    alertBox
  );
  resetLoadingState(btn, 'Save');
  if (response?.success) {
    displayAlert(alertBox, 'Rights assigned successfully', 'success');
    resetWindow();
  }
});

function resetWindow() {
  userSelect.value = '';
  tableContainer.querySelector('.card').classList.add('d-none');
  table.getElementsByTagName('tbody')[0].innerHTML = '';
}
