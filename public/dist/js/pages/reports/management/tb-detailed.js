import { getUrlParams, numberWithCommas } from '../../../utils/utils.js';
import { dateFormat, getRequest, HOST_URL } from '../../utils.js';
import {
  clearLoadingSpinner,
  setLoadingSpinner,
  setdatatable,
  spinnerContainer,
  tableContainer,
} from '../utils.js';
const titleElm = document.querySelector('h5');

async function loadDetailedReport() {
  setLoadingSpinner(spinnerContainer, tableContainer);
  const { acc, sdate, edate } = getUrlParams();

  const title = `${capitalizeFirstLetter(acc)} details between ${dateFormat(
    sdate
  )} to ${dateFormat(edate)}}`;
  titleElm.innerText = title;
  //load data
  const data = await getRequest(
    `${HOST_URL}/managementreports/getledgerdetailedrpt?account=${acc}&sdate=${sdate}&edate=${edate}`
  );
  clearLoadingSpinner(spinnerContainer);
  //if success
  if (data && data.success) {
    tableContainer.innerHTML = createTable(data.results);
    setdatatable('table');
    document.getElementById('debitstotal').innerText = numberWithCommas(
      data.debitstotal
    );
    document.getElementById('creditstotal').innerText = numberWithCommas(
      data.creditstotal
    );
  }
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function createTable(data) {
  let html = `
        <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="table">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Account</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Narration</th>
                    <th>Transaction</th>
                </tr>
            </thead>
            <tbody>
    `;
  data.forEach(dt => {
    html += `
                <tr>
                    <td>${dt.transactionDate}</td>
                    <td>${dt.account}</td>
                    <td class="text-center">${numberWithCommas(dt.debit)}</td>
                    <td class="text-center">${numberWithCommas(dt.credit)}</td>
                    <td>${dt.narration}</td>
                    <td>${dt.transactionType}</td>
                </tr>
    `;
  });
  html += `
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th id="debitstotal"></th>
                    <th id="creditstotal"></th>
                    <th colspan="2"></th>
                </tr>
            </tfoot>
        </table>  
  `;

  return html;
}
loadDetailedReport();
