import { HOST_URL, sendHttpRequest } from '../utils.js';
const saleTypeSelect = document.getElementById('saletype');
const studentSelect = document.getElementById('studentorgroup');
const studentTable = document.getElementById('studentList');

studentTable.innerHTML = '';

saleTypeSelect.addEventListener('change', async function (e) {
  studentSelect.innerHTML = '';
  studentTable.innerHTML = '';
  const res = await fetch(
    `${HOST_URL}/sales/getstudentorgroup?type=${e.target.value}`
  );
  const data = await res.json();
  studentSelect.innerHTML = data;
});

//load groups
studentSelect.addEventListener('change', async function (e) {
  if (saleTypeSelect.value === 'student') return;
  const url = `${HOST_URL}/sales/getgroupmembers?groupid=${+e.target.value}`;
  const data = await sendHttpRequest(url);
  studentTable.innerHTML = '';
  data?.forEach(dt => {
    let html = `
    <div class="table-like">
      <div class="form-check">
          <input type="checkbox" name="active" class="form-check-input" id="active">
      </div>
      <div class="studentname">${dt.studentName}</div>                        
      <div class="contact">${dt.contact}</div>
    </div>
    `;
    studentTable.insertAdjacentHTML('beforeend', html);
  });
});
