export const mandatoryFields = document.querySelectorAll('.mandatory');
export const alerBox = document.getElementById('alerBox');

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

export function displayAlert(elm, message, status = 'danger') {
  const html = `
    <div class="alert alert-${status}" role="alert">
      ${message}
    </div>
  `;
  elm.insertAdjacentHTML('afterbegin', html);
  setTimeout(function () {
    elm.innerHTML = '';
  }, 5000);
}

export function snackBar(elm, message, status = 'danger') {
  elm.className = 'show';
  const html = `
    <div class="alert alert-${status}" role="alert">
      ${message}
    </div>
  `;
  elm.insertAdjacentHTML('afterbegin', html);
  setTimeout(function () {
    elm.innerHTML = '';
    elm.className = x.className.replace('show', '');
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

//function to make http requests
export async function sendHttpRequest(
  url,
  method = 'GET',
  body = null,
  headers = {},
  alertBox = undefined
) {
  try {
    const res = await fetch(url, {
      method,
      body,
      headers,
    });

    const data = await res.json();
    if (!res.ok) throw new Error(data.message);
    return data;
  } catch (error) {
    if (alertBox) {
      displayAlert(alertBox, error.message);
    } else {
      console.error(error.message);
    }
  }
}

export function validation() {
  let errorCount = 0;

  const mandatoryField = document.querySelectorAll('.mandatory');
  mandatoryField?.forEach(field => {
    if (!field.value || field.value == '') {
      field.classList.add('is-invalid');
      field.nextSibling.nextSibling.textContent = 'Field is required';
      errorCount++;
    }
  });

  return errorCount;
}

export function clearOnChange(mandatoryField) {
  mandatoryField?.forEach(field => {
    field.addEventListener('change', function () {
      field.classList.remove('is-invalid');
      field.nextSibling.nextSibling.textContent = '';
    });
  });
}

export function setLoadingState(btn, text = 'loading') {
  btn.innerHTML = '';
  let html = `
    <div class="spinner-container">
    <div class="spinner"></div> 
    <span>${text}...</span> 
  </div>
    `;
  btn.innerHTML = html;
  btn.disabled = true;
}

export function resetLoadingState(btn, text) {
  btn.disabled = false;
  btn.textContent = text;
}

export function validateDate(start, end) {
  if (new Date(start.value).getTime() > new Date(end.value).getTime()) {
    start.classList.add('is-invalid');
    start.nextSibling.nextSibling.textContent =
      'Date cannot be greather than end date';
    return false;
  }
  return true;
}

export async function getRequest(url) {
  return await sendHttpRequest(url, 'GET', undefined, {}, alerBox);
}
