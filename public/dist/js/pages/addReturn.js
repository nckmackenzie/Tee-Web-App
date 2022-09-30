import { displayAlert, getSelectedText } from './utils.js';
const form = document.getElementById('returnForm');
const addBtn = document.querySelector('.add');
const controls = document.querySelectorAll('.control');
const bookSelect = document.getElementById('book');
const qtyInput = document.getElementById('qty');
const table = document.getElementById('returns-table');
const alertBox = document.querySelector('.alert-box');

//add btn event listener
addBtn.addEventListener('click', function () {
  resetErrorState();
  if (!validate()) return;

  //define values
  const body = table.getElementsByTagName('tbody')[0];
  const bookId = +bookSelect.value;
  const bookText = getSelectedText(bookSelect);
  const qty = qtyInput.value;
  const rows = table.rows;

  //loop to check if books exists
  for (var i = 1; i < rows.length; i++) {
    var cols = rows[i].cells;
    if (Number(cols[0].children[0].value) === +bookId) {
      cols[2].children[0].value =
        parseFloat(cols[2].children[0].value) + parseFloat(qty);
      bookSelect.value = qtyInput.value = '';
      return;
    }
  }
  let html = `
      <tr>
        <td class="d-none"><input type="text" name="booksid[]" value="${bookId}" readonly></td>
        <td><input type="text" class="table-input w-100" name="booksname[]" value="${bookText}" readonly></td>
        <td><input type="text" class="table-input" name="qtys[]" value="${qty}" readonly></td>
        <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
      </tr>
  `;
  let newRow = body.insertRow(body.rows.length);
  newRow.innerHTML = html;
  bookSelect.value = qtyInput.value = '';
});

controls.forEach(contrl => {
  contrl.addEventListener('change', function () {
    contrl.classList.remove('is-invalid');
    contrl.nextSibling.nextSibling.textContent = '';
  });
});

//clear error state
function resetErrorState() {
  controls.forEach(contrl => {
    contrl.classList.remove('is-invalid');
    contrl.nextSibling.nextSibling.textContent = '';
  });
}

//validator function
function validate() {
  let errorCount = 0;
  controls.forEach(contrl => {
    if (contrl.value === '') {
      contrl.classList.add('is-invalid');
      contrl.nextSibling.nextSibling.textContent = 'Field is required';
      errorCount++;
    }
  });
  //errors found
  if (errorCount > 0) return false;
  return true;
}

//remove click handler
table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('btndel')) return;
  const btn = e.target;
  btn.closest('tr').remove();
});

//form submit
form.addEventListener('submit', e => {
  e.preventDefault();
  const body = document
    .getElementById('returns-table')
    .getElementsByTagName('tbody')[0];
  // const body = table.getElementsByTagName('tbody')[0];
  if (Number(body.rows.length) === 0) {
    displayAlert(alertBox, 'Add Returned Items');
    return false;
  } else {
    document.returnForm.submit();
  }
});
