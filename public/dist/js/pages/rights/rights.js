const form = document.getElementById('rights-form');
const user = document.getElementById('user');
const table = document.getElementById('rights');
const span = document.querySelector('.invalid-feedback');
// const checkBox = document.querySelectorAll('access');

user.addEventListener('change', function (e) {
  if (e.target.value !== '') {
    user.classList.remove('is-invalid');
    span.textContent = '';
  }
});

form.addEventListener('submit', function (e) {
  e.preventDefault();
  if (user.value == '') {
    user.classList.add('is-invalid');
    span.textContent = 'Select user';
    return;
  }

  document.form.submit();
});
