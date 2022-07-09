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

            $this->db->query('INSERT INTO books (Title,BookCode,Author,Publisher) VALUES(:title, :code, :author, :publisher)');
            $this->db->bind(':title',$data['name']);
            $this->db->bind(':code',$data['code']);
            $this->db->bind(':author',!empty($data['author']) ? $data['author'] : null);
            $this->db->bind(':publisher',!empty($data['publisher']) ? $data['publisher'] : null);
            $this->db->execute();

            $id = $this->db->dbh->lastInsertId();

            $this->db->query('INSERT INTO stockmovements (TransactionDate,BookId,Qty,TransactionType,TransactionId) 
                              VALUES(:tdate,:bid,:qty,:ttype,:tid)');
            $this->db->bind(':tdate',$data['asat']);
            $this->db->bind(':bid',$id);
            $this->db->bind(':qty',$data['openingbal']);
            $this->db->bind(':ttype',1);
            $this->db->bind(':tid',$id);
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

    //save or update 
    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            return $this->Save($data);
        }
    }
}