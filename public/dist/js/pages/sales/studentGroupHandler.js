import { HOST_URL } from '../utils.js';
const saleTypeSelect = document.getElementById('saletype');
const studentSelect = document.getElementById('studentorgroup');

saleTypeSelect.addEventListener('change', async function (e) {
  studentSelect.innerHTML = '';
  const res = await fetch(
    `${HOST_URL}/sales/getstudentorgroup?type=${e.target.value}`
  );
  const data = await res.json();
  studentSelect.innerHTML = data;
});
