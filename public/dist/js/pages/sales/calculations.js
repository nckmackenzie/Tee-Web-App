export const qtyInput = document.getElementById('qty');
export const valueInput = document.getElementById('value');
export const rateInput = document.getElementById('rate');
export const subtotalInput = document.getElementById('subtotal');
export const discountInput = document.getElementById('discount');
export const deliveryInput = document.getElementById('deliveryfee');
export const netInput = document.getElementById('net');
export const paidInput = document.getElementById('paid');
export const balanceInput = document.getElementById('balance');

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

export function summaryCalculations() {
  const subTotal = +subtotalInput.value || 0;
  const discount = +discountInput.value || 0;
  const deliveryFee = +deliveryInput.value || 0;
  const netAmount = subTotal - subTotal * (discount / 100) + deliveryFee;
  const amountPaid = +paidInput.value || 0;
  const balance = netAmount - amountPaid;
  netInput.value = netAmount || 0;
  balanceInput.value = balance || 0;
}
