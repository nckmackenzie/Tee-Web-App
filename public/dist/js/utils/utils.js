import { HOST_URL } from '../pages/utils.js';
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

export function clearValues() {
  const inputs = document.querySelectorAll('input');
  const selects = document.querySelectorAll('select');
  if (inputs.length > 0) {
    inputs.forEach(input => (input.value = ''));
  }
  if (selects.length > 0) {
    selects.forEach(select => (select.value = ''));
  }
}

export function redirect(page) {
  window.location.href = `${HOST_URL}/${page}`;
}

export function clearErrorState(fields) {
  fields.forEach(field => {
    field.classList.remove('is-invalid');
    field.nextSibling.nextSibling.textContent = '';
  });
}

export function getUrlParams() {
  const urlSearchParams = new URLSearchParams(window.location.search);
  const params = Object.fromEntries(urlSearchParams.entries());
  return params;
}
