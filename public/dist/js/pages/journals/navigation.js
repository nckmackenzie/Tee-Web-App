import { getData } from './ajax-requests.js';
import { debitsTotalInput, creditsTotalInput } from './calculations.js';
import { table } from './index.js';
const prevBtn = document.getElementById('prevbtn');
const nextBtn = document.getElementById('nextbtn');
const deleteBtn = document.getElementById('deletbtn');
const journalNoInput = document.getElementById('journalno');
const firstJournalNoInput = document.getElementById('firstjournalno');
const lastJournalNoInput = document.getElementById('journalnohidden');
const jdateInput = document.getElementById('jdate');
const narrationInput = document.getElementById('description');
const iseditInput = document.getElementById('isedit');

function enableDisableNavBtns() {
  if (+journalNoInput.value === +firstJournalNoInput.value)
    prevBtn.disabled = true;
  if (+journalNoInput.value < +lastJournalNoInput.value)
    nextBtn.disabled = false;
  if (+journalNoInput.value > +firstJournalNoInput.value)
    prevBtn.disabled = false;
  if (+journalNoInput.value !== +lastJournalNoInput.value)
    deleteBtn.disabled = false;

  iseditInput.value = true;
  if (+journalNoInput.value === +lastJournalNoInput.value) {
    nextBtn.disabled = true;
    deleteBtn.disabled = true;
    iseditInput.value = false;
  }
}

function clear() {
  jdateInput.value =
    narrationInput.value =
    debitsTotalInput.value =
    creditsTotalInput.value =
      '';
  const body = table.getElementsByTagName('tbody')[0];
  body.innerHTML = '';
}

prevBtn.addEventListener('click', async function () {
  const prevJournalNo = +journalNoInput.value - 1;
  if (!prevJournalNo) return;
  journalNoInput.value = prevJournalNo;
  enableDisableNavBtns();
  clear();
  bindData(prevJournalNo);
});

nextBtn.addEventListener('click', function () {
  const currJournalNo = +journalNoInput.value;
  if (!currJournalNo) return;
  journalNoInput.value = currJournalNo + 1;
  enableDisableNavBtns();
  clear();
  const isLast = +journalNoInput.value === +lastJournalNoInput.value;
  if (isLast) return;
  bindData(+journalNoInput.value);
});

async function bindData(no) {
  const data = await getData(no);
  debitsTotalInput.value = data.debitstotal;
  creditsTotalInput.value = data.creditstotal;
  narrationInput.value = data.narration;
  jdateInput.value = data.jdate;
  const body = table.getElementsByTagName('tbody')[0];
  deleteBtn.setAttribute('data-id', no);

  data?.fields?.forEach(field => {
    const html = `
      <tr>
        <td class="d-none"><input type="text" name="accountsid[]" value="${field.aid}" readonly></td>
        <td><input type="text" class="table-input w-100" name="accountsname[]" value="${field.name}" readonly></td>
        <td style="width:15%"><input type="text" class="table-input" name="types[]" value="${field.type}" readonly></td>
        <td style="width:15%"><input type="text" class="table-input" name="debits[]" value="${field.debit}" readonly></td>
        <td style="width:15%"><input type="text" class="table-input" name="credits[]" value="${field.credit}" readonly></td>
        <td style="width:10%"><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
      </tr>
    `;
    let newRow = body.insertRow(body.rows.length);
    newRow.innerHTML = html;
  });
}
