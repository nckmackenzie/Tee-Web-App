import { HOST_URL } from '../utils.js';

const invoiceDateInput = document.querySelector('#invoicedate');
const dueDateInput = document.querySelector('#duedate');
const vatTypeSelect = document.querySelector('#vattype');
const vatSelect = document.querySelector('#vat');
const productSelect = document.querySelector('#book');
const rateInput = document.querySelector('#rate');

invoiceDateInput.addEventListener('change', function (e) {
  const currDate = new Date(e.target.value);
  const newDate = new Date(currDate.setMonth(currDate.getMonth() + 1));
  const formatedDate =
    newDate.getFullYear() +
    '-' +
    ('0' + (newDate.getMonth() + 1)).slice(-2) +
    '-' +
    ('0' + newDate.getDate()).slice(-2);
  dueDateInput.value = formatedDate;
});

vatTypeSelect.addEventListener('change', function (e) {
  const vatType = +e.target.value;
  if (!vatType) return;
  if (vatType === 1) {
    vatSelect.disabled = true;
  } else {
    vatSelect.disabled = false;
  }
  vatSelect.value = '';
});

productSelect.addEventListener('change', async function (e) {
  const product = +e.target.value;
  const date = invoiceDateInput.value;
  if (!product || !date) {
    alert('Ensure Invoice date and product are selected');
    e.target.value = '';
    return;
  }
  const res = await fetch(
    `${HOST_URL}/stocks/getprice?book=${product}&rdate=${date}`
  );
  const data = await res.json();
  rateInput.value = data;
});
