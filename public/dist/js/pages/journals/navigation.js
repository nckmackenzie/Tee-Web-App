const prevBtn = document.getElementById('prevbtn');
const nextBtn = document.getElementById('nextbtn');
const deleteBtn = document.getElementById('deletbtn');
const journalNoInput = document.getElementById('journalno');
const firstJournalNoInput = document.getElementById('firstjournalno');
const lastJournalNoInput = document.getElementById('journalnohidden');

function enableDisableNavBtns() {
  if (+journalNoInput.value === +firstJournalNoInput.value)
    prevBtn.disabled = true;
  if (+journalNoInput.value < +lastJournalNoInput.value)
    nextBtn.disabled = false;
  if (+journalNoInput.value === +lastJournalNoInput.value) {
    nextBtn.disabled = true;
    deleteBtn.disabled = true;
  }
  if (+journalNoInput.value > +firstJournalNoInput.value)
    prevBtn.disabled = false;
  if (+journalNoInput.value !== +lastJournalNoInput.value)
    deleteBtn.disabled = false;
}

prevBtn.addEventListener('click', function () {
  const prevJournalNo = +journalNoInput.value;
  if (!prevJournalNo) return;
  journalNoInput.value = prevJournalNo - 1;
  enableDisableNavBtns();
  const isFirst = +journalNoInput.value === +firstJournalNoInput.value;
  if (isFirst) return;
});

nextBtn.addEventListener('click', function () {
  const currJournalNo = +journalNoInput.value;
  if (!currJournalNo) return;
  journalNoInput.value = currJournalNo + 1;
  enableDisableNavBtns();
  const isLast = +journalNoInput.value === +lastJournalNoInput.value;
  if (isLast) return;
});
