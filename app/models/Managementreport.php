<?php
class Managementreport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetIncomeStatementValues($data)
    {
        $values = array_values($data);
        $feepayments = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(AmountPaid),0) AS SumOfValue 
                                                  FROM fees_payment 
                                                  WHERE (PaymentDate BETWEEN ? AND ?) AND (Deleted = 0) ',[...$values]);
        $graduationfees = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(AmountPaid),0) AS SumOfValue 
                                                     FROM graduation_fee_payment 
                                                     WHERE (PaymentDate BETWEEN ? AND ?) AND (Deleted = 0) ',[...$values]); 
        $generalexpenses = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Amount),0) AS SumOfValue 
                                                      FROM expenses 
                                                      WHERE (ExpenseDate BETWEEN ? AND ?) AND (Deleted = 0) ',[...$values]);
        $purchases = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(InclusiveVat),0) AS SumOfValue 
                                                FROM invoice_header 
                                                WHERE (InvoiceDate BETWEEN ? AND ?) AND (Deleted = 0) ',[...$values]); 
        $sales = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(AmountPaid),0) As SaleValue FROM sales_header 
                                            WHERE (SalesDate BETWEEN ? AND ?) AND (Deleted = 0)',[...$values]);
        return [$feepayments,$graduationfees,$generalexpenses,$purchases,$sales];
    }

    public function GetTrialBalanceReport($data)
    {
        $values = array_values($data); //extract values from associative array
        return loadresultset($this->db->dbh,'CALL sp_trialbalance(?,?)',[...$values]);
    }

    public function GetLedgerDetails($data)
    {
        $values = array_values($data);
        return loadresultset($this->db->dbh,'SELECT * FROM vw_ledger 
                                             WHERE (TransactionDate BETWEEN ? AND ?) AND (Account = ?)',[...$values]);
    }

    public function BalancesheetAssets($date)
    {
        return loadresultset($this->db->dbh,'CALL sp_balancesheet_assets(?)',[$date]);
    }

    public function BalancesheetLiablityAndEquity($date)
    {
        return loadresultset($this->db->dbh,'CALL sp_balanceSheet_liablityequity(?)',[$date]);
    }

    public function GetTotals($date)
    {
        $assetsdebits = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Debit),0) as sumofdebits 
                                                   FROM   ledger 
                                                   WHERE  (AccountId=3) AND (TransactionDate <= ?)
                                                   AND    (Deleted = 0)',[$date]);
        $assetscredits = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Credit),0) as sumofcredits 
                                                    FROM   ledger 
                                                    WHERE  (AccountId=3) AND (TransactionDate <= ?)
                                                    AND    (Deleted = 0)',[$date]);
        $assetstotal = floatval($assetsdebits) - floatval($assetscredits);
        //liabilties
        $liabilityequitydebits = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Debit),0) as sumofdebits 
                                                            FROM   ledger 
                                                            WHERE  (AccountId = 4 OR AccountId = 6) AND (TransactionDate <= ?)
                                                            AND    (Deleted = 0)',[$date]);
        $liabilityequitycredits = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Credit),0) as sumofcredits 
                                                             FROM   ledger 
                                                             WHERE  (AccountId = 4 OR AccountId = 6) AND (TransactionDate <= ?)
                                                             AND    (Deleted = 0)',[$date]);
        $liabilityequitytotal = floatval($liabilityequitydebits) - floatval($liabilityequitycredits);

        return [$assetstotal,$liabilityequitytotal];
    }

    public function GetNetIncome($date)
    {
        $revenuedebit = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Debit),0) 
                                                   FROM   ledger 
                                                   WHERE  (AccountId=1) AND TransactionDate <= ?
                                                   AND     Deleted = 0',[$date]);
        $revenuecredit = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Credit),0) 
                                                    FROM   ledger 
                                                    WHERE  (AccountId=1) AND TransactionDate <= ?
                                                    AND     Deleted = 0',[$date]);
        $revenuebalance = floatval($revenuedebit) - floatval($revenuecredit);
        //expenses 
        $expensesdebit = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Debit),0) 
                                                    FROM   ledger 
                                                    WHERE  (AccountId=2) AND TransactionDate <= ?
                                                    AND     Deleted = 0',[$date]);
        $expensescredit = getdbvalue($this->db->dbh,'SELECT IFNULL(SUM(Credit),0) 
                                                     FROM   ledger 
                                                     WHERE  (AccountId=2) AND TransactionDate <= ?
                                                     AND     Deleted = 0',[$date]);
        $expensebalance = floatval($expensesdebit) - floatval($expensescredit);
        return floatval($revenuebalance) - floatval($expensebalance);                                                                                        
    }
}