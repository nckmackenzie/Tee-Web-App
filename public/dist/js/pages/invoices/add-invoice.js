import { HOST_URL, getSelectedText } from '../utils.js';
import { getGrossValue, qtyInput, grossInput } from './calculations.js';

const invoiceDateInput = document.querySelector('#invoicedate');
const dueDateInput = document.querySelector('#duedate');
const vatTypeSelect = document.querySelector('#vattype');
const vatSelect = document.querySelector('#vat');
const productSelect = document.querySelector('#book');
const rateInput = document.querySelector('#rate');
const table = document.querySelector('#detailstable');
const addBtn = document.querySelector('.btnadd');
const qtySpan = document.querySelector('.qtyspan');
const bookSpan = document.querySelector('.bookspan');

invoiceDateInput.addEventListener('change', function (e) {
  const currDate = new Date(e.target.value);
  const newDate = new Date(currDate.setMonth(currDate.getMonth() + 1));
  const formatedDate =
    newDate.getFullYear() +
    '-' +
    ('0' + (newDate.getMonth() + 1)).slice(-2) +
    '-' +
    ('0' + newDate.getDate()).slice(-2);
  dueDateInput.value = formatedDate;
});

vatTypeSelect.addEventListener('change', function (e) {
  const vatType = +e.target.value;
  if (!vatType) return;
  if (vatType === 1) {
    vatSelect.disabled = true;
  } else {
    vatSelect.disabled = false;
  }
  vatSelect.value = '';
});

productSelect.addEventListener('change', async function (e) {
  const product = +e.target.value;
  const date = invoiceDateInput.value;
  if (!product || !date) {
    alert('Ensure Invoice date and product are selected');
    e.target.value = '';
    return;
  }
  const res = await fetch(
    `${HOST_URL}/stocks/getprice?book=${product}&rdate=${date}`
  );
  const data = await res.json();
  rateInput.value = data;
  getGrossValue();
});

function resetBeforeAndAfterAdd() {
  productSelect.classList.remove('is-invalid');
  qtyInput.classList.remove('is-invalid');
  productSelect.value =
    qtyInput.value =
    rateInput.value =
    grossInput.value =
      '';
  qtySpan.textContent = '';
  bookSpan.textContent = '';
}

addBtn.addEventListener('click', () => {
  const product = productSelect.value;
  const qty = qtyInput.value;
  if (!product) {
    productSelect.classList.add('is-invalid');
    bookSpan.textContent = 'Please select a product';
  }
  if (!qty) {
    qtyInput.classList.add('is-invalid');
    qtySpan.textContent = 'Please enter qty';
  }
  if (!qty || !product) return;
  const selectedBook = getSelectedText(productSelect);
  const gross = grossInput.value;
  const rate = rateInput.value;
  const body = table.getElementsByTagName('tbody')[0];
  const rows = table.rows;
  for (var i = 1; i < rows.length; i++) {
    var cols = rows[i].cells;
    if (Number(cols[0].children[0].value) === +product) {
      cols[2].children[0].value =
        parseFloat(cols[2].children[0].value) + parseFloat(qty);
      cols[4].children[0].value =
        parseFloat(cols[4].children[0].value) + parseFloat(gross);
      resetBeforeAndAfterAdd();
      return;
    }
  }
  let html = `
      <tr>
        <td class="d-none"><input type="text" name="booksid[]" value="${product}" readonly></td>
        <td><input type="text" class="table-input w-100" name="booksname[]" value="${selectedBook}" readonly></td>
        <td><input type="text" class="table-input" name="qtys[]" value="${qty}" readonly></td>
        <td><input type="text" class="table-input" name="rates[]" value="${rate}" readonly></td>
        <td><input type="text" class="table-input" name="gross[]" value="${gross}" readonly></td>
        <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
      </tr>
  `;
  let newRow = body.insertRow(body.rows.length);
  newRow.innerHTML = html;
  resetBeforeAndAfterAdd();
});

table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('btndel')) return;
  const btn = e.target;
  btn.closest('tr').remove();
});
