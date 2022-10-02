<?php
class Stock
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }

    //get receipts
    public function GetReceiptsOrTransfers($type)
    {
        $this->db->query("CALL sp_getreceiptsortransfers(:action,:cid)");
        $this->db->bind(':action',$type);
        $this->db->bind(':cid',$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function GetGrnNo()
    {
        return getuniqueid($this->db->dbh,'GrnNo','receiptsheader',$_SESSION['centerid']);
    }

    public function GetMtnNo($isedit = false,$id = '')
    {
        if($isedit) return getdbvalue($this->db->dbh,'SELECT fn_getmtnno(?)',[$id]);
        return getuniqueid($this->db->dbh,'MtnNo','transfersheader',$_SESSION['centerid']);
    }

    //get books
    public function GetBooks()
    {
        $this->db->query('SELECT ID,UCASE(Title) As Title FROM books WHERE Deleted = 0 ORDER BY Title ASC');
        return $this->db->resultset();
    }
   
    //get mtns
    public function GetMtns()
    {
        $this->db->query('SELECT ID,UCASE(MtnNo) AS Mtn 
                          FROM transfersheader 
                          WHERE (Deleted=0) AND (ToCenter =:cid)
                          HAVING NOT fn_checktransferreceived(ID);');
        $this->db->bind(':cid',$_SESSION['centerid']);
        return $this->db->resultset();
    }

    //get book price
    public function GetPrice($book,$date)
    {
       $this->db->query('SELECT fn_getprice(:book,:rdate) As Price');
       $this->db->bind(':book',(int)$book);
       $this->db->bind(':rdate',date('Y-m-d',strtotime($date)));
       return $this->db->getvalue();
    }

    //create new receipt
    public function CreateUpdateReceipt($data)
    {
        try {

            $this->db->dbh->beginTransaction();
            if(!$data['isedit']){
                $this->db->query('INSERT INTO receiptsheader (ReceiptDate,ReceiptType,TransferId,GrnNo,CenterId) 
                              VALUES(:rdate,:rtype,:mtn,:grn,:cid)');
                $this->db->bind(':rdate',$data['date']);
                $this->db->bind(':rtype',$data['type'] === 'grn' ? 1 : 2);
                $this->db->bind(':mtn',!empty($data['mtn']) ? $data['mtn'] : null);
                $this->db->bind(':grn',$data['reference']);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }else{
                $this->db->query('UPDATE receiptsheader SET ReceiptDate=:rdate 
                                 WHERE (ID = :id)');
                $this->db->bind(':rdate',$data['date']);
                $this->db->bind(':id',$data['id']);
                $this->db->execute();
            }
            
            $id = !$data['isedit'] ? $this->db->dbh->lastInsertId() : $data['id'];

            if($data['isedit']) :
                $this->db->query('DELETE FROM receiptsdetails WHERE (HeaderID=:id)');
                $this->db->bind(':id',$id);
                $this->db->execute();

                $this->db->query('DELETE FROM stockmovements WHERE (TransactionType = 2) AND (TransactionId = :id)');
                $this->db->bind(':id',$id);
                $this->db->execute();
            endif;

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $this->db->query('INSERT INTO receiptsdetails(HeaderID,BookId,Qty) VALUES(:hid,:bid,:qty)');
                $this->db->bind(':hid',$id);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty', !empty($data['qtys'][$i]) ? $data['qtys'][$i] : 0);
                $this->db->execute();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                          TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['date']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':ref',$data['reference']); 
                $this->db->bind(':ttype',2);
                $this->db->bind(':tid',$id);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
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

    public function GetReceiptHeader($id)
    {
        $this->db->query('SELECT * FROM receiptsheader WHERE ID = :id');
        $this->db->bind(':id',(int)$id);
        return $this->db->single();
    }

    public function GetReceiptDetails($id)
    {
        $this->db->query('SELECT * FROM vw_receipts_details WHERE HeaderID = :id');
        $this->db->bind(':id',(int)$id);
        return $this->db->resultset();
    }

    public function GetCenters()
    {
        $this->db->query('SELECT ID,
                                 UCASE(CenterName) AS CenterName 
                          FROM   centers 
                          WHERE  Deleted = 0 AND ID <> :id
                          ORDER BY CenterName');
        $this->db->bind(':id',$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function SaveTransfer($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO transfersheader (TransferDate,MtnNo,ToCenter,CenterId) 
                              VALUES(:tdate,:mtn,:tocenter,:cid)');
            $this->db->bind(':tdate',$data['date']);
            $this->db->bind(':mtn',!empty($data['mtn']) ? $data['mtn'] : null);
            $this->db->bind(':tocenter',$data['center']);
            $this->db->bind(':cid',$_SESSION['centerid']);
            $this->db->execute();

            $id = $this->db->dbh->lastInsertId();

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $this->db->query('INSERT INTO transferdetails(HeaderID,BookId,Qty) VALUES(:hid,:bid,:qty)');
                $this->db->bind(':hid',$id);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->execute();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                          TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['date']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':ref',$data['mtn']); 
                $this->db->bind(':ttype',3);
                $this->db->bind(':tid',$id);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
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
    public function UpdateTransfer($data)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE transfersheader SET TransferDate=:tdate,MtnNo=:mtn,ToCenter=:tocenter
                              WHERE (ID=:id)');
            $this->db->bind(':tdate',$data['date']);
            $this->db->bind(':mtn',!empty($data['mtn']) ? $data['mtn'] : null);
            $this->db->bind(':tocenter',$data['center']);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM transferdetails WHERE HeaderID = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM stockmovements WHERE TransactionType = 3 AND TransactionId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            for ($i=0; $i < count($data['booksid']); $i++) { 
                $this->db->query('INSERT INTO transferdetails(HeaderID,BookId,Qty) VALUES(:hid,:bid,:qty)');
                $this->db->bind(':hid',$data['id']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->execute();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                          TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['date']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':ref',$data['mtn']); 
                $this->db->bind(':ttype',3);
                $this->db->bind(':tid',$data['id']);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
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
    public function CreateUpdateTransfer($data)
    {
        if(!$data['isedit']){
            return $this->SaveTransfer($data);
        }elseif($data['isedit'] && $data['allowedit']){
            return $this->UpdateTransfer($data);
        }
    }
    public function GetTransfereHeader($id)
    {
        $this->db->query('SELECT h.ID,
                                 h.TransferDate,
                                 h.MtnNo,
                                 h.ToCenter,
                                 fn_checktransferreceived(h.ID) AS Received,
                                 CenterId
                          FROM   transfersheader h
                          WHERE  h.ID = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    public function GetTransferDetails($id)
    {
        $this->db->query('SELECT 
                            d.BookId,
                            UCASE(b.Title) As Title,
                            d.Qty
                          FROM 
                            transferdetails d 
                                INNER JOIN 
                            books b 
                                ON 
                            d.BookId =b.ID
                          WHERE 
                            d.HeaderID = :id');
        $this->db->bind(':id',$id);
        return $this->db->resultset();
    }
    public function CheckGrnMtnAvailability($type,$mtn,$id)
    {
        $this->db->query('SELECT fn_checkmtngrnavailability(:ttype,:mtn,:id)');
        $this->db->bind(':ttype',$type);
        $this->db->bind(':mtn',$mtn);
        $this->db->bind(':id',$id);
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
    }

    public function DeleteTransfer($id)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE transfersheader SET Deleted=1
                              WHERE (ID=:id)');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('UPDATE stockmovements SET Deleted=1 WHERE TransactionType = 3 AND TransactionId = :id');
            $this->db->bind(':id',$id);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
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

    public function CheckStockAvailability($book,$date,$qty)
    {
        $this->db->query('SELECT fn_getstock(:bid,:rdate,:cid)');
        $this->db->bind(':bid',$book);
        $this->db->bind(':rdate',$date);
        $this->db->bind(':cid',$_SESSION['centerid']);
        if((int)$this->db->getvalue() < $qty){
            return false;
        }else{
            return true;
        }
    }

    public function ValidateReceiptVsTransferDate($date,$mtn)
    {
        $this->db->query('SELECT TransferDate FROM transfersheader WHERE ID = :id');
        $this->db->bind(':id',$mtn);
        $transferdate = date('Y-m-d',strtotime($this->db->getvalue()));
        if($transferdate > $date){
            return false;
        }else{
            return true;
        }
    }

    //get returns
    public function GetReturns()
    {
        $this->db->query('SELECT * FROM vw_returns WHERE CenterId = :cid');
        $this->db->bind(':cid',(int)$_SESSION['centerid']);
        return $this->db->resultset();
    }

    public function SaveReturn($data)
    {
        try {
            //begin transaction
            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO returns_header(ReturnDate,ReturnFrom,Reason,CenterId) 
                              VALUES(:rdate,:returnee,:reason,:cid)');
            $this->db->bind(':rdate',!empty($data['returndate']) ? $data['returndate'] : null);
            $this->db->bind(':returnee',!empty($data['from']) ? strtolower($data['from']) : null);
            $this->db->bind(':reason',!empty($data['reason']) ? strtolower($data['reason']) : null);
            $this->db->bind(':cid',(int)$_SESSION['centerid']);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();

            //loop and save to details table and stock movements table
            for($i = 0; $i < count($data['booksid']); $i++){
                $this->db->query('INSERT INTO returns_details (HeaderId,BookId,Qty) VALUES(:hid,:bid,:qty)');
                $this->db->bind(':hid',$tid);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->execute();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                          TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['returndate']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':ref',null); 
                $this->db->bind(':ttype',5);
                $this->db->bind(':tid',$tid);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }

            return true;
        } catch (\Exception $th) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            throw $th;
            return false;
        }
    }

    //edit return
    public function UpdateReturn($data)
    {
        try {
            //begin transaction
            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE returns_header SET ReturnDate=:rdate,ReturnFrom=:returnee,Reason=:reason 
                              WHERE (ID = :id)');
            $this->db->bind(':rdate',!empty($data['returndate']) ? $data['returndate'] : null);
            $this->db->bind(':returnee',!empty($data['from']) ? strtolower($data['from']) : null);
            $this->db->bind(':reason',!empty($data['reason']) ? strtolower($data['reason']) : null);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();
            
            $this->db->query('DELETE FROM returns_details WHERE HeaderId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('DELETE FROM stockmovements WHERE TransactionType = 5 AND TransactionId = :id');
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            //loop and save to details table and stock movements table
            for($i = 0; $i < count($data['booksid']); $i++){
                $this->db->query('INSERT INTO returns_details (HeaderId,BookId,Qty) VALUES(:hid,:bid,:qty)');
                $this->db->bind(':hid',$data['id']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->execute();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,Reference,
                                          TransactionType,TransactionId,CenterId) 
                              VALUES(:tdate,:bid,:qty,:ref,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['returndate']);
                $this->db->bind(':bid',$data['booksid'][$i]);
                $this->db->bind(':qty',$data['qtys'][$i]);
                $this->db->bind(':ref',null); 
                $this->db->bind(':ttype',5);
                $this->db->bind(':tid',$data['id']);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }

            return true;
        } catch (\Exception $th) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            throw $th;
            return false;
        }
    }

    //create and update returns
    public function CreateUpdateReturn($data)
    {
        if(!$data['isedit']){
            return $this->SaveReturn($data);
        }else{
            return $this->UpdateReturn($data);
        }
    }

    //return header fetch
    public function GetReturnHeader($id)
    {
        $this->db->query('SELECT * FROM returns_header WHERE (ID = :id)');
        $this->db->bind(':id',(int)$id);
        return $this->db->single();
    }

    //return details fetch
    public function GetReturnDetails($id)
    {
        $this->db->query('SELECT * FROM vw_return_details WHERE (HeaderId = :id)');
        $this->db->bind(':id',(int)$id);
        return $this->db->resultset();
    }

    //delete return'
    public function DeleteReturn($id)
    {
        try {
            //begin transaction
            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE returns_header SET Deleted = 1 
                              WHERE (ID = :id)');
            $this->db->bind(':id',(int)$id);
            $this->db->execute();
            

            $this->db->query('UPDATE stockmovements SET Deleted = 1 WHERE TransactionType = 5 AND TransactionId = :id');
            $this->db->bind(':id',(int)$id);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
            }

            return true;
        } catch (\Exception $th) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            throw $th;
            return false;
        }
    }
}