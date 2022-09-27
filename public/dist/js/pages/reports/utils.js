export function validatedate(startEl, endEl, startSpan, endSpan) {
  startEl.classList.remove('is-invalid');
  endEl.classList.remove('is-invalid');
  startSpan.textContent = endSpan.textContent = '';
  if (startEl.value === '') {
    startEl.classList.add('is-invalid');
    startSpan.textContent = 'Select start date';
  }
  if (endEl.value === '') {
    endEl.classList.add('is-invalid');
    endSpan.textContent = 'Select end date';
  }
  if (new Date(startEl.value).getTime() > new Date(endEl.value).getTime()) {
    startEl.classList.add('is-invalid');
    startSpan.textContent = 'start date cannot be greater than end date';
  }
  if (startSpan.textContent !== '' || endSpan.textContent !== '') return false;
  return true;
}

export function setdatatable(tbl) {
  $(document).ready(function () {
    'use strict';
    var table = $(`#${tbl}`).DataTable();
    table.destroy();
    table = $(`#${tbl}`)
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
      .appendTo(`#${tbl}_wrapper .col-md-6:eq(0)`);
  });
}

export function updateColumnTotal(tbl, i, col) {
  var mytable = document.getElementById(tbl).getElementsByTagName('tbody')[0]; //
  let subTotal = Array.from(mytable.rows)
    .slice(0)
    .reduce((total, row) => {
      return total + parseFloat(row.cells[i].innerHTML);
    }, 0);
  document.getElementById(col).innerHTML = subTotal.toFixed(2);
}
