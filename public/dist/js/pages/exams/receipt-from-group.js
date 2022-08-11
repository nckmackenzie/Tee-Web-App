import { HOST_URL } from '../utils.js';
const table = document.getElementById('groupmembers');
const groupSelect = document.getElementById('group');
const form = document.querySelector('form');

groupSelect.addEventListener('change', async function (e) {
  const tbody = table.getElementsByTagName('tbody')[0];
  tbody.innerHTML = '';
  const type = 'fromgroup';
  const groupId = +e.target.value;
  const res = await fetch(
    `${HOST_URL}/exams/getstudents?gid=${groupId}&type=${type}`
  );
  const data = await res.json();
  tbody.innerHTML = data;
});

table.addEventListener('click', function (e) {
  if (!e.target.classList.contains('btndel')) return;
  const btn = e.target;
  btn.closest('tr').remove();
});

form.addEventListener('submit', function (e) {
  e.preventDefault();
  const tbody = table.getElementsByTagName('tbody')[0];
  if (Number(tbody.rows.length) === 0) {
    alert('No group members were selected');
    return false;
  } else {
    document.groupform.submit();
  }
});
