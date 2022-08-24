import { getSelectedText, dateFormat, displayAlert } from '../utils.js';
import {
  getDebitsCreditsTotal,
  debitsTotalInput,
  creditsTotalInput,
} from './calculations.js';
const jdateInput = document.getElementById('jdate');
const accountSelect = document.getElementById('account');
const typeSelect = document.getElementById('type');
const amountInput = document.getElementById('amount');
const jdateSpan = document.getElementById('jdate_span');
const accountSpan = document.getElementById('account_span');
const typeSpan = document.getElementById('type_span');
const amountSpan = document.getElementById('amount_span');
const addBtn = document.getElementById('addbtn');
const table = document.getElementById('details');
const form = document.querySelector('form');
const alertBox = document.getElementById('alertBox');

function singleValidate(elm, msg, span, span_err) {
  if (!elm.value) {
    span_err = msg;
    span.textContent = span_err;
    elm.classList.add('is-invalid');
  } else {
    span_err = '';
    span.textContent = span_err;
    elm.classList.remove('is-invalid');
  }
}

function validate() {
  let jdate_err = '';
  let account_err = '';
  let type_err = '';
  let amount_err = '';

  singleValidate(jdateInput, 'Please enter a valid date', jdateSpan, jdate_err);
  singleValidate(
    accountSelect,
    'Please select account.',
    accountSpan,
    account_err
  );
  singleValidate(typeSelect, 'Please select debit/credit.', typeSpan, type_err);
  singleValidate(amountInput, 'Please enter amount.', amountSpan, amount_err);

  if (
    jdate_err !== '' ||
    account_err !== '' ||
    amount_err !== '' ||
    type_err !== ''
  ) {
    return false;
  } else if (
    jdate_err === '' &&
    account_err === '' &&
    amount_err === '' &&
    type_err === ''
  ) {
    return true;
  }
}

function clear() {
  jdateInput.value =
    accountSelect.value =
    typeSelect.value =
    amountInput.value =
      '';
}

function appendToTable() {
  const accountName = getSelectedText(accountSelect);
  const accountValue = accountSelect.value;
  const jdateValue = dateFormat(jdateInput.value);
  const typeName = getSelectedText(typeSelect);
  const amountValue = amountInput.value;
  const body = table.getElementsByTagName('tbody')[0];

  let html = `
      <tr>
        <td style="width:15%"><input type="text" class="table-input" name="jdates[]" value="${jdateValue}" readonly></td>
        <td class="d-none"><input type="text" name="accountsid[]" value="${accountValue}" readonly></td>
        <td><input type="text" class="table-input w-100" name="accountsname[]" value="${accountName}" readonly></td>
        <td style="width:15%"><input type="text" class="table-input" name="types[]" value="${typeName}" readonly></td>
        <td style="width:15%"><input type="text" class="table-input" name="gross[]" value="${amountValue}" readonly></td>
        <td style="width:10%"><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
      </tr>
  `;
  let newRow = body.insertRow(body.rows.length);
  newRow.innerHTML = html;
  clear();
  getDebitsCreditsTotal(table);
}

addBtn.addEventListener('click', function () {
  if (!validate()) return;
  appendToTable();
});

table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('btndel')) return;
  const btn = e.target;
  btn.closest('tr').remove();
});

form.addEventListener('submit', function (e) {
  e.preventDefault();
  const body = document
    .getElementById('details')
    .getElementsByTagName('tbody')[0];

  if (Number(body.rows.length) === 0) {
    displayAlert(alertBox, 'Add journal entries');
    return;
  }
  if (
    parseFloat(debitsTotalInput.value) !== parseFloat(creditsTotalInput.value)
  ) {
    displayAlert(alertBox, "Debit and Credits don't match");
    return;
  }
  document.form.submit();
});
