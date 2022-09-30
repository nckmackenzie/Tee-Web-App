import { HOST_URL } from '../../utils.js';

const url = `${HOST_URL}/invoicereports`;

export async function getSupplierOrInvoice(type) {
  const res = await fetch(`${url}/get_invoice_or_supplier?type=${type}`);

  return await res.json();
}

export async function getSupplierPayments(
  type,
  sdate = null,
  edate = null,
  criteria = null
) {
  let res;
  if (type === 'bysupplier') {
    res = await fetch(
      `${url}/getpaymentsrpt?type=${type}&sdate=${sdate}&edate=${edate}&supplier=${criteria}`
    );
  } else if (type === 'bydate') {
    res = await fetch(
      `${url}/getpaymentsrpt?type=${type}&sdate=${sdate}&edate=${edate}`
    );
  } else if (type === 'byinvoice') {
    res = await fetch(
      `${url}/getpaymentsrpt?type=${type}&invoiceno=${criteria}`
    );
  }

  return await res.json();
}

//ajax request form getting supplier statement
export async function getSupplierStatement(supplier, sdate, edate) {
  const res = await fetch(
    `${url}/statementrpt?supplier=${supplier}&sdate=${sdate}&edate=${edate}`
  );
  return await res.json();
}
