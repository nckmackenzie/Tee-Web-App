export const qtyInput = document.querySelector('#qty');
export const rateInput = document.querySelector('#rate');
export const grossInput = document.querySelector('#gross');

export function getGrossValue() {
  const qtyValue = qtyInput.value;
  const rateValue = rateInput.value;
  if (!qtyValue || !rateValue) return;
  const grossValue = qtyValue * rateValue;
  grossInput.value = grossValue;
}

qtyInput.addEventListener('change', getGrossValue);
rateInput.addEventListener('change', getGrossValue);
