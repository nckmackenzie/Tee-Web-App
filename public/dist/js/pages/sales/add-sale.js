import { HOST_URL, displayAlert, getSelectedText } from '../utils.js';
const table = document.getElementById('addsale');
const addBtn = document.querySelector('.btnadd');
const bookSelect = document.getElementById('book');
const rateInput = document.getElementById('rate');
const stockInput = document.getElementById('stock');
const selectedDate = document.getElementById('sdate');
const qtyInput = document.getElementById('qty');
const valueInput = document.getElementById('value');
const alertBox = document.getElementById('message');
const subtotalInput = document.getElementById('subtotal');

//calculate total value
function calculateTotalValue() {
  const qty = qtyInput.value;
  const rate = rateInput.value;
  if (!qty || !rate) return;
  valueInput.value = +qty * +rate;
}

function resetAndGetTotal() {
  bookSelect.value = '';
  qtyInput.value = '';
  value.value = '';
  rateInput.value = '';
  stockInput.value = '';
  updateSubTotal();
}

function updateSubTotal() {
  let sumVal = 0;
  for (var i = 1; i < table.rows.length; i++) {
    sumVal = sumVal + parseFloat(table.rows[i].cells[4].children[0].value);
  }

  subtotalInput.value = sumVal;
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
      return;
    }
  }
  const body = document
    .getElementById('addsale')
    .getElementsByTagName('tbody')[0];
  let html = `
      <tr>
        <td class="d-none"><input type="text" name="booksid[]" value="${
          bookSelect.value
        }" readonly></td>
        <td><input type="text" class="table-input w-100" name="booksname[]" value="${getSelectedText(
          bookSelect
        )}" readonly></td>
        <td><input type="text" class="table-input" name="rates[]" value="${
          rateInput.value
        }" readonly></td>
        <td><input type="text" class="table-input" name="qtys[]" value="${
          qtyInput.value
        }" readonly></td>
        <td><input type="text" class="table-input" name="values[]" value="${
          valueInput.value
        }" readonly></td>
        <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
      </tr>
  `;
  let newRow = body.insertRow(body.rows.length);
  newRow.innerHTML = html;
  resetAndGetTotal();
});

//remove row
table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('btndel')) return;
  const btn = e.target;
  btn.closest('tr').remove();
  updateSubTotal();
});
