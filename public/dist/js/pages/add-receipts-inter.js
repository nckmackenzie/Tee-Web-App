import { HOST_URL } from './utils.js';

const mtnSelect = document.getElementById('mtn');
const table = document.getElementById('receipts-table');

mtnSelect.addEventListener('change', async function (e) {
  const body = table.getElementsByTagName('tbody')[0];
  body.innerHTML = '';
  const res = await fetch(
    `${HOST_URL}/stocks/gettransfereditems?tid=${+e.target.value}`
  );

  const data = await res.json();
  body.innerHTML = data;
});
