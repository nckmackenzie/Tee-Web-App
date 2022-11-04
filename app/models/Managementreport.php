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
        
        return [$feepayments,$graduationfees,$generalexpenses,$purchases];
    }

    public function GetTrialBalanceReport($data)
    {
        $values = array_values($data); //extract values from associative array
        return loadresultset($this->db->dbh,'CALL sp_trialbalance(?,?)',[...$values]);
    }
}