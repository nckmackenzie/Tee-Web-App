export const spinnerContainer = document.querySelector('.spinner-container');
export const tableContainer = document.querySelector('.table-responsive');
export const previewBtn = document.querySelector('.preview');

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

export function setdatatable(
  tbl,
  columnDefs = [],
  searching = true,
  paging = true,
  info = true
) {
  $(document).ready(function () {
    'use strict';
    var table = $(`#${tbl}`).DataTable();
    table.destroy();
    table = $(`#${tbl}`)
      .DataTable({
        lengthChange: !1,
        buttons: ['print', 'excel', 'pdf'],
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

export function numberWithCommas(x) {
  return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}

export const formatToNumber = value => {
  if (typeof value === 'string' && value.includes(',')) {
    const passedVal = value.replaceAll(',', '');
    return parseFloat(passedVal);
  }
  return parseFloat(value);
};

export function updateColumnTotal(tbl, i, col) {
  var mytable = document.getElementById(tbl).getElementsByTagName('tbody')[0]; //
  let subTotal = Array.from(mytable.rows)
    .slice(0)
    .reduce((total, row) => {
      const convertedRow = isNaN(formatToNumber(row.cells[i].innerHTML))
        ? 0
        : formatToNumber(row.cells[i].innerHTML);
      return total + convertedRow;
    }, 0);
  document.getElementById(col).innerHTML = numberWithCommas(
    subTotal.toFixed(2)
  );
}

export function clearErrors() {
  document.querySelectorAll('.control').forEach(select => {
    select.classList.remove('is-invalid');
  });
  document.querySelectorAll('.invalid-feedback').forEach(span => {
    span.textContent = '';
  });
}

export function setLoadingSpinner(container, elmToClear = undefined) {
  if (elmToClear) {
    elmToClear.innerHTML = '';
  }
  container.innerHTML = '<div class="spinner md"></div>';
}

export function clearLoadingSpinner(container) {
  container.innerHTML = '';
}
