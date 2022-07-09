import { datatable } from './datatable.js';
import { btnClick } from './utils.js';
datatable('books');
btnClick('.btndel', 'id');
// const btns = document.querySelectorAll('.btndel');
// Array.prototype.forEach.call(btns, function addClickListener(btn) {
//   btn.addEventListener('click', function (event) {
//     const id = this.dataset.id;
//     console.log(id);
//   });
// });
