export const qtyInput = document.getElementById('qty');
export const valueInput = document.getElementById('value');
export const rateInput = document.getElementById('rate');
const subtotalInput = document.getElementById('subtotal');

//calculate total value
export function calculateTotalValue() {
  const qty = qtyInput.value;
  const rate = rateInput.value;
  if (!qty || !rate) return;
  valueInput.value = +qty * +rate;
}

//get total for value in table
export function updateSubTotal(table) {
  let sumVal = 0;
  for (var i = 1; i < table.rows.length; i++) {
    sumVal = sumVal + parseFloat(table.rows[i].cells[4].children[0].value);
  }

  subtotalInput.value = sumVal;
}
