import { HOST_URL, sendHttpRequest } from '../utils.js';
const saleTypeSelect = document.getElementById('saletype');
const studentSelect = document.getElementById('studentorgroup');
const studentTable = document.getElementById('studentList');
const selectAllBtn = document.getElementById('selectAll');

studentTable.innerHTML = '';

saleTypeSelect.addEventListener('change', async function (e) {
  studentSelect.innerHTML = '';
  studentTable.innerHTML = '';
  selectAllBtn.disabled = true;
  const res = await fetch(
    `${HOST_URL}/sales/getstudentorgroup?type=${e.target.value}`
  );
  const data = await res.json();
  studentSelect.innerHTML = data;
});

//load groups
studentSelect.addEventListener('change', async function (e) {
  if (saleTypeSelect.value === 'student') {
    selectAllBtn.disabled = true;
    selectAllBtn.textContent = 'Select All';
    return;
  }
  const url = `${HOST_URL}/sales/getgroupmembers?groupid=${+e.target.value}`;
  const data = await sendHttpRequest(url);
  studentTable.innerHTML = '';
  if (!data || data.length === 0) return;
  selectAllBtn.disabled = false;
  data?.forEach(dt => {
    let html = `
    <div class="table-like">
      <div class="form-check">
          <input type="checkbox" name="active[]" class="form-check-input stdcheck">
      </div>
      <div class="d-none"><input type="text" name="studentsid[]" value="${dt.id}" /></div>
      <div class="studentname">${dt.studentName}</div>                        
      <div class="contact">${dt.contact}</div>
    </div>
    `;
    studentTable.insertAdjacentHTML('beforeend', html);
  });
});

selectAllBtn.addEventListener('click', function (e) {
  const btnText = String(e.target.textContent).trim();
  const checkBxs = document.querySelectorAll('.stdcheck');
  checkBxs.forEach(chkbx => {
    chkbx.checked = btnText === 'Select all' ? true : false;
  });
  this.textContent = btnText === 'Select all' ? 'Deselect all' : 'Select all';
  // console.log(this);
});
