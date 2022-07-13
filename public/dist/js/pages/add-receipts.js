const mtnSelect = document.getElementById('mtn');
const bookSelect = document.getElementById('bookid');
const receiptDate = document.getElementById('date');
const valueInput = document.getElementById('value');
const qtyInput = document.getElementById('qty');

// radio event handler
document.querySelectorAll("input[name='receipttype']").forEach(input => {
  input.addEventListener('change', e => {
    // console.log(e.target.value);
    if (e.target.value === 'grn') mtnSelect.disabled = true;
    if (e.target.value === 'internal') mtnSelect.disabled = false;
    mtnSelect.value = '';
  });
});

async function getBooksValue() {
  if (!receiptDate || !qtyInput || !bookSelect) return;

  const url = 'http://localhost/pcea_tee';
  const bookValue = bookSelect.value;
  const date = receiptDate.value;
  const res = await fetch(
    `${url}/stocks/getprice?book=${bookValue}&rdate=${date}`
  );
  const data = await res.json();
  let qty = qtyInput.value || 1;
  valueInput.value = +qty * +data;
}

bookSelect.addEventListener('change', getBooksValue);

qtyInput.addEventListener('change', function (e) {
  getBooksValue();
});
