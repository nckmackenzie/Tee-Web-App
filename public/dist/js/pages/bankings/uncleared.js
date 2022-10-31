import { getUnclearedReport } from './ajax.js';
import { numberWithCommas } from '../../utils/utils.js';
import { setdatatable, updateColumnTotal } from '../reports/utils.js';

const urlSearchParams = new URLSearchParams(window.location.search);
const params = Object.fromEntries(urlSearchParams.entries());
const spinnerContainer = document.querySelector('.spinner-container');
const tableContainer = document.querySelector('.table-responsive');

function loadingState() {
  tableContainer.innerHTML = '';
  spinnerContainer.innerHTML = '<div class="spinner md"></div>';
}

function resetState() {
  spinnerContainer.innerHTML = '';
}

async function loadReport() {
  loadingState();
  const { type, sdate, edate } = params;
  const data = await getUnclearedReport(type, sdate, edate);
  resetState();
  if (data && data.success) {
    const { results } = data;
    tableContainer.innerHTML = table(results);
    setdatatable('table');
    updateColumnTotal('table', 1, 'totals');
  }
}

function table(data) {
  let html = `
    <table class="table table-sm table-bordered nowrap dt-responsive w-100" id="table">
        <thead class="table-light">
            <tr>
                <th>Transaction Date</th>
                <th>Amount</th>
                <th>Reference</th>
                <th>Narration</th>
            </tr>
        </thead>
        <tbody>`;
  data.forEach(dt => {
    html += `
                <tr>
                    <td>${dt.transactionDate}</td>
                    <td>${numberWithCommas(dt.amount)}</td>
                    <td>${dt.reference}</td>
                    <td>${dt.narration}</td>
                </tr>
            `;
  });
  html += `
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th id="totals"></th>
                <th colspan="2"></th>
            </tr>
        </tfoot>
    </table>
  `;

  return html;
}
loadReport();
