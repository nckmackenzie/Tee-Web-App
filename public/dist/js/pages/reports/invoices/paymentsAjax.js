import { HOST_URL } from '../../utils.js';

const url = `${HOST_URL}/invoicereports`;

export async function getSupplierOrInvoice(type) {
  const res = await fetch(`${url}/get_invoice_or_supplier?type=${type}`);

  return await res.json();
}
