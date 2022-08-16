import { HOST_URL, displayAlert } from '../utils.js';

const btn = document.querySelector('[data-id="btn"]');
const bookSelect = document.querySelector('#book');
const courseSelect = document.querySelector('#course');
const groupSelect = document.querySelector('#group');
const resultsDiv = document.querySelector('#results');
const alertBox = document.getElementById('alertBox');

courseSelect.addEventListener('change', async function (e) {
  const course = +e.target.value;
  if (!course) return;
  bookSelect.innerHTML = '';
  const res = await fetch(`${HOST_URL}/exams/getbooks?course=${course}`);
  const data = await res.json();
  bookSelect.innerHTML = data;
});

btn.addEventListener('click', async function () {
  const selects = document.querySelectorAll('select');
  let validValue = 0;
  selects.forEach(select => {
    select.classList.remove('is-invalid');
    if (!select.value || String(select.value).trim() === '') {
      select.classList.add('is-invalid');
    } else {
      select.classList.add('is-valid');
      validValue++;
    }
  });
  if (validValue !== 3) return;
  resultsDiv.innerHTML = '';
  const data = await fetchedResultsFromDb();
  resultsDiv.innerHTML = data;
  setdatatable();
});

async function fetchedResultsFromDb() {
  const bookValue = +bookSelect.value;
  const groupValue = +groupSelect.value;
  if (!bookValue || !groupValue) {
    displayAlert(alertBox, 'Ensure all required fields are correctly selected');
    return;
  }

  const res = await fetch(
    `${HOST_URL}/exams/getfinalpoints?gid=${groupValue}&bid=${bookValue}`
  );
  const data = await res.json();
  return data;
}

function setdatatable() {
  $(document).ready(function () {
    'use strict';
    var table = $('#table').DataTable();
    table.destroy();
    table = $('#table')
      .DataTable({
        lengthChange: !1,
        buttons: ['copy', 'print'],
        language: {
          paginate: {
            previous: "<i class='mdi mdi-chevron-left'>",
            next: "<i class='mdi mdi-chevron-right'>",
          },
        },
        drawCallback: function () {
          $('.dataTables_paginate > .pagination').addClass(
            'pagination-rounded'
          );
        },
      })
      .buttons()
      .container()
      .appendTo('#table_wrapper .col-md-6:eq(0)');
  });
}
