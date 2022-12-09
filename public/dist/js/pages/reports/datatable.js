export function setdatatable(
  tbl,
  columnDefs = [],
  searching = true,
  paging = true,
  info = true,
  buttonOptions = undefined
) {
  $(document).ready(function () {
    'use strict';
    var table = $(`#${tbl}`).DataTable();
    table.destroy();
    table = $(`#${tbl}`)
      .DataTable({
        lengthChange: !1,
        buttons: buttonOptions || ['print', 'excel', 'pdf'],
        columnDefs: columnDefs,
        ordering: false,
        searching: searching,
        paging: paging,
        info: info,
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
      .appendTo(`#${tbl}_wrapper .col-md-6:eq(0)`);
  });
}
