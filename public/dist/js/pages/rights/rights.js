import { sendHttpRequest, displayAlert, HOST_URL } from '../utils.js';
const form = document.getElementById('rights-form');
const userSelect = document.getElementById('user');
const table = document.getElementById('rights');
const span = document.querySelector('.invalid-feedback');
const spinnerContainer = document.querySelector('.spinner-container');
const tableContainer = document.querySelector('.rights-container');
const alertBox = document.getElementById('alertBox');
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

form.addEventListener('submit', function (e) {
  e.preventDefault();

  document.form.submit();
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
                <input type="checkbox" name="active" class="form-check-input" id="active" ${
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
