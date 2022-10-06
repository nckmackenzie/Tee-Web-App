import { HOST_URL, sendHttpRequest } from '../utils.js';
import {
  saleTypeSelect,
  studentSelect,
  studentTable,
  selectAllBtn,
} from './elements.js';

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
      <div class="d-none"><input type="number" name="studentsid[]" value="${dt.id}" /></div>
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

export function validateSelectedStudents() {
  let checkedBoxes = 0;
  const checkBxs = document.querySelectorAll('.stdcheck');
  if (!checkBxs || checkBxs.length === 0) {
    return 0;
  }
  checkBxs.forEach(chkbox => {
    if (chkbox.checked) checkedBoxes++;
  });
  return checkedBoxes;
}
