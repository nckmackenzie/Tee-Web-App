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
