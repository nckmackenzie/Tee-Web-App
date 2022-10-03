import { HOST_URL, displayAlert } from '../utils.js';
const fromUserSelect = document.getElementById('fromuser');
const userToSelect = document.getElementById('touser');
const form = document.getElementById('cloneForm');
const alertBox = document.getElementById('alertBox');
const selects = document.querySelectorAll('.form-select');
const saveBtn = document.querySelector('.savebtn');

//validate
function validate() {
  let errorCount = 0;
  //check if empty
  selects.forEach(select => {
    if (!select.value || select.value === '') {
      select.classList.add('is-invalid');
      select.nextSibling.nextSibling.textContent = 'Field is required';
      errorCount++;
    }
  });

  if (errorCount > 0) return false;
  //if users match
  if (+fromUserSelect.value === +userToSelect.value) {
    selects.forEach(select => {
      select.classList.add('is-invalid');
      select.nextSibling.nextSibling.textContent = 'Users cannot match';
    });
    return false;
  }
  return true;
}

//onchange
selects.forEach(select => {
  select.addEventListener('change', clearErrors);
});

//clear error state
function clearErrors() {
  selects.forEach(select => {
    select.classList.remove('is-invalid');
    select.nextSibling.nextSibling.textContent = '';
  });
}

function setLoadingState() {
  saveBtn.innerHTML = '';
  let html = `
    <div class="spinner-container">
    <div class="spinner"></div> 
    <span>Cloning...</span> 
  </div>
    `;
  saveBtn.innerHTML = html;
  saveBtn.disabled = true;
}

function clear() {
  fromUserSelect.value = userToSelect.value = '';
  resetLoadingState();
}

function resetLoadingState() {
  saveBtn.disabled = false;
  saveBtn.textContent = 'Clone';
}

//form submit handler
form.addEventListener('submit', async function (e) {
  e.preventDefault();
  if (!validate()) return;
  setLoadingState();
  await clone();
});

async function clone() {
  const userData = { from: +fromUserSelect.value, to: +userToSelect.value };
  try {
    const res = await fetch(`${HOST_URL}/userrights/createclone`, {
      method: 'POST',
      body: JSON.stringify(userData),
      headers: { 'Content-Type': 'application/json' },
    });

    const data = await res.json();
    if (!res.ok) throw new Error(data.message);
    clear();
    displayAlert(alertBox, data.message, 'success');
  } catch (error) {
    resetLoadingState();
    displayAlert(alertBox, error.message);
  }
}
