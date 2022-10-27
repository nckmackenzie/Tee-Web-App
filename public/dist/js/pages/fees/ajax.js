import { HOST_URL, getRequest, sendHttpRequest, alerBox } from '../utils.js';

export async function getReceiptNo() {
  return await getRequest(`${HOST_URL}/fees/getgraduationpaymentid`);
}

export async function getFirstAndLastIds() {
  return await getRequest(`${HOST_URL}/fees/getfirstandlastids`);
}
//garduation fee submission
export async function saveGraduationPayment(formdata) {
  const response = await sendHttpRequest(
    `${HOST_URL}/fees/graudationcreateedit`,
    'POST',
    JSON.stringify(formdata),
    { 'Content-Type': 'application/json' },
    alerBox
  );

  return response;
}
