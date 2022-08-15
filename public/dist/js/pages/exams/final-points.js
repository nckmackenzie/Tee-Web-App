import { HOST_URL } from '../utils.js';

const btn = document.querySelector('[data-id="btn"]');
const bookSelect = document.querySelector('#book');
const courseSelect = document.querySelector('#course');
const groupSelect = document.querySelector('#group');

courseSelect.addEventListener('change', async function (e) {
  const course = +e.target.value;
  if (!course) return;
  bookSelect.innerHTML = '';
  const res = await fetch(`${HOST_URL}/exams/getbooks?course=${course}`);
  const data = await res.json();
  bookSelect.innerHTML = data;
});

btn.addEventListener('click', function () {
  const selects = document.querySelectorAll('select');
  let validValue = 0;
  selects.forEach(select => {
    select.classList.remove('is-invalid');
    if (!select.value || String(select.value).trim() === '') {
      select.classList.add('is-invalid');
    } else {
      select.classList.add('is-valid');
      validValue++;
    }
    if (validValue !== 3) return;
  });
});
