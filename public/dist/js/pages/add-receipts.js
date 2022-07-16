import { getSelectedText, HOST_URL, displayAlert } from './utils.js';

const mtnSelect = document.getElementById('mtn');
const bookSelect = document.getElementById('bookid');
const receiptDate = document.getElementById('date');
const valueInput = document.getElementById('value');
const qtyInput = document.getElementById('qty');
const btnadd = document.querySelector('.btnadd');
const table = document.getElementById('receipts-table');
const form = document.querySelector('form');
const alertBox = document.querySelector('.alert-box');
const tableDiv = document.querySelector('.table-responsive');

// radio event handler
document.querySelectorAll("input[name='receipttype']").forEach(input => {
  input.addEventListener('change', e => {
    mtnSelect.classList.remove('mandatory');
    if (e.target.value === 'grn') {
      mtnSelect.disabled = true;
      btnadd.disabled = false;
    }
    if (e.target.value === 'internal') {
      mtnSelect.disabled = false;
      mtnSelect.classList.add('mandatory');
      btnadd.disabled = true;
    }
    mtnSelect.value = '';
    tableReset();
  });
});

async function getBooksValue() {
  if (!receiptDate || !qtyInput || !bookSelect) return;

  const bookValue = bookSelect.value;
  const date = receiptDate.value;
  const res = await fetch(
    `${HOST_URL}/stocks/getprice?book=${bookValue}&rdate=${date}`
  );
  const data = await res.json();
  let qty = qtyInput.value || 1;
  valueInput.value = +qty * +data;
}

function tableReset() {
  tableDiv.innerHTML = '';
  const html = `
  <table class="table-sm table" id="receipts-table">
    <thead class="table-light">
        <tr>
            <th class="d-none">Pid</th>
            <th>Product</th>
            <th>Qty</th>
            <th width="10%">Remove</th>
        </tr>
    </thead>
    <tbody></tbody>
  </table>
  `;
  tableDiv.insertAdjacentHTML('afterbegin', html);
}

bookSelect.addEventListener('change', getBooksValue);

qtyInput.addEventListener('change', function (e) {
  getBooksValue();
});

btnadd.addEventListener('click', function () {
  if (!bookSelect.value || !qtyInput.value) {
    alert('Enter all required fields');
    return;
  }

  const body = table.getElementsByTagName('tbody')[0];
  let selectedBook = getSelectedText(bookSelect);
  let selectedValue = bookSelect.value;
  let qty = qtyInput.value;
  var rows = table.rows;
  for (var i = 1; i < rows.length; i++) {
    var cols = rows[i].cells;
    if (Number(cols[0].children[0].value) === +selectedValue) {
      cols[2].children[0].value =
        parseFloat(cols[2].children[0].value) + parseFloat(qty);
      bookSelect.value = '';
      qtyInput.value = '';
      value.value = '';
      return;
    }
  }
  let html = `
      <tr>
        <td class="d-none"><input type="text" name="booksid[]" value="${selectedValue}" readonly></td>
        <td><input type="text" class="table-input" name="booksname[]" value="${selectedBook}" readonly></td>
        <td><input type="text" class="table-input" name="qtys[]" value="${qty}" readonly></td>
        <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
      </tr>
  `;
  let newRow = body.insertRow(body.rows.length);
  newRow.innerHTML = html;
  bookSelect.value = '';
  qtyInput.value = '';
  value.value = '';
});

table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('btndel')) return;
  const btn = e.target;
  btn.closest('tr').remove();
});

form.addEventListener('submit', e => {
  e.preventDefault();
  const body = document
    .getElementById('receipts-table')
    .getElementsByTagName('tbody')[0];
  // const body = table.getElementsByTagName('tbody')[0];
  if (Number(body.rows.length) === 0) {
    displayAlert(alertBox, 'Add Received Items');
    return false;
  } else {
    document.receiptform.submit();
  }
});

mtnSelect.addEventListener('change', async e => {
  const selectedValue = Number(e.target.value);
  const res = await fetch(
    `${HOST_URL}/stocks/gettransfereditems?tid=${selectedValue}`
  );
  const data = await res.json();
  tableDiv.innerHTML = '';
  tableDiv.insertAdjacentHTML('afterbegin', data);
});
