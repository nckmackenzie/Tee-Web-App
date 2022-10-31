import { getRequest, HOST_URL, sendHttpRequest, alerBox } from '../utils.js';

export async function getTransactions(type, sdate, edate) {
  const url = `${HOST_URL}/bankings/fetchtransactions?type=${type}&sdate=${sdate}&edate=${edate}`;
  const response = await getRequest(url);
  return response;
}

export async function clearBankings(formdata) {
  const response = await sendHttpRequest(
    `${HOST_URL}/bankings/cleartransactions`,
    'POST',
    JSON.stringify(formdata),
    { 'Content-Type': 'application/json' },
    alerBox
  );

  return response;
}

export async function getBankingValues(sdate, edate) {
  const url = `${HOST_URL}/bankings/getbankingvalues?sdate=${sdate}&edate=${edate}`;
  const res = await getRequest(url);
  return res;
}

export async function getUnclearedReport(type, sdate, edate) {
  const url = `${HOST_URL}/bankings/getuncleared?type=${type}&sdate=${sdate}&edate=${edate}`;
  const res = await getRequest(url);
  return res;
}
