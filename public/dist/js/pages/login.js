import { loadingButton } from '../utils/utils.js';

const btn = document.querySelector('.login-btn');
const form = document.querySelector('#login-form');
const formData = new FormData(form);

form.addEventListener('submit', async e => {
  e.preventDefault();
  loadingButton(btn, 'Logging in...');
  const response = await fetch('/pcea_tee/auth/login_act', {
    method: 'POST',
    body: formData,
  });
  const data = await response.json();
  console.log(data);
});
