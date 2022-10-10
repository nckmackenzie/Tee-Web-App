import {
  sendHttpRequest,
  mandatoryFields,
  clearOnChange,
  validation,
  alerBox,
  HOST_URL,
} from '../utils.js';
import { setdatatable } from '../reports/utils.js';

const previewBtn = document.querySelector('.preview');
const sdateInput = document.querySelector('#startdate');
const edateInput = document.querySelector('#enddate');
const spinnerContainer = document.querySelector('.spinner-container');
const resultsDiv = document.querySelector('#results');
const table = document.querySelector('#logs-datatable');
const tbody = table.getElementsByTagName('tbody')[0];
//onclick
previewBtn.addEventListener('click', async function () {
  if (validation() > 0) return;

  if (
    new Date(sdateInput.value).getTime() > new Date(edateInput.value).getTime()
  ) {
    sdateInput.classList.add('is-invalid');
    sdateInput.nextSibling.nextSibling.textContent =
      'Invalid date! Has to be less than end date';
    return;
  }
  const sdate = sdateInput.value;
  const edate = edateInput.value;
  setLoadingState();
  const data = await sendHttpRequest(
    `${HOST_URL}/users/fetchlogs?sdate=${sdate}&edate=${edate}`,
    'GET',
    undefined,
    {},
    alerBox
  );
  removeLoadingState();
  if (data && data.length > 0) {
    setTbody(data);
    setdatatable('logs-datatable', columnDefs());
  }
});

function setLoadingState() {
  spinnerContainer.innerHTML = '<div class="spinner md"></div>';
  previewBtn.disabled = true;
  resultsDiv.classList.add('d-none');
  tbody.innerHTML = '';
}

function removeLoadingState() {
  spinnerContainer.innerHTML = '';
  previewBtn.disabled = false;
  resultsDiv.classList.remove('d-none');
}

function setTbody(data) {
  data.forEach(dt => {
    let html = `
            <tr>
                <td>${dt.saleDate}</td>
                <td>${dt.editDate}</td>
                <td>${dt.editedBy}</td>
                <td>${dt.reason}</td>
            </tr>
        `;
    tbody.insertAdjacentHTML('beforeend', html);
  });
}

function columnDefs() {
  return [
    { width: '10%', targets: 0 },
    { width: '10%', targets: 1 },
    { width: '10%', targets: 2 },
  ];
}
clearOnChange(mandatoryFields);
