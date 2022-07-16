export function btnClick(btnclass, inputid) {
  const input = document.querySelector('#' + inputid + '');
  const btns = document.querySelectorAll(btnclass);
  Array.prototype.forEach.call(btns, function addClickListener(btn) {
    btn.addEventListener('click', function () {
      const id = this.dataset.id;
      input.value = id;
    });
  });
}

export function getSelectedText(sel) {
  return sel.options[sel.selectedIndex].text;
}

export const HOST_URL = 'http://localhost/pcea_tee';

export function displayAlert(elm, message) {
  const html = `
    <div class="alert alert-danger" role="alert">
      ${message}
    </div>
  `;
  elm.insertAdjacentHTML('afterbegin', html);
  setTimeout(function () {
    elm.innerHTML = '';
  }, 5000);
}
