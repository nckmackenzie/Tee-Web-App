export const debitsTotalInput = document.getElementById('debits');
export const creditsTotalInput = document.getElementById('credits');

//get total for value in table
export function getDebitsCreditsTotal(table) {
  let debitsTotal = 0;
  let creditsTotal = 0;
  for (var i = 1; i < table.rows.length; i++) {
    debitsTotal =
      debitsTotal + parseFloat(table.rows[i].cells[3].children[0].value);
    creditsTotal =
      creditsTotal + parseFloat(table.rows[i].cells[4].children[0].value);
  }

  debitsTotalInput.value = debitsTotal.toFixed(2);
  creditsTotalInput.value = creditsTotal.toFixed(2);
  //   return [debitsTotal, creditsTotal];
}
