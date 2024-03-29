<?php

class Sale
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetSales()
    {
        $this->db->query('SELECT * FROM vw_sales WHERE (CenterId = :cid)');
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        return $this->db->resultset();
    }

    public function GetStudentsOrGroups($type)
    {
        if($type === 'student'){
            $this->db->query('SELECT ID,UCASE(StudentName) AS CriteriaName 
                              FROM   students 
                              WHERE  (Deleted =0) AND (StatusId = 1)');
        }elseif ($type === 'group') {
            $this->db->query("SELECT ID,UCASE(CONCAT(GroupName,'-',IFNULL(g.ParishName,''))) AS CriteriaName 
                              FROM   groups g
                              WHERE  (Deleted =0) AND (Active = 1)");
        }
        
        return $this->db->resultset();
    }
    
    public function GetCenterDetails()
    {
        $this->db->query("SELECT 
                            UCASE(CenterName) As CenterName, 
                            Contact,
                            IFNULL(Email,'N/A') AS Email 
                          FROM
                            centers
                          WHERE 
                            ID = :id");
        $this->db->bind(':id',intval($_SESSION['centerid']));
        return $this->db->single();
    }

    public function GetSaleId()
    {
        $this->db->query("SELECT COUNT(*) FROM sales_header WHERE (CenterId = :cid) AND (Deleted = 0)");
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        if(intval($this->db->getvalue()) === 0){
            return 1;
        }else{
            $this->db->query('SELECT SalesID FROM sales_header 
                              WHERE (CenterId = :cid) AND (Deleted = 0) 
                              ORDER BY SalesID DESC LIMIT 1');
            $this->db->bind(':cid',intval($_SESSION['centerid']));
            return intval($this->db->getvalue()) + 1;
        }
    }

    public function GetBooks()
    {
        $this->db->query("SELECT ID,UCASE(Title) AS Title 
                          FROM books 
                          WHERE (Deleted = 0) AND (Active = 1)
                          ORDER BY Title");
        return $this->db->resultset();
    }

    public function CheckRefExists($ref,$id)
    {
        $this->db->query("SELECT COUNT(*) FROM sales_header WHERE (ID <> :id) AND (Reference = :ref) AND (Deleted = 0)");
        $this->db->bind(':id',$id);
        $this->db->bind(':ref',strtolower($ref));
        if(intval($this->db->getvalue()) > 0){
            return false;
        }else{
            return true;
        }
    }

    public function GetStockAndRate($date,$id)
    {
        $this->db->query("SELECT fn_getsellingprice(b.ID,:cdate) As Rate,
                                 fn_getstock(b.ID,:cdate,:cid) As Stock 
                          FROM `books` b WHERE b.ID = :bid");
        $this->db->bind(':cdate',$date);
        $this->db->bind(':cid',intval($_SESSION['centerid']));
        $this->db->bind(':bid',$id);
        return $this->db->single();
    }

    function GetItemBuyingPrice($date,$item){
        $this->db->query('SELECT 
                            IFNULL(BuyingPrice, 0) AS bp
                          FROM
                            prices
                          WHERE
                            (:tdate BETWEEN StartDate AND EndDate)
                            AND (BookId = :bid)
                            AND (Deleted = 0)');
        $this->db->bind(':tdate',$date);
        $this->db->bind(':bid',intval($item));
        return floatval($this->db->getvalue());
    }

    function GetBuyerName($type,$id){
        $this->db->query('SELECT '.ucwords($type).'Name FROM '.$type.'s WHERE ID = :id');
        $this->db->bind(':id',intval($id));
        return ucwords($this->db->getvalue());
    } 
    
    function GetGlDetails($bookid){
        $values = [];
        $this->db->query('SELECT GlAccountId FROM books WHERE ID = :id');
        $this->db->bind(':id',(int)$bookid);
        $glaccount = (int)$this->db->getvalue();

        $this->db->query('SELECT AccountName,AccountTypeId FROM accounttypes WHERE ID = :id');
        $this->db->bind(':id',$glaccount);
        $details = $this->db->single();
        array_push($values,$details->AccountName);
        array_push($values,$details->AccountTypeId);
        return $values;
    }

    function Save($data){
        $desc = 'Sale of '.count($data['books']) . ' book(s) to '.$this->GetBuyerName($data['saletype'],$data['buyer']);
        $saleid = $this->GetSaleId();
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO sales_header (SalesID,SalesDate,PayDate,SaleType,GroupId,StudentId,DeliveryFee,SubTotal,
                                          Discount,AmountPaid,PaymentMethodId,Reference,CenterId,
                                          UpdatedBy,UpdatedOn) 
                              VALUES(:saleid,:sdate,:pdate,:stype,:gid,:student,:delivery,:stotal,:discount,:paid,:pid,:ref,:cid,:upby,:upon)');
            $this->db->bind(':saleid',$saleid);
            $this->db->bind(':sdate',$data['sdate']);
            $this->db->bind(':pdate',$data['pdate']);
            $this->db->bind(':stype',$data['saletype']);
            $this->db->bind(':gid',$data['saletype'] === 'group' ? $data['buyer'] : null);
            $this->db->bind(':student',$data['saletype'] === 'student' ? $data['buyer'] : null);
            $this->db->bind(':delivery',$data['deliveryfee']);
            $this->db->bind(':stotal',$data['subtotal']);
            $this->db->bind(':discount',$data['discount']);
            // $this->db->bind(':net',!empty($data['net']) ? $data['net'] : 0);
            $this->db->bind(':paid',$data['paid']);
            // $this->db->bind(':bal',!empty($data['balance']) ? $data['balance'] : 0);
            $this->db->bind(':pid',$data['paymethod']);
            $this->db->bind(':ref',strtolower($data['reference']));
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->bind(':upby',(int)$_SESSION['userid']);
            $this->db->bind(':upon',date('Y-m-d H:i:s'));
            $this->db->execute();

            $tid = $this->db->dbh->lastInsertId();

            for ($i=0; $i < count($data['books']); $i++) { 
                $bp = $this->GetItemBuyingPrice($data['sdate'],$data['books'][$i]->bid);
                $issoftcopy = converttobool($data['books'][$i]->isSoft);
                $buyingvalue = $data['books'][$i]->qty * $bp;
                $sellingvalue =$data['books'][$i]->qty * $data['books'][$i]->rate;
                $this->db->query('INSERT INTO sales_details (HeaderId,BookId,Qty,BoughtValue,SellingValue,IsSoftCopy) 
                              VALUES(:hid,:bid,:qty,:bought,:selling,:soft)');
                $this->db->bind(':hid',$tid);
                $this->db->bind(':bid',$data['books'][$i]->bid);
                $this->db->bind(':qty',$data['books'][$i]->qty);
                $this->db->bind(':bought',$buyingvalue);
                $this->db->bind(':selling',$sellingvalue);
                $this->db->bind(':soft',$issoftcopy);
                $this->db->execute();

                if(!$issoftcopy){
                    $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                            TransactionType,TransactionId,CenterId) 
                                VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                    $this->db->bind(':tdate',$data['sdate']);
                    $this->db->bind(':bid',$data['books'][$i]->bid);
                    $this->db->bind(':qty',$data['books'][$i]->qty);
                    $this->db->bind(':ref',$data['reference']); 
                    $this->db->bind(':ttype',4);
                    $this->db->bind(':tid',$tid);
                    $this->db->bind(':cid',$_SESSION['centerid']);
                    $this->db->execute();
                }
                // $accountname = $this->GetGlDetails($data['books'][$i]->bid)[0];
                // $accountid = $this->GetGlDetails($data['books'][$i]->bid)[1];
                
            }

            //if sale to group
            if($data['saletype'] === 'group'){
                for($i = 0; $i < count($data['students']); $i++){
                    $this->db->query('INSERT INTO sales_students (SaleId,StudentId,Paid) VALUES(:sale,:student,:paid)');
                    $this->db->bind(':sale',$data['id']);
                    $this->db->bind(':student',$data['students'][$i]->studentId);
                    $this->db->bind(':paid',converttobool($data['students'][$i]->checkState));
                    $this->db->execute();
                }
            }

            savetoledger($this->db->dbh,$data['pdate'],'sales',0,$data['paid'],$desc,1,1,$tid,$_SESSION['centerid']);
            if(intval($data['paymethod']) === 1){
                savetoledger($this->db->dbh,$data['pdate'],'cash at hand',$data['paid'],0,$desc,3,1,$tid,$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['pdate'],'cash at bank',$data['paid'],0,$desc,3,1,$tid,$_SESSION['centerid']);
                savebankposting($this->db->dbh,$data['pdate'],(int)$data['paymethod'] === 2 ? 1 : 0,null,$data['paid'],
                                0,$data['reference'],$desc,1,$tid,$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return  false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if ($this->db->dbh->inTransaction()) {
                $this->db->dbh->rollBack();
            }
            throw $e;
            return false;
        }
    }

    function Update($data){
        $desc = 'Sale of '.count($data['books']) . ' book(s) to '.$this->GetBuyerName($data['saletype'],$data['buyer']);
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE sales_header SET SalesDate=:sdate,PayDate=:pdate,SaleType=:stype,GroupId=:gid,StudentId=:student
                                                      ,DeliveryFee=:delivery,SubTotal=:stotal,Discount=:discount,AmountPaid=:paid,PaymentMethodId=:pid,Reference=:ref,UpdatedBy=:upby,UpdatedOn=:upon 
                              WHERE (ID = :id)');
            $this->db->bind(':sdate',$data['sdate']);
            $this->db->bind(':pdate',$data['pdate']);
            $this->db->bind(':stype',$data['saletype']);
            $this->db->bind(':gid',$data['saletype'] === 'group' ? $data['buyer'] : null);
            $this->db->bind(':student',$data['saletype'] === 'student' ? $data['buyer'] : null);
            $this->db->bind(':stotal',$data['subtotal']);
            $this->db->bind(':delivery',$data['deliveryfee']);
            $this->db->bind(':discount',$data['discount']);
            // $this->db->bind(':net',!empty($data['net']) ? $data['net'] : 0);
            $this->db->bind(':paid',$data['paid']);
            // $this->db->bind(':bal',!empty($data['balance']) ? $data['balance'] : 0);
            $this->db->bind(':pid',$data['paymethod']);
            $this->db->bind(':ref',strtolower($data['reference']));
            $this->db->bind(':upby',(int)$_SESSION['userid']);
            $this->db->bind(':upon',date('Y-m-d H:i:s'));
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM sales_details WHERE HeaderId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM ledger WHERE TransactionType = 1 AND TransactionId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM bankpostings WHERE TransactionType = 1 AND TransactionId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM stockmovements WHERE TransactionType = 4 AND TransactionId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM sales_students WHERE SaleId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            for ($i=0; $i < count($data['books']); $i++) { 
                $bp = $this->GetItemBuyingPrice($data['sdate'],$data['books'][$i]->bid);
                $issoftcopy = converttobool($data['books'][$i]->isSoft);
                $buyingvalue = $data['books'][$i]->qty * $bp;
                $sellingvalue =$data['books'][$i]->qty * $data['books'][$i]->rate;
                $this->db->query('INSERT INTO sales_details (HeaderId,BookId,Qty,BoughtValue,SellingValue,IsSoftCopy) 
                                  VALUES(:hid,:bid,:qty,:bought,:selling,:soft)');
                $this->db->bind(':hid',$data['id']);
                $this->db->bind(':bid',$data['books'][$i]->bid);
                $this->db->bind(':qty',$data['books'][$i]->qty);
                $this->db->bind(':bought',$buyingvalue);
                $this->db->bind(':selling',$sellingvalue);
                $this->db->bind(':soft',$issoftcopy);
                $this->db->execute();

                if(!$issoftcopy){
                    $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                            TransactionType,TransactionId,CenterId) 
                                VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                    $this->db->bind(':tdate',$data['sdate']);
                    $this->db->bind(':bid',$data['books'][$i]->bid);
                    $this->db->bind(':qty',$data['books'][$i]->qty);
                    $this->db->bind(':ref',$data['reference']); 
                    $this->db->bind(':ttype',4);
                    $this->db->bind(':tid',$data['id']);
                    $this->db->bind(':cid',$_SESSION['centerid']);
                    $this->db->execute();
                }

                // $accountname = $this->GetGlDetails($data['books'][$i]->bid)[0];
                // $accountid = $this->GetGlDetails($data['books'][$i]->bid)[1];
                // savetoledger($this->db->dbh,$data['pdate'],$accountname,0,$sellingvalue,$desc,$accountid,1,$data['id'],$_SESSION['centerid']);
            }

            //if sale to group
            if($data['saletype'] === 'group'){
                for($i = 0; $i < count($data['students']); $i++){
                    $this->db->query('INSERT INTO sales_students (SaleId,StudentId,Paid) VALUES(:sale,:student,:paid)');
                    $this->db->bind(':sale',$data['id']);
                    $this->db->bind(':student',$data['students'][$i]->studentId);
                    $this->db->bind(':paid',converttobool($data['students'][$i]->checkState));
                    $this->db->execute();
                }
            }
            
            //edit logging
            $this->db->query('INSERT INTO edit_logs(SaleID,EditDate,SaleDate,ReasonForEdit,EditedBy,CenterId) 
                              VALUES(:saleid,:edate,:sdate,:reason,:editby,:cid)');
            $this->db->bind(':saleid',$data['id']);
            $this->db->bind(':edate',date('Y-m-d'));
            $this->db->bind(':sdate',$data['sdate']);
            $this->db->bind(':reason',$data['reason']);
            $this->db->bind(':editby',$_SESSION['userid']);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();

            savetoledger($this->db->dbh,$data['pdate'],'sales',0,$data['paid'],$desc,1,1,$data['id'],$_SESSION['centerid']);
            if(intval($data['paymethod']) === 1){
                savetoledger($this->db->dbh,$data['pdate'],'cash at hand',$data['paid'],0,$desc,3,1,$data['id'],$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['pdate'],'cash at bank',$data['paid'],0,$desc,3,1,$data['id'],$_SESSION['centerid']);
                savebankposting($this->db->dbh,$data['pdate'],$data['paymethod'] === 2 ? 1 : 0,null,$data['paid'],
                                0,$data['reference'],$desc,1,$data['id'],$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return  false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            http_response_code(500);
            if ($this->db->dbh->inTransaction()) {
                $this->db->dbh->rollBack();
            }
            error_log($e->getMessage(),0);
            return false;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            return $this->Save($data);
        }else{
            return $this->Update($data);
        }
    }

    public function GetSaleHeader($id)
    {
        $this->db->query('SELECT * FROM sales_header WHERE id = :id');
        $this->db->bind(':id',intval($id));
        return $this->db->single();
    }

    public function GetSaleDetails($id)
    {
        $this->db->query('SELECT * FROM vw_salesdetails WHERE HeaderId = :id');
        $this->db->bind(':id',intval($id));
        return $this->db->resultset();
    }

    public function GetStudentSales($id)
    {
        $sql = 'SELECT * FROM vw_sales_students WHERE (SaleId = ?)';
        return loadresultset($this->db->dbh,$sql,[$id]);
    }

    public function Delete($id)
    {
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE sales_header SET Deleted = 1
                              WHERE (ID=:id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('UPDATE stockmovements SET Deleted=1 WHERE TransactionType = 4 AND TransactionId = :id');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('UPDATE bankpostings SET Deleted =1 WHERE TransactionType = 1 AND TransactionId = :id');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('UPDATE ledger SET Deleted =1 WHERE TransactionType = 1 AND TransactionId = :id');
            $this->db->bind(':id',$id);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return  false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if ($this->db->dbh->inTransaction()) {
                $this->db->dbh->rollBack();
            }
            throw $e;
            return false;
        }
    }

    public function GetStudentsByGroup($gid)
    {
        $this->db->query('SELECT ID,StudentName,Contact 
                          FROM vw_membersbygroup
                          WHERE GroupId = :gid
                          ORDER BY StudentName');
        $this->db->bind(':gid',$gid);
        return $this->db->resultset();
    }

    public function GetSalesWithBalances()
    {
        return loadresultset($this->db->dbh,'SELECT * FROM vw_sales_balances WHERE CenterId = ?',[(int)$_SESSION['centerid']]);
    }

    public function GetBalanceDetails($id)
    {
        $balance = getdbvalue($this->db->dbh,'SELECT Balance from vw_sales_balances WHERE ID = ?',[$id]);
        $soldto = getdbvalue($this->db->dbh,'SELECT SoldTo from vw_sales_balances WHERE ID = ?',[$id]);
        return [$balance,$soldto];
    }

    public function ReceivePayment($data)
    {
        try {

            $this->db->dbh->beginTransaction();
            $amoutpaid = getdbvalue($this->db->dbh,'SELECT AmountPaid FROM sales_header WHERE (ID = ?)',[$data['saleid']]);

            $this->db->query('INSERT INTO sale_balance_pay (SaleId,PaymentDate,InitialBalance,AmountPaid,TransactionType)
                              VALUES(:saleid,:pdate,:balance,:amount,:ttype)');
            $this->db->bind(':saleid',$data['saleid']);
            $this->db->bind(':pdate',$data['paydate']);
            $this->db->bind(':balance',$data['balance']);
            $this->db->bind(':amount',$data['payment']);
            $this->db->bind(':ttype',11);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();
            $newpaid = $amoutpaid + $data['payment'];

            $this->db->query('UPDATE sales_header SET AmountPaid=:paid WHERE ID=:id');
            $this->db->bind(':paid',$newpaid);
            $this->db->bind(':id',$data['saleid']);
            $this->db->execute();

            $desc = 'sale balance payment ref '.$data['reference'];
            savetoledger($this->db->dbh,$data['paydate'],'sales',0,$data['payment'],$desc,1,11,$tid,$_SESSION['centerid']);
            if(intval($data['paymethod']) === 1){
                savetoledger($this->db->dbh,$data['paydate'],'cash at hand',$data['payment'],0,$desc,3,11,$tid,$_SESSION['centerid']);
            }else{
                savetoledger($this->db->dbh,$data['paydate'],'cash at bank',$data['payment'],0,$desc,3,11,$tid,$_SESSION['centerid']);
                savebankposting($this->db->dbh,$data['paydate'],(int)$data['paymethod'] === 2 ? 1 : 0,null,$data['payment'],
                                0,$data['reference'],$desc,11,$tid,$_SESSION['centerid']);
            }

            if(!$this->db->dbh->commit()){
                return  false;
            }else{
                return true;
            }

        } catch (PDOException $e) {
            if ($this->db->dbh->inTransaction()) {
                $this->db->dbh->rollBack();
            }
            error_log($e->getMessage(),0);
            return false;
        } catch (Exception $e) {
            error_log($e->getMessage(),0);
            return false;
        }
    }
}