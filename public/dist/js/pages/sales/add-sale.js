import { validateSelectedStudents } from './studentGroupHandler.js';
import { submitHandler } from './formSubmit.js';
//prettier ignore
import {
  HOST_URL,
  displayAlert,
  getSelectedText,
  validation,
  clearOnChange,
  setLoadingState,
  resetLoadingState,
  sendHttpRequest,
} from '../utils.js';
//prettier ignore
import {
  qtyInput,
  rateInput,
  valueInput,
  calculateTotalValue,
  updateSubTotal,
  subtotalInput,
  deliveryInput,
  summaryCalculations,
  discountInput,
  paidInput,
} from './calculations.js';
//prettier ignore
import {
  table,
  addBtn,
  bookSelect,
  stockInput,
  selectedDate,
  alertBox,
  form,
  mandatoryFields,
  saleTypeSelect,
  studentTable,
  btn,
  saleIdInput,
} from './elements.js';

function resetAndGetTotal() {
  bookSelect.value = '';
  qtyInput.value = '';
  value.value = '';
  rateInput.value = '';
  stockInput.value = '';
  updateSubTotal(table);
}

qtyInput.addEventListener('change', calculateTotalValue);

//get current and rate
bookSelect.addEventListener('change', async e => {
  const selected = +e.target.value;
  const res = await fetch(
    `${HOST_URL}/sales/getstockandrate?bookid=${selected}&date=${selectedDate.value}`
  );
  const data = await res.json();
  stockInput.value = data.stock;
  rateInput.value = data.rate;
  calculateTotalValue();
});

addBtn.addEventListener('click', () => {
  if (!qtyInput.value) {
    displayAlert(alertBox, 'Qty is required');
    return;
  }
  if (!bookSelect.value || !rateInput.value || !stockInput.value) {
    displayAlert(alertBox, 'Book not selected');
    return;
  }
  if (+qtyInput.value > +stockInput.value) {
    displayAlert(alertBox, 'Qty must be less than or equal to available stock');
    return;
  }
  var rows = table.rows;
  for (var i = 1; i < rows.length; i++) {
    var cols = rows[i].cells;
    if (Number(cols[0].children[0].value) === +bookSelect.value) {
      cols[3].children[0].value =
        parseFloat(cols[3].children[0].value) + parseFloat(qtyInput.value);
      cols[4].children[0].value =
        parseFloat(cols[4].children[0].value) + parseFloat(valueInput.value);
      resetAndGetTotal();
      summaryCalculations();
      return;
    }
  }
  const body = document
    .getElementById('addsale')
    .getElementsByTagName('tbody')[0];
  let html = `
      <tr>
        <td class="d-none"><input type="text" class="bid" name="booksid[]" value="${
          bookSelect.value
        }" readonly></td>
        <td><input type="text" class="table-input w-100 bname" name="booksname[]" value="${getSelectedText(
          bookSelect
        )}" readonly></td>
        <td><input type="text" class="table-input rate" name="rates[]" value="${
          rateInput.value
        }" readonly></td>
        <td><input type="text" class="table-input qty" name="qtys[]" value="${
          qtyInput.value
        }" readonly></td>
        <td><input type="text" class="table-input value" name="values[]" value="${
          valueInput.value
        }" readonly></td>
        <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
      </tr>
  `;
  let newRow = body.insertRow(body.rows.length);
  newRow.innerHTML = html;
  resetAndGetTotal();
  summaryCalculations();
});

//remove row
table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('btndel')) return;
  const btn = e.target;
  btn.closest('tr').remove();
  updateSubTotal(table);
  summaryCalculations();
});

discountInput.addEventListener('change', summaryCalculations);
paidInput.addEventListener('change', summaryCalculations);
deliveryInput.addEventListener('change', summaryCalculations);

form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (validation() > 0) return;

  //validate group
  if (saleTypeSelect.value === 'group' && validateSelectedStudents() === 0) {
    displayAlert(alertBox, 'Select at least one student');
    return;
  }

  const body = document
    .getElementById('addsale')
    .getElementsByTagName('tbody')[0];

  if (Number(body.rows.length) === 0) {
    displayAlert(alertBox, 'Add Items');
    return false;
  }

  if (+paidInput.value > +subtotalInput.value) {
    displayAlert(alertBox, 'Payment more than sale value');
    return;
  }

  setLoadingState(btn, 'Saving...');
  const res = await submitHandler();
  resetLoadingState(btn, 'Save');
  if (res) {
    displayAlert(alertBox, 'Saved Successfully', 'success');
    clearValues();
  }
  // document.salesform.submit();
});

clearOnChange(mandatoryFields);

async function clearValues() {
  const fields = form.querySelectorAll('input');
  const selects = document.querySelectorAll('select');
  fields.forEach(field => {
    field.value = '';
  });
  selects.forEach(select => {
    select.value = '';
  });
  const tbody = table.getElementsByTagName('tbody')[0];
  tbody.innerHTML = '';
  studentTable.innerHTML = '';
  const data = await sendHttpRequest(`${HOST_URL}/sales/getnewid`);
  saleIdInput.value = data;
}
