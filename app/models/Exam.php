<?php
class Exam
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function GetCenterName($id)
    {
        $this->db->query('SELECT UCASE(CenterName) As CenterName FROM centers WHERE ID = :id');
        $this->db->bind(':id',(int)$id);
        return $this->db->getvalue();
    }

    public function GetExams()
    {
        $this->db->query('SELECT * FROM vw_exams');
        return $this->db->resultSet();
    }

    public function GetCourses()
    {
        $this->db->query('SELECT ID,UCASE(CourseName) AS CourseName 
                          FROM courses
                          WHERE (Deleted = 0) AND (Active = 1)
                          ORDER BY CourseName');
        return $this->db->resultSet();
    }

    public function GetBooks($id)
    {
        $this->db->query('SELECT ID,UCASE(Title) AS BookName 
                          FROM books 
                          WHERE (CourseId = :cid) AND (Active = 1) AND (Deleted =0)
                          ORDER BY BookName');
        $this->db->bind(':cid',intval($id));
        return $this->db->resultset();
    }

    public function CheckExamName($name,$id)
    {
        $this->db->query('SELECT COUNT(*) FROM exams WHERE (ExamName = :ename) AND (ID <> :id) AND (Deleted = 0)');
        $this->db->bind(':ename',strtolower($name));
        $this->db->bind(':id',intval($id));
        if(intval($this->db->getvalue()) > 0) {
            return false;
        }else{
            return true;
        }
    }

    public function CreateUpdate($data)
    {
        if(!$data['isedit']){
            $this->db->query('INSERT INTO exams (ExamName,CourseId,BookId) 
                              VALUES(:ename,:course,:bookid)');
        }else{
            $this->db->query('UPDATE exams SET ExamName=:ename,CourseId=:course,BookId=:bookid
                              WHERE  (ID = :id)');
        }
        $this->db->bind(':ename',$data['examname']);
        $this->db->bind(':course',$data['course']);
        $this->db->bind(':bookid',$data['bookid']);
        if($data['isedit']){
            $this->db->bind(':id',$data['id']);
        }
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function GetExam($id)
    {
        $this->db->query('SELECT * FROM exams WHERE ID = :id');
        $this->db->bind(':id',trim($id));
        return $this->db->single();
    }

    public function Delete($id)
    {
        $this->db->query('UPDATE exams SET Deleted = 1 WHERE ID = :id');
        $this->db->bind(':id',trim($id));
        if(!$this->db->execute()){
            return false;
        }else{
            return true;
        }
    }

    public function GetGroups()
    {
        $this->db->query('SELECT ID, UCASE(GroupName ) AS GroupName
                          FROM groups 
                          WHERE (Active = 1) AND (Deleted = 0) ORDER BY GroupName');
        return $this->db->resultSet();
    }

    public function GetCategories()
    {
        $this->db->query('SELECT ID, UCASE(Category ) AS Category
                          FROM point_categories');
        return $this->db->resultSet();
    }

    public function GetStudentsByGroup($id,$type)
    {
        if($type === 'fromgroup'){
            $this->db->query('SELECT ID,StudentName 
                              FROM vw_membersbygroup
                              WHERE GroupId = :gid
                              ORDER BY StudentName');
            $this->db->bind(':gid',$id);
        }
        if($type === 'formarking'){
            $this->db->query('SELECT e.StudentId As ID,
                                     UCASE(s.StudentName) As StudentName
                              FROM exam_marking_details e join students s on e.StudentId = s.ID
                              WHERE HeaderId = :hid
                              ORDER BY StudentName');
            $this->db->bind(':hid',$id);
        }
        return $this->db->resultSet();
    }

    public function CheckExamSubmission($group,$exam)
    {
        $this->db->query('SELECT COUNT(*) 
                          FROM exam_marking_header
                          WHERE (GroupId = :group) AND (ExamId = :exam)');
        $this->db->bind(':group',$group);
        $this->db->bind(':exam',$exam);
        if(intval($this->db->getvalue()) > 0){
            return false;
        }else{
            return true;
        }
    }

    public function CreateReceiptFromGroup($data)
    {
        try {
            $this->db->dbh->beginTransaction();

            $this->db->query('INSERT INTO exam_marking_header (FromCenter,ExamId,GroupId,ReceiptFromGroupDate
                                                               ,SubmitMarkingDate,ExamStatus) 
                              VALUES(:fcenter,:eid,:gid,:rdate,:sdate,:estatus)');
            $this->db->bind(':fcenter',$_SESSION['centerid']);
            $this->db->bind(':eid',!empty($data['exam']) ? $data['exam'] : null);
            $this->db->bind(':gid',!empty($data['group']) ? $data['group'] : null);
            $this->db->bind(':rdate',!empty($data['receiptdate']) ? $data['receiptdate'] : null);
            $this->db->bind(':sdate',!empty($data['submitdate']) ? $data['submitdate'] : null);
            $this->db->bind(':estatus',1);
            $this->db->execute();
            $tid = $this->db->dbh->lastInsertId();
            
            $this->db->query('INSERT INTO exam_marking_remarks (HeaderId,ExamStatusId,Remarks) 
                              VALUES(:hid,:eid,:remark)');
            $this->db->bind(':hid',$tid);
            $this->db->bind(':eid',1);
            $this->db->bind(':remark',!empty($data['remarks']) ? strtolower($data['remarks']) : null);
            $this->db->execute();

            for($i = 0; $i < count($data['studentsid']); $i++){
                $this->db->query('INSERT INTO exam_marking_details (HeaderId,StudentId) VALUES(:hid,:student)');
                $this->db->bind(':hid',$tid);
                $this->db->bind(':student',$data['studentsid'][$i]);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            throw $e;
            return false;
        }
    }

    public function GetCentersByStatus($id)
    {
        $this->db->query('SELECT 
                            DISTINCT e.FromCenter AS ID,
                            UCASE(c.CenterName) As CenterName
                          FROM exam_marking_header e join centers c on e.FromCenter = c.ID
                          WHERE e.ExamStatus = :id');
        $this->db->bind(':id', $id);
        return $this->db->resultSet();
    }

    public function GetSelectOptions($type,$value,$status)
    {
        // $sql= '';
        if($type === 'group'){
            $sql = 'SELECT 
                        DISTINCT e.GroupId As ID,
                        UCASE(g.GroupName) AS CriteriaName
                    FROM exam_marking_header e join groups g On e.GroupId = g.ID
                    WHERE  e.FromCenter = :val AND (e.ExamStatus = :stat)';
        }
        if($type === 'exam'){
            $sql = 'SELECT 
                        DISTINCT e.ExamId As ID,
                        UCASE(x.ExamName) AS CriteriaName
                    FROM exam_marking_header e join exams x On e.ExamId = x.ID
                    WHERE  e.GroupId = :val AND (e.ExamStatus = :stat)';
        }

        $this->db->query($sql);
        $this->db->bind(':val',$value);
        $this->db->bind(':stat',$status);
        return $this->db->resultSet();
    }

    public function GetId($data)
    {
        $fields = [];
        $this->db->query('SELECT ID FROM exam_marking_header
                          WHERE (FromCenter = :center) AND (ExamId = :eid) AND (GroupId = :gid)');
        $this->db->bind(':center', $data['centerid']);
        $this->db->bind(':eid', $data['examid']);
        $this->db->bind(':gid', $data['groupid']);
        $value = $this->db->getvalue();
        if(!$value){
            return false;
        }else{
           array_push($fields,$value);
           $this->db->query('SELECT UCASE(Remarks) AS Remarks FROM exam_marking_remarks WHERE (HeaderId = :hid) AND (ExamStatusId = :stat)');
           $this->db->bind(':hid',$value);
           $this->db->bind(':stat',$data['status']);
           array_push($fields,$this->db->getvalue());
           return $fields;
        }
    }

    public function CreateReceiptMarking($data)
    {
        try {
            $this->db->dbh->beginTransaction();
            $this->db->query('UPDATE exam_marking_header SET ReceiptForMarkingDate = :edate,ExamStatus =:stat 
                              WHERE (ID = :id)');
            $this->db->bind(':edate',!empty($data['receiptdate']) ? $data['receiptdate'] : null);
            $this->db->bind(':stat',2);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('INSERT INTO exam_marking_remarks (HeaderId,ExamStatusId,Remarks) 
                              VALUES(:hid,:stat,:remark)');
            $this->db->bind(':hid',$data['id']);
            $this->db->bind(':stat',2);
            $this->db->bind(':remark',!empty($data['markingremarks']) ? strtolower($data['markingremarks']) : null);
            $this->db->execute();

            $this->db->query('DELETE FROM exam_marking_details WHERE HeaderId = :hid');
            $this->db->bind(':hid',$data['id']);
            $this->db->execute();

            for($i = 0; $i < count($data['studentsid']); $i++){
                $this->db->query('INSERT INTO exam_marking_details (HeaderId,StudentId,Marks) 
                                  VALUES(:hid,:student,:marks)');
                $this->db->bind(':hid',$data['id']);
                $this->db->bind(':student',$data['studentsid'][$i]);
                $this->db->bind(':marks', !empty($data['marks']) ? $data['marks'][$i] : 0);
                $this->db->execute();
            }

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }


        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            throw $e;
            return false;
        }
    }

    public function GetStudentsMarks($id)
    {
        $this->db->query('SELECT e.StudentId As ID,
                                 UCASE(s.StudentName) As StudentName,
                                 IFNULL(e.Marks,0) As Marks
                          FROM   exam_marking_details e join students s on e.StudentId = s.ID
                          WHERE  HeaderId = :hid
                          ORDER BY StudentName');
        $this->db->bind(':hid',$id);
        return $this->db->resultset();
    }

    public function CreateReceiptPostMarking($data)
    {
        try {
            $this->db->dbh->beginTransaction();
            $this->db->query('UPDATE exam_marking_header SET ReceiptPostMarkingDate = :edate,ExamStatus =:stat 
                              WHERE (ID = :id)');
            $this->db->bind(':edate',!empty($data['receiptdate']) ? $data['receiptdate'] : null);
            $this->db->bind(':stat',3);
            $this->db->bind(':id',$data['id']);
            $this->db->execute();

            $this->db->query('INSERT INTO exam_marking_remarks (HeaderId,ExamStatusId,Remarks) 
                              VALUES(:hid,:stat,:remark)');
            $this->db->bind(':hid',$data['id']);
            $this->db->bind(':stat',3);
            $this->db->bind(':remark',!empty($data['receiptremarks']) ? strtolower($data['receiptremarks']) : null);
            $this->db->execute();

            if(!$this->db->dbh->commit()){
                return false;
            }else{
                return true;
            }

        } catch (\Exception $e) {
            if($this->db->dbh->inTransaction()){
                $this->db->dbh->rollBack();
            }
            throw $e;
            return false;
        }
    }

    public function GetPoints()
    {
        $this->db->query('SELECT * FROM vw_exampoints');
        return $this->db->resultset();
    }

    public function CheckPointsEntered($course,$book,$cat,$group,$id)
    {
        $this->db->query('SELECT COUNT(*) FROM points_header
                          WHERE (CourseId = :course) AND (GroupId = :group) AND (BookId = :book)
                                AND (CategoryId = :cat) AND (ID <> :id) AND (Deleted =0)');
        $this->db->bind(':course',$course);
        $this->db->bind(':group',$group);
        $this->db->bind(':book',$book);
        $this->db->bind(':cat',$cat);
        $this->db->bind(':id',$id);
        if((int)$this->db->getvalue() > 0){
            return false;
        }else{
            return true;
        }
    }
}