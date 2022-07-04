export function datatable(table) {
  $(document).ready(function () {
    'use strict';
    $(`#${table}-datatable`).DataTable({
      language: {
        paginate: {
          previous: "<i class='mdi mdi-chevron-left'>",
          next: "<i class='mdi mdi-chevron-right'>",
        },
        info: `Showing ${table} _START_ to _END_ of _TOTAL_`,
        lengthMenu:
          'Display <select class=\'form-select form-select-sm ms-1 me-1\'><option value="5">5</option><option value="10">10</option><option value="20">20</option><option value="-1">All</option></select> products',
      },
      pageLength: 10,
      drawCallback: function () {
        $('.dataTables_paginate > .pagination').addClass('pagination-rounded'),
          $('#' + table + '-datatable_length label').addClass('form-label');
      },
    });
  });
}
