import { HOST_URL } from '../utils.js';
const courseSelect = document.getElementById('course');
const bookSelect = document.getElementById('book');
const table = document.getElementById('groupmembers');
const groupSelect = document.getElementById('group');
const form = document.querySelector('form');

courseSelect.addEventListener('change', async function (e) {
  const course = +e.target.value;
  if (!course) return;
  bookSelect.innerHTML = '';
  const res = await fetch(`${HOST_URL}/exams/getbooks?course=${course}`);
  const data = await res.json();
  bookSelect.innerHTML = data;
});

groupSelect.addEventListener('change', async function (e) {
  const tbody = table.getElementsByTagName('tbody')[0];
  tbody.innerHTML = '';
  const group = +e.target.value;
  if (!group) return;
  const type = 'fromgroup';
  const res = await fetch(
    `${HOST_URL}/exams/getstudentspoints?gid=${group}&type=${type}`
  );
  const data = await res.json();
  tbody.innerHTML = data;
});

form.addEventListener('submit', function (e) {
  e.preventDefault();
  const tbody = table.getElementsByTagName('tbody')[0];
  if (Number(tbody.rows.length) === 0) {
    alert('No group members found');
    return false;
  } else {
    document.form.submit();
  }
});
