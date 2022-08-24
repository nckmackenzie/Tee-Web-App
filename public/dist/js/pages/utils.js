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

export function formatcurrencyvalue(val) {
  return val.replace(/,/g, '');
}

export function dateFormat(pdate) {
  const newDate = new Date(pdate);
  const formatedDate =
    ('0' + newDate.getDate()).slice(-2) +
    '-' +
    ('0' + (newDate.getMonth() + 1)).slice(-2) +
    '-' +
    newDate.getFullYear();
  return formatedDate;
}
