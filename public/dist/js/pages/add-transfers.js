import { getSelectedText, HOST_URL } from './utils.js';

const bookSelect = document.getElementById('bookid');
const receiptDate = document.getElementById('date');
const valueInput = document.getElementById('value');
const qtyInput = document.getElementById('qty');
const btnadd = document.querySelector('.btnadd');
const table = document.getElementById('receipts-table');
const form = document.querySelector('form');

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
  let html = `
      <tr>
        <td class="d-none"><input type="text" name="booksid[]" value="${selectedValue}"></td>
        <td><input type="text" class="table-input" name="booksname[]" value="${selectedBook}"></td>
        <td><input type="text" class="table-input" name="qtys[]" value="${qty}"></td>
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
  const body = table.getElementsByTagName('tbody')[0];
  if (Number(body.rows.length) === 0) {
    alert('No items added');
    return false;
  } else {
    document.transferform.submit();
  }
});
