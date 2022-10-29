import {
  clearValues,
  dateNotGreaterToday,
  numberWithCommas,
} from '../../utils/utils.js';
import {
  validation,
  mandatoryFields,
  clearOnChange,
  displayAlert,
  alerBox,
  setLoadingState,
  resetLoadingState,
  HOST_URL,
  sendHttpRequest,
} from '../utils.js';
const table = document.getElementById('invoices-table');
const totalInput = document.getElementById('total');
const form = document.getElementById('invoices-form');
const savebtn = document.querySelector('.btn-block');
const paymethodSelect = document.getElementById('paymethod');
const paydateInput = document.getElementById('paydate');
let selected = 0;

//listener for checknbox
table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('chkbx')) return;
  const chkbox = e.target;
  const tr = chkbox.closest('tr');
  const inputs = tr.querySelectorAll('.form-control');
  if (chkbox.checked) {
    selected++;
    inputs.forEach(control => (control.readOnly = false));
  } else {
    inputs.forEach(control => {
      control.readOnly = true;
      control.value = '';
    });
    selected--;
  }
});

//payment value entry
table.addEventListener('change', function (e) {
  if (!e.target.classList.contains('payment')) return;
  updateSubTotal(this);
});

//form submit event
form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (validation() > 0) return;
  if (!dateNotGreaterToday(paydateInput)) return;
  if (+selected === 0) {
    displayAlert(alerBox, 'Please select invoices to pay');
    return;
  }
  //ajax request
  setLoadingState(savebtn, 'Saving...');
  const response = await savePayments();
  resetLoadingState(savebtn, 'Save');
  if (response && response.success) {
    displayAlert(alerBox, 'Payments saved successfully', 'success');
    clearValues();
    table.getElementsByTagName('tbody')[0].innerHTML = '';
    setTimeout(() => {
      window.location.replace(`${HOST_URL}/payments`);
    }, 2000);
  }
});

//get totals
function updateSubTotal(table) {
  let sumVal = 0;
  for (var i = 1; i < table.rows.length; i++) {
    const rowValue = parseFloat(table.rows[i].cells[7].children[0].value) || 0;
    sumVal = sumVal + rowValue;
  }

  totalInput.value = numberWithCommas(sumVal.toFixed(2));
}

async function savePayments() {
  const headerdata = {
    paydate: paydateInput.value,
    paymethod: paymethodSelect.value,
  };
  const formData = { header: headerdata, details: getTableData() };

  const res = await sendHttpRequest(
    `${HOST_URL}/payments/create`,
    'POST',
    JSON.stringify(formData),
    { 'Content-Type': 'application/json' },
    alerBox
  );

  return res;
}
//get table data
function getTableData() {
  const tableData = [];
  const trs = table.getElementsByTagName('tbody')[0].querySelectorAll('tr');
  trs.forEach(tr => {
    const chkbx = tr.querySelector('.chkbx');
    if (chkbx.checked) {
      const invoiceid = tr.querySelector('.invoiceid').value;
      const sid = tr.querySelector('.sid').value;
      const cheque = tr.querySelector('.payreferece').value;
      const balance = parseFloat(tr.querySelector('.balance').innerText);
      const payment = parseFloat(tr.querySelector('.payment').value);
      tableData.push({ invoiceid, sid, cheque, payment, balance });
    }
  });

  return tableData;
}

clearOnChange(mandatoryFields);
