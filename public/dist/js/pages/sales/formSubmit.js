import { table, headerDetails } from './elements.js';
function checkedFields() {
  const students = [];
  const tablikes = document.querySelectorAll('.table-like');
  tablikes.forEach(tblike => {
    const checkState = tblike.querySelector('input[type="checkbox"]').checked;
    const studentId = tblike.querySelector('input[type="number"]').value;
    students.push({ checkState, studentId });
  });

  return students;
}

function tableFields() {
  const tableData = [];
  const tbody = table.getElementsByTagName('tbody')[0];
  const trs = tbody.querySelectorAll('tr');
  trs.forEach(tr => {
    const bid = tr.querySelector('.bid').value;
    const bname = tr.querySelector('.bname').value;
    const qty = tr.querySelector('.qty').value;
    const rate = tr.querySelector('.rate').value;
    const value = tr.querySelector('.value').value;
    tableData.push({ bid, bname, qty, rate, value });
  });

  return tableData;
}

export function submitHandler() {
  console.log(tableFields());
  console.log(headerDetails());
  console.log(checkedFields());
}
