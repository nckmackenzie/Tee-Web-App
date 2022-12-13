import {
  clearOnChange,
  getRequest,
  HOST_URL,
  mandatoryFields,
  validateDate,
  validation,
} from '../../utils.js';
import { settable } from './tables.js';

const typeSelect = document.querySelector('#type');
const criteriaSelect = document.querySelector('#criteria');
const startInput = document.querySelector('#start');
const endInput = document.querySelector('#end');
const preview = document.querySelector('.preview');
const results = document.querySelector('#results');

//report type event handler
typeSelect.addEventListener('click', async function (e) {
  if (!e.target.value || e.target.value === '') return;

  if (e.target.value === 'all' || e.target.value === 'bycourse') {
    criteriaSelect.value = '';
    criteriaSelect.disabled = true;
    criteriaSelect.classList.remove('mandatory');
    criteriaSelect.nextSibling.nextSibling.textContent = '';
    return;
  }
  criteriaSelect.disabled = false;
  criteriaSelect.classList.add('mandatory');
  criteriaSelect.innerHTML = await getCriteria(e.target.value);
});

//criteria event handlers
criteriaSelect.addEventListener('click', function (e) {
  if (!e.target.value || !e.target.value === '') return;
  this.classList.remove('is-invalid');
  this.nextSibling.nextSibling.textContent = '';
});

//preview button
preview.addEventListener('click', async function () {
  if (validation() > 0) return;
  if (!validateDate(startInput, endInput)) return;
  const type = typeSelect.value || 'all';
  const criteria = criteriaSelect.value || '';
  const sdate = startInput.value || new Date();
  const edate = endInput.value || new Date();
  const url = `${HOST_URL}/reports/salesrpt?type=${type}&sdate=${sdate}&edate=${edate}&criteria=${criteria}`;
  const data = await getRequest(url);
  settable(type, data, results);
});

//get criterias
async function getCriteria(type) {
  const url = `${HOST_URL}/reports/getsalesreportcriteria?type=${type}`;
  return await getRequest(url);
}

clearOnChange(mandatoryFields);
