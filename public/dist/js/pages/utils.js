export function rowFunction(el) {
  const input = document.querySelector('#id');
  var n = el.parentNode.parentNode.cells[0].textContent;
  input.value = +n;
}
