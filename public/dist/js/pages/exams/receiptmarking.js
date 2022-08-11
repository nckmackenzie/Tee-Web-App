import { HOST_URL } from '../utils.js';

const centerSelect = document.getElementById('fromcenter');
const groupSelect = document.getElementById('group');
const examSelect = document.getElementById('exam');
const table = document.getElementById('groupmembers');
const form = document.querySelector('form');

async function getSelectOptions(type, value) {
  const res = await fetch(
    `${HOST_URL}/exams/getselectoptions?type=${type}&value=${value}`
  );
  const data = await res.json();
  return data;
}

centerSelect.addEventListener('change', async function (e) {
  groupSelect.innerHTML = '';
  const centerId = +e.target.value;
  const type = 'group';
  const data = await getSelectOptions(type, centerId);
  groupSelect.innerHTML = data;
});

groupSelect.addEventListener('change', async function (e) {
  examSelect.innerHTML = '';
  const groupId = +e.target.value;
  const type = 'exam';
  const data = await getSelectOptions(type, groupId);
  examSelect.innerHTML = data;
});

examSelect.addEventListener('change', async function (e) {
  const examId = examSelect.value;
  const groupId = groupSelect.value;
  const centerId = centerSelect.value;
  const idInput = document.getElementById('id');

  if (!examId || !groupId || !centerId) return;

  const response = await fetch(
    `${HOST_URL}/exams/getheaderid?examId=${examId}&groupId=${groupId}&centerId=${centerId}`
  );
  const data = await response.json();
  if (!data) return;
  idInput.value = data;

  const tbody = table.getElementsByTagName('tbody')[0];
  tbody.innerHTML = '';
  const fetchtype = 'formarking';
  const res = await fetch(
    `${HOST_URL}/exams/getstudents?gid=${data}&type=${fetchtype}`
  );
  const studentdata = await res.json();
  tbody.innerHTML = studentdata;
});

form.addEventListener('submit', function (e) {
  e.preventDefault();
  const tbody = document
    .getElementById('groupmembers')
    .getElementsByTagName('tbody')[0];
  if (+tbody.rows.length === 0) {
    alert('No student marks entered');
    return;
  }
  document.form.submit();
});
