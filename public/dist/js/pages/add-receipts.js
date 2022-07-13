import { getSelectedText, HOST_URL } from './utils.js';

const mtnSelect = document.getElementById('mtn');
const bookSelect = document.getElementById('bookid');
const receiptDate = document.getElementById('date');
const valueInput = document.getElementById('value');
const qtyInput = document.getElementById('qty');
const btnadd = document.querySelector('.btnadd');
const table = document.getElementById('receipts-table');

// radio event handler
document.querySelectorAll("input[name='receipttype']").forEach(input => {
  input.addEventListener('change', e => {
    // console.log(e.target.value);
    if (e.target.value === 'grn') mtnSelect.disabled = true;
    if (e.target.value === 'internal') mtnSelect.disabled = false;
    mtnSelect.value = '';
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

bookSelect.addEventListener('change', getBooksValue);

qtyInput.addEventListener('change', function (e) {
  getBooksValue();
});

btnadd.addEventListener('click', function () {
  if (!bookSelect.value || !qtyInput.value) {
    alert('Enter all required fields');
    return;
  }
  const rows = table.rows.length;
  const body = table.getElementsByTagName('tbody')[0];
  let selectedBook = getSelectedText(bookSelect);
  let selectedValue = bookSelect.value;
  let qty = qtyInput.value;
  let html = `
      <tr>
        <td class="d-none">${selectedValue}</td>
        <td>${selectedBook}</td>
        <td>${qty}</td>
        <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button>
        </td>
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
