const invoiceDateInput = document.querySelector('#invoicedate');
const dueDateInput = document.querySelector('#duedate');

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
