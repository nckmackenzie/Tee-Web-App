import { HOST_URL, getRequest, sendHttpRequest, alerBox } from '../utils.js';

export async function getReceiptNo() {
  return await getRequest(`${HOST_URL}/fees/getgraduationpaymentid`);
}

export async function getTransaction(receiptno) {
  const response = await getRequest(
    `${HOST_URL}/fees/getgraduationtxn?receiptno=${receiptno}`
  );
  return response;
}
