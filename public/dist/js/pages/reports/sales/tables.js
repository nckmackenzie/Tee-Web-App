import { numberWithCommas, setdatatable, updateColumnTotal } from '../utils.js';

export const settable = (type, data, results) => {
  let table = tableHtml(type, data);
  results.innerHTML = table;
  setdatatable('table');
  if (type !== 'bycourse') {
    updateColumnTotal('table', 3, 'subtotal');
    updateColumnTotal('table', 4, 'discount');
    updateColumnTotal('table', 5, 'netamount');
  } else {
    updateColumnTotal('table', 1, 'total');
  }
};

function tableHtml(type, data) {
  let html;
  if (type !== 'bycourse') {
    html = `
    <table class="table table-sm w-100 dt-responsive nowrap" id="table">
      <thead class="table-light">
        <tr>
          <th>Sale ID</th>
          <th>Sales Date</th>
          <th>Sold To</th>
          <th>Sub Total</th>
          <th>Discount</th>
          <th>Net Amount</th>
          <th>Reference</th>
        </tr>
      </thead>
      <tbody>`;
    if (data.length > 0) {
      data.forEach(dt => {
        html += `
            <tr>
              <td>${dt.saleId}</td>
              <td>${dt.salesDate}</td>
              <td>${dt.soldTo}</td>
              <td>${numberWithCommas(dt.subTotal)}</td>
              <td>${numberWithCommas(dt.discount)}</td>
              <td>${numberWithCommas(dt.netAmount)}</td>
              <td>${dt.reference}</td>
            </tr>
          `;
      });
    }
    html += `
      </tbody>
      <tfoot class="table-light">
        <th colspan="3" style="text-align:center">Total:</th>
        <th id="subtotal"></th>
        <th id="discount"></th>
        <th id="netamount"></th>
        <th></th>
      </tfoot>
    </table>
  `;
  } else {
    html = `
    <table class="table table-sm w-100 dt-responsive nowrap" id="table">
    <thead class="table-light">
      <tr>
        <th>Course Name</th>
        <th>Sales Value</th>
      </tr>
    </thead>
    <tbody>`;
    if (data.length > 0) {
      data.forEach(dt => {
        html += `
          <tr>
            <td>${dt.course}</td>
            <td>${numberWithCommas(dt.value)}</td>
          </tr>
        `;
      });
    }
    html += `
    </tbody>
    <tfoot class="table-light">
      <th style="text-align:center">Total:</th>
      <th id="total"></th>
    </tfoot>
  </table>
    `;
  }

  return html;
}
