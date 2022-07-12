const mtnSelect = document.getElementById('mtn');

// radio event handler
document.querySelectorAll("input[name='receipttype']").forEach(input => {
  input.addEventListener('change', e => {
    // console.log(e.target.value);
    if (e.target.value === 'grn') mtnSelect.disabled = true;
    if (e.target.value === 'internal') mtnSelect.disabled = false;
    mtnSelect.value = '';
  });
});
