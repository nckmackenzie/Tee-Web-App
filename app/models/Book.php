<?php
class Book
{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    //get books list from database
    public function GetBooks(){
        $this->db->query('CALL sp_bookslist(:tdate)');
        $this->db->bind(':tdate',date('Y-m-d'));
        return $this->db->resultSet();
    }

    //check if book exists
    public function CheckAvailability($id,$code)
    {
        $arr = array();
        array_push($arr,$id);
        array_push($arr,$code);
        if((int)getdbvalue($this->db->dbh,"SELECT fn_checkbookavailability(?,?)",$arr) > 0){
           return false; 
        }else{
            return true;
        }
    }

    public function Save($data)
    {
        try {
            //begin transaction
            $this->db->dbh->beginTransaction();

            if(!$data['isedit']){
                $this->db->query('INSERT INTO books (Title,BookCode,Author,Publisher) VALUES(:title, :code, :author, :publisher)');
                $this->db->bind(':title',$data['name']);
                $this->db->bind(':code',$data['code']);
                $this->db->bind(':author',!empty($data['author']) ? $data['author'] : null);
                $this->db->bind(':publisher',!empty($data['publisher']) ? $data['publisher'] : null);
                $this->db->execute();

                $id = $this->db->dbh->lastInsertId();

                $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,TransactionType,TransactionId,CenterId) 
                                  VALUES(:tdate,:bid,:qty,:ttype,:tid,:cid)');
                $this->db->bind(':tdate',$data['asat']);
                $this->db->bind(':bid',$id);
                $this->db->bind(':qty',$data['openingbal']);
                $this->db->bind(':ttype',1);
                $this->db->bind(':tid',$id);
                $this->db->bind(':cid',$_SESSION['centerid']);
                $this->db->execute();
            }else{
                if($data['allowedit']){
                    $this->db->query('UPDATE stockmovements SET TransactionDate = :tdate,Qty = :qty 
                                      WHERE (BookId = :id) AND (TransactionType = 1)');
                    $this->db->bind(':tdate',$data['asat']);
                    $this->db->bind(':qty',$data['openingbal']);
                    $this->db->bind(':id',$data['id']);
                    $this->db->execute();
                }
                $this->db->query('UPDATE books SET Title = :title, BookCode = :code, Author = :author, Publisher = :publisher, Active = :active 
                                  WHERE (ID=:id)');
                $this->db->bind(':title',$data['name']);
                $this->db->bind(':code',$data['code']);
                $this->db->bind(':author',!empty($data['author']) ? $data['author'] : null);
                $this->db->bind(':publisher',!empty($data['publisher']) ? $data['publisher'] : null);
                $this->db->bind(':active',$data['active']);
                $this->db->bind(':id',$data['id']);
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

    //save or update 
    public function CreateUpdate($data)
    {
        return $this->Save($data);
    }

    //get single book
    public function GetBook($id)
    {
        $this->db->query('SELECT * FROM vw_books WHERE ID = :id');
        $this->db->bind(':id',$id);
        return $this->db->single();
    }

    //get stock of book
    public function GetStock($book)
    {
        $this->db->query('SELECT fn_getstock(:pid,:tdate)');
        $this->db->bind(':pid',$book);
        $this->db->bind(':tdate',date('Y-m-d'));
        return $this->db->getvalue();
    }

    //delete a book
    public function Delete($id)
    {
        try {

            $this->db->dbh->beginTransaction();

            $this->db->query('UPDATE books SET Deleted = 0 WHERE ID = :id');
            $this->db->bind(':id',$id);
            $this->db->execute();

            $this->db->query('UPDATE stockmovements SET Deleted = 0 WHERE BookId = :id');
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
}