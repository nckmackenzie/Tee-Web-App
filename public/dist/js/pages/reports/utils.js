export function validatedate(startEl, endEl, startSpan, endSpan) {
  startEl.classList.remove('is-invalid');
  endEl.classList.remove('is-invalid');
  startSpan.textContent = endSpan.textContent = '';
  if (startEl.value === '') {
    startEl.classList.add('is-invalid');
    startSpan.textContent = 'Select start date';
  }
  if (endEl.value === '') {
    endEl.classList.add('is-invalid');
    endSpan.textContent = 'Select end date';
  }
  if (new Date(startEl.value).getTime() > new Date(endEl.value).getTime()) {
    startEl.classList.add('is-invalid');
    startSpan.textContent = 'start date cannot be greater than end date';
  }
  if (startSpan.textContent !== '' || endSpan.textContent !== '') return false;
  return true;
}
