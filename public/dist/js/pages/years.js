$(document).ready(function () {
  'use strict';
  $('#years-datatable').DataTable({
    language: {
      paginate: {
        previous: "<i class='mdi mdi-chevron-left'>",
        next: "<i class='mdi mdi-chevron-right'>",
      },
      info: 'Showing Years _START_ to _END_ of _TOTAL_',
      lengthMenu:
        'Display <select class=\'form-select form-select-sm ms-1 me-1\'><option value="5">5</option><option value="10">10</option><option value="20">20</option><option value="-1">All</option></select> products',
    },
    pageLength: 10,
    drawCallback: function () {
      $('.dataTables_paginate > .pagination').addClass('pagination-rounded'),
        $('#years-datatable_length label').addClass('form-label');
    },
  });
});

function rowFunction(el) {
  const input = document.querySelector('#id');
  var n = el.parentNode.parentNode.cells[0].textContent;
  input.value = +n;
  // ...
}
