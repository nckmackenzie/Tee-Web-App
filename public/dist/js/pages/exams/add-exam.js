import { HOST_URL } from '../utils.js';
const courseSelect = document.getElementById('course');
const bookSelect = document.getElementById('book');

courseSelect.addEventListener('change', async function (e) {
  bookSelect.innerHTML = '';
  const course = +e.target.value;
  const res = await fetch(`${HOST_URL}/exams/getbooks?course=${course}`);
  const data = await res.json();
  bookSelect.innerHTML = data;
});
