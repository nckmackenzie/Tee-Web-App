import { numberWithCommas, setdatatable, updateColumnTotal } from '../utils.js';

export const settable = (data, results) => {
  let table = `
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
      table += `
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
  table += `
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
  results.innerHTML = table;
  setdatatable('table');
  updateColumnTotal('table', 3, 'subtotal');
  updateColumnTotal('table', 4, 'discount');
  updateColumnTotal('table', 5, 'netamount');
};
