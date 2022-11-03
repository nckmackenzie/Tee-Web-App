import {
  spinnerContainer,
  tableContainer,
  setLoadingSpinner,
  clearLoadingSpinner,
  previewBtn,
} from '../utils.js';
import {
  mandatoryFields,
  clearOnChange,
  getRequest,
  HOST_URL,
  validateDate,
  validation,
} from '../../utils.js';
import { clearErrorState } from '../../../utils/utils.js';
const sdateInput = document.querySelector('#from');
const edateInput = document.querySelector('#to');

//preview button handler
previewBtn.addEventListener('click', async function () {
  clearErrorState(mandatoryFields);
  if (validation() > 0) return;
  if (!validateDate(sdateInput, edateInput)) return;
  setLoadingSpinner(spinnerContainer, tableContainer);
  const sdateVal = sdateInput.value || new Date();
  const edateVal = edateInput.value || new Date();
  //ajax request to get report
  const data = await getRequest(
    `${HOST_URL}/pettycashreports/utilizationrpt?sdate=${sdateVal}&edate=${edateVal}`
  );
  clearLoadingSpinner(spinnerContainer);
  //loading done
  if (data && data.success) {
    console.log(data.results);
  }
});

clearOnChange(mandatoryFields);
