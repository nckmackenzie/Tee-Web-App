export const loadingButton = (btn, text) => {
  const html = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${text}...`;
  btn.disabled = true;
  btn.innerHTML = html;
};

export function numberFormatter(number) {
  if (number.includes(',')) {
    return number.replaceAll(',', '');
  }
  return number;
}

export function getSelectedText(sel) {
  return sel.options[sel.selectedIndex].text;
}

export function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

export function dateNotGreaterToday(dateElm) {
  if (new Date(dateElm.value).getTime() > new Date().getTime()) {
    dateElm.classList.add('is-invalid');
    dateElm.nextSibling.nextSibling.textContent =
      'Invalid payment date selected';
    return false;
  }
  return true;
}
