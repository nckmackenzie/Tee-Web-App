export const loadingButton = (btn, text) => {
  const html = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ${text}...`;
  btn.disabled = true;
  btn.innerHTML = html;
};
