import { HOST_URL } from '../utils.js';

const centerSelect = document.getElementById('fromcenter');
const groupSelect = document.getElementById('group');
const examSelect = document.getElementById('exam');
const table = document.getElementById('groupmembers');
const form = document.querySelector('form');

async function getSelectOptions(type, value, status) {
  const res = await fetch(
    `${HOST_URL}/exams/getselectoptions?type=${type}&value=${value}&status=${status}`
  );
  const data = await res.json();
  return data;
}

centerSelect.addEventListener('change', async function (e) {
  groupSelect.innerHTML = '';
  const status = 1;
  const centerId = +e.target.value;
  const type = 'group';
  const data = await getSelectOptions(type, centerId, status);
  groupSelect.innerHTML = data;
});

groupSelect.addEventListener('change', async function (e) {
  examSelect.innerHTML = '';
  const groupId = +e.target.value;
  const status = 1;
  const type = 'exam';
  const data = await getSelectOptions(type, groupId, status);
  examSelect.innerHTML = data;
});

examSelect.addEventListener('change', async function (e) {
  const examId = examSelect.value;
  const groupId = groupSelect.value;
  const centerId = centerSelect.value;
  const idInput = document.getElementById('id');
  const remarksInput = document.getElementById('centerremarks');
  const status = 1;

  if (!examId || !groupId || !centerId) return;

  const response = await fetch(
    `${HOST_URL}/exams/getheaderid?examId=${examId}&groupId=${groupId}&centerId=${centerId}&status=${status}`
  );
  const data = await response.json();
  if (!data || !data.id) return;
  idInput.value = +data.id;
  remarksInput.value = data.remarks;

  const tbody = table.getElementsByTagName('tbody')[0];
  tbody.innerHTML = '';
  const fetchtype = 'formarking';
  const res = await fetch(
    `${HOST_URL}/exams/getstudents?gid=${data.id}&type=${fetchtype}`
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
