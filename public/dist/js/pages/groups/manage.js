import { getSelectedText, displayAlert } from '../utils.js';

const studentSelect = document.getElementById('student');
const addBtn = document.querySelector('.btnadd');
const studentSpan = document.querySelector('.invalid-feedback');
const table = document.getElementById('members');
const form = document.querySelector('form');
const alertBox = document.querySelector('.alert-box');

addBtn.addEventListener('click', () => {
  studentSelect.classList.remove('is-invalid');
  if (!studentSelect.value) {
    studentSelect.classList.add('is-invalid');
    studentSpan.textContent = 'Please select a student';
    return;
  }

  const selectedStudentId = +studentSelect.value;
  const selectedStudentName = getSelectedText(studentSelect);
  const body = table.getElementsByTagName('tbody')[0];
  const rows = table.rows;

  for (var i = 1; i < rows.length; i++) {
    var cols = rows[i].cells;
    if (Number(cols[0].children[0].value) === +selectedStudentId) {
      studentSelect.classList.add('is-invalid');
      studentSpan.textContent = 'Student already selected';
      studentSelect.value = '';
      return;
    }
  }

  let html = `
    <td class="d-none"><input type="text" name="studentsid[]" value="${selectedStudentId}"></td>
    <td><input type="text" class="table-input" name="studentsname[]" value="${selectedStudentName}" readonly></td>
    <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
  `;

  let newRow = body.insertRow(body.rows.length);
  newRow.innerHTML = html;
  studentSelect.value = '';
});

form.addEventListener('submit', function (e) {
  e.preventDefault();
  const body = document
    .getElementById('members')
    .getElementsByTagName('tbody')[0];
  // const body = table.getElementsByTagName('tbody')[0];
  if (Number(body.rows.length) === 0) {
    displayAlert(alertBox, 'Add Students');
    return false;
  } else {
    document.studentsform.submit();
  }
});

table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('btndel')) return;

  const btn = e.target;
  btn.closest('tr').remove();
});
