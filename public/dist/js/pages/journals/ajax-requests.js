import { HOST_URL } from '../utils.js';
export async function getData(id) {
  const res = await fetch(`${HOST_URL}/journals/getjournaldetails?id=${id}`);
  const data = await res.json();
  return data;
}
