<?php
class Stocks extends Controller
{
    public function __construct()
    {
        if(!is_authenticated($_SESSION['userid'])){
            redirect('auth');
            exit();
        }
        $this->authmodel = $this->model('Auths');
        $this->stockmodel = $this->model('Stock');
    }

    public function receipts()
    {
        checkrights($this->authmodel,'receipts');
        $receipts = $this->stockmodel->GetReceiptsOrTransfers('receipts');
        $data = [
            'title' => 'Receipts',
            'has_datatable' => true,
            'receipts' => $receipts
        ];
        $this->view('stocks/receipts',$data);
        exit();
    }

    public function addgrn()
    {
        checkrights($this->authmodel,'receipts');
        $books = $this->stockmodel->GetBooks();
        $data = [
            'title' => 'Add Receipt',
            'books' => $books,
            'date' => date('Y-m-d'),
            'touched' => false,
            'type' => 'grn',
            'id' => '',
            'isedit' => false,
            'reference' => $this->stockmodel->GetGrnNo(),
            'table' => [],
            'date_err' => '',
            'reference_err' => '',
        ];
        $this->view('stocks/addreceipt',$data);
    }

    public function addinter()
    {
        checkrights($this->authmodel,'receipts');
        $books = $this->stockmodel->GetBooks();
        $mtns = $this->stockmodel->GetMtns();
        $data = [
            'title' => 'Add Receipt',
            'mtns' => $mtns,
            'books' => $books,
            'date' => date('Y-m-d'),
            'touched' => false,
            'type' => 'internal',
            'mtn' => '',
            'id' => '',
            'isedit' => false,
            'reference' => $this->stockmodel->GetGrnNo(),
            'table' => [],
            'date_err' => '',
            'reference_err' => '',
            'mtn_err' => '',
            'qty_err' => 0,
        ];
        $this->view('stocks/addreceiptinter',$data);
    }

    public function getprice()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $_GET = filter_input_array(INPUT_GET,FILTER_UNSAFE_RAW);
            $data = [
                'book' => $_GET['book'],
                'date' => $_GET['rdate']
            ];
            $price = $this->stockmodel->GetPrice($data['book'],$data['date']);
            echo json_encode(floatval($price));
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    public function createupdatereceipt()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $mtns = $this->stockmodel->GetMtns();
            $data = [
                'title' => converttobool($_POST['isedit']) ? 'Edit Receipt' :'Add Receipt',
                'mtns' => $mtns,
                'touched' => true,
                'id' => trim($_POST['id']),
                'isedit' => converttobool($_POST['isedit']),
                'type' => !empty(trim($_POST['receipttype'])) ? trim($_POST['receipttype']) : 'grn',
                'date' => !empty($_POST['date']) ? date('Y-m-d',strtotime($_POST['date'])) : date('Y-m-d'),
                'mtn' => isset($_POST['mtn']) && !empty($_POST['mtn']) ? trim($_POST['mtn']) : '',
                'reference' => !empty($_POST['reference']) ? strtolower(trim($_POST['reference'])) : '',
                'booksid' => $_POST['booksid'],
                'booksname' => $_POST['booksname'],
                'trqtys' => '',
                'qtys' => $_POST['qtys'],
                'table' => [],
                'date_err' => '',
                'mtn_err' => '',
                'reference_err' => '',
                'qty_err' => 0,
            ];

            if($data['type'] === 'internal'){
                $data['trqtys'] = $_POST['trqtys'];
            }

            for ($i=0; $i < count($data['booksid']); $i++) { 
                array_push($data['table'],[
                    'pid' => $data['booksid'][$i],
                    'book' => $data['booksname'][$i],
                    'qty' => $data['qtys'][$i],
                    'trqty' => $data['type'] === 'internal' ? $data['trqtys'][$i] : '',
                ]);
            }

            if($data['type'] === 'internal'){
                foreach($data['table'] as $table){
                    if(intval($table['qty']) > intval($table['trqty'])){
                        $data['qty_err'] ++;
                    }
                }
            }
            
            //validate
            if(empty($data['date'])){
                $data['date_err'] = 'Select receipt date';
            }else{
                if($data['date'] > date('Y-m-d')){
                    $data['date_err'] = 'Invalid date selected';
                }
            }

            if($data['type'] === 'internal' && empty($data['mtn'])){
                $data['mtn_err'] = 'No mtn selected';
            }

            if(!empty($data['date']) && $data['type'] === 'internal'){
                if(!$this->stockmodel->ValidateReceiptVsTransferDate($data['date'],$data['mtn'])){
                    $data['date_err'] = 'Receipt date earlier than transfer date';
                }
            }

            if(empty($data['reference'])){
                $data['reference_err'] = 'Enter GRN No';
            }else{
                if(!$this->stockmodel->CheckGrnMtnAvailability('grn',$data['reference'],$data['id'])){
                    $data['reference_err'] = 'GRN No already exists';
                }
            }

            if(!empty($data['date_err']) || !empty($data['mtn_err']) || !empty($data['reference_err'])
                || !empty($data['qty_err'])){

                if($data['type'] === 'grn') {
                    $this->view('stocks/addreceipt',$data);
                }elseif($data['type'] === 'internal'){
                    $this->view('stocks/addreceiptinter',$data);
                }
                exit();
            }

            if(!$this->stockmodel->CreateUpdateReceipt($data)){
                flash('receipt_msg',null,'Receipt not created. Retry or contact admin',flashclass('alert','danger'));
                redirect('stocks/receipts');
                exit();
            }

            flash('receipt_toast_msg',null,'Receipt saved successfully!.',flashclass('toast','success'));
            redirect('stocks/receipts');
            exit();
        }
    }

    public function receiptedit($id)
    {
        checkrights($this->authmodel,'receipts');
        $header = $this->stockmodel->GetReceiptHeader($id);
        $details = $this->stockmodel->GetReceiptDetails($id);
        $books = $this->stockmodel->GetBooks();
        checkcenter($header->CenterId);
        $data = [
            'title' => 'Edit Receipt',
            'touched' => false,
            'isedit' => true,
            'books' => $books,
            'type' => (int)$header->ReceiptType === 1 ? 'grn' : 'internal',
            'id' => $header->ID,
            'date' => $header->ReceiptDate,
            'reference' => $header->GrnNo,
            'mtn' => $header->ReceiptType === 1 ? NULL : $this->stockmodel->GetMtnNo(true,$header->TransferId),
            'table' => [],
            'date_err' => '',
            'qty_err' =>''
        ];
        foreach($details as $detail) : 
            array_push($data['table'],[
                'pid' => $detail->BookId,
                'book' => $detail->BookTitle,
                'trqty' => $detail->TransferedQty,
                'qty' => $detail->Qty
            ]);
        endforeach;
        $this->view('stocks/editreceipt',$data);
        exit;
    }

    public function transfers()
    {
        checkrights($this->authmodel,'transfers');
        $transfers = $this->stockmodel->GetReceiptsOrTransfers('transfers');
        $data = [
            'title' => 'Transfers',
            'has_datatable' => true,
            'transfers' => $transfers,
        ];
        $this->view('stocks/transfers',$data);
        exit();
    }

    public function addtransfer()
    {
        checkrights($this->authmodel,'transfers');
        $books = $this->stockmodel->GetBooks();
        $centers = $this->stockmodel->GetCenters();
        $data = [
            'title' => 'Add Transfer',
            'centers' => $centers,
            'books' => $books,
            'touched' => false,
            'id' => '',
            'allowedit' => false,
            'isedit' => false,
            'date' => date('Y-m-d'),
            'errors' => [],
            'center' => '',
            'mtn' => $this->stockmodel->GetMtnNo(),
            'table' => [],
            'date_err' => '',
            'center_err' => '',
            'mtn_err' => '',
        ];
        $this->view('stocks/addtransfer',$data);
        exit();
    }

    public function createupdatetransfer()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $books = $this->stockmodel->GetBooks();
            $centers = $this->stockmodel->GetCenters();
            $data = [
                'title' => converttobool($_POST['isedit']) ? 'Edit Transfer' : 'Add Transfer',
                'centers' => $centers,
                'books' => $books,
                'touched' => true,
                'id' => trim($_POST['id']),
                'isedit' => converttobool($_POST['isedit']),
                'allowedit' => converttobool($_POST['allowedit']),
                'errors' => [],
                'date' => !empty($_POST['date']) ? date('Y-m-d',strtotime($_POST['date'])) : '',
                'center' => !empty($_POST['center']) ? trim($_POST['center']) : '',
                'mtn' => trim($_POST['mtn']),
                'table' => [],
                'booksid' => $_POST['booksid'],
                'booksname' => $_POST['booksname'],
                'qtys' => $_POST['qtys'],
                'date_err' => '',
                'center_err' => '',
                'mtn_err' => '',
            ];

            if(count($data['booksid']) == 0){
                $data['error'] = 'No items added for transfer';
            }else{
                for ($i=0; $i < count($data['booksid']); $i++) { 
                    array_push($data['table'],[
                        'pid' => $data['booksid'][$i],
                        'book' => $data['booksname'][$i],
                        'qty' => $data['qtys'][$i],
                    ]);
                    if(!$this->stockmodel->CheckStockAvailability($data['booksid'][$i],$data['date'],$data['qtys'][$i])){
                        array_push($data['errors'],$data['booksname'][$i] . ' has insufficient stock');
                    }
                }
            }

            if(empty($data['date'])){
                $data['date_err'] = 'Select transfer date';
            }else{
                if($data['date'] > date('Y-m-d')){
                    $data['date_err'] = 'Invalid date selected';
                }
            }

            if(empty($data['center'])){
                $data['center_err'] = 'Select center transfering to';
            }

            if(empty($data['mtn'])){
                $data['mtn_err'] = 'Please enter MTN No';
            }else{
                if(!$this->stockmodel->CheckGrnMtnAvailability('mtn',$data['mtn'],$data['id'])){
                    $data['mtn_err'] = 'Mtn No already exists';
                }
            }

            if(!empty($data['date_err']) || !empty($data['center_err']) || !empty($data['mtn_err']) 
               || count($data['errors']) !== 0){
                $this->view('stocks/addtransfer',$data);
                exit();
            }

            if(!$this->stockmodel->CreateUpdateTransfer($data)){
                flash('transfer_msg',null,'Transfer not created. Retry or contact admin',flashclass('alert','danger'));
                redirect('stocks/transfers');
                exit();
            }

            flash('transfer_toast_msg',null,'Transfer created.',flashclass('toast','success'));
            redirect('stocks/transfers');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
    public function transferedit($id)
    {
        checkrights($this->authmodel,'transfers');
        $books = $this->stockmodel->GetBooks();
        $centers = $this->stockmodel->GetCenters();
        $transfereheader = $this->stockmodel->GetTransfereHeader($id);
        checkcenter($transfereheader->CenterId);
        $transferdetails = $this->stockmodel->GetTransferDetails($id);
        $data = [
            'title' => 'Edit Transfer',
            'centers' => $centers,
            'books' => $books,
            'touched' => false,
            'id' => $transfereheader->ID,
            'isedit' => true,
            'allowedit' => !converttobool($transfereheader->Received),
            'date' => $transfereheader->TransferDate,
            'center' => $transfereheader->ToCenter,
            'mtn' => $transfereheader->MtnNo,
            'table' => [],
            'date_err' => '',
            'center_err' => '',
            'mtn_err' => '',
        ];
        
        foreach ($transferdetails as $detail){
            array_push($data['table'],[
                'pid' => $detail->BookId,
                'book' => $detail->Title,
                'qty' => $detail->Qty
            ]);
        }
        $this->view('stocks/addtransfer',$data);
        exit();
    }
    public function deletetransfer()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = trim($_POST['id']);

            if(empty($id)){
                flash('transfer_msg',null,'Unable to get selected transfer',flashclass('alert','danger'));
                redirect('stocks/transfers');
                exit();
            }

            if(!$this->stockmodel->DeleteTransfer($id)){
                flash('transfer_msg',null,'Unable to delete selected transfer',flashclass('alert','danger'));
                redirect('stocks/transfers');
                exit();
            }

            flash('transfer_toast_msg',null,'Transfer deleted',flashclass('toast','success'));
            redirect('stocks/transfers');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
    public function gettransfereditems()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET'){
            $tid = trim($_GET['tid']);
            if(empty($tid)){
                echo json_encode('No data found');
                exit();
            }

            $transfers = $this->stockmodel->GetTransferDetails($tid);
            $output = '';
            foreach($transfers as $transfer){
                $output .='
                <tr>
                    <td class="d-none"><input type="text" name="booksid[]" value="'.$transfer->BookId.'" readonly></td>
                    <td><input type="text" class="table-input" name="booksname[]" value="'.$transfer->Title.'" readonly></td>
                    <td><input type="text" class="table-input" name="trqtys[]" value="'.$transfer->Qty.'" readonly></td>
                    <td><input type="number" class="table-input" name="qtys[]" value="" ></td>
                </tr>
                '; 
            }
            echo json_encode($output);
        }else{
            redirect('auth/forbidden');
            exit();
        }
    }

    //stock returns
    public function returns()
    {
        checkrights($this->authmodel,'returns');
        $data = [
            'title' => 'Returns',
            'has_datatable' => true,
            'returns' => $this->stockmodel->GetReturns()
        ];
        $this->view('stocks/returns', $data);
        exit;
    }

    //add stock returns view
    public function addreturn()
    {
        checkrights($this->authmodel,'returns');
        $data = [
            'title' => 'Add Return',
            'books' => $this->stockmodel->GetBooks(),
            'isedit' => false,
            'touched' => false,
            'id' => '',
            'returndate' => '',
            'from' => '',
            'reason' => '',
            'table' => [],
            'returndate_err' => '',
            'from_err' => '',
            'reason_err' => '',
        ];
        $this->view('stocks/addreturn', $data);
        exit;
    }

    public function createupdatereturn()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => !converttobool($_POST['isedit']) ? 'Add Return' : 'Edit Return',
                'books' => $this->stockmodel->GetBooks(),
                'isedit' => converttobool($_POST['isedit']),
                'touched' => true,
                'id' => trim($_POST['id']),
                'returndate' => !empty(trim($_POST['returndate'])) ? date('Y-m-d',strtotime(trim($_POST['returndate']))) : '',
                'from' => !empty(trim($_POST['from'])) ? trim($_POST['from']) : '',
                'reason' => !empty(trim($_POST['reason'])) ? trim($_POST['reason']) : '',
                'table' => [],
                'booksid' => $_POST['booksid'],
                'booksname' => $_POST['booksname'],
                'qtys' => $_POST['qtys'],
                'returndate_err' => '',
                'from_err' => '',
                'reason_err' => '',
            ];

            if(count($data['booksid']) == 0){
                $data['error'] = 'No items added for transfer';
            }else{
                for ($i=0; $i < count($data['booksid']); $i++) { 
                    array_push($data['table'],[
                        'pid' => $data['booksid'][$i],
                        'book' => $data['booksname'][$i],
                        'qty' => $data['qtys'][$i],
                    ]);
                }
            }

            //validation
            if(empty($data['returndate'])){
                $data['returndate_err'] = 'Select return date';
            }else{
                if(!validatedate($data['returndate'])){
                    $data['returndate_err'] = 'Invalid return date';
                }
            }
            if(empty($data['from'])){
                $data['from_err'] = 'Enter returnee';
            }
            if(empty($data['reason'])){
                $data['reason_err'] = 'Enter reason for returning';
            }

            //errors found
            if(!empty($data['returndate_err']) || !empty($data['from_err']) || !empty($data['reason_err'])){
                $this->view('stocks/addreturn',$data);
                exit;
            }

            if(!$this->stockmodel->CreateUpdateReturn($data)){
                flash('return_msg',null,'Unable to save the return.Retry or contact admin',flashclass('alert','danger'));
                redirect('stocks/returns');
            }

            flash('return_toast_msg',null,'Saved successfully!',flashclass('toast','success'));
            redirect('stocks/returns');
            exit();

        }else {
            redirect('auth/forbidden');
            exit;
        }
    }

    //edit return
    public function returnedit($id)
    {
        checkrights($this->authmodel,'returns');
        $returnheader = $this->stockmodel->GetReturnHeader($id);
        $returndetails = $this->stockmodel->GetReturnDetails($id);
        checkcenter($returnheader->CenterId);
        $data = [
            'title' => 'Edit Return',
            'books' => $this->stockmodel->GetBooks(),
            'isedit' => true,
            'touched' => false,
            'id' => $returnheader->ID,
            'returndate' => $returnheader->ReturnDate,
            'from' => strtoupper($returnheader->ReturnFrom),
            'reason' => strtoupper($returnheader->Reason),
            'table' => [],
            'returndate_err' => '',
            'from_err' => '',
            'reason_err' => '',
        ];
        foreach($returndetails as $returndetail):
            array_push($data['table'],[
                'pid' => $returndetail->BookId,
                'book' => $returndetail->BookTitle,
                'qty' => $returndetail->Qty,
            ]);
        endforeach;
        $this->view('stocks/addreturn', $data);
        exit;
    }

    public function deletereturn()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = trim($_POST['id']);

            if(empty($id)){
                flash('return_msg',null,'Unable to get selected return.Retry or contact admin',flashclass('alert','danger'));
                redirect('stocks/returns');
                exit;
            }

            if(!$this->stockmodel->DeleteReturn($id)){
                flash('return_msg',null,'Unable to delete the return.Retry or contact admin',flashclass('alert','danger'));
                redirect('stocks/returns');
                exit;
            }

            flash('return_toast_msg',null,'Deleted successfully!',flashclass('alert','danger'));
            redirect('stocks/returns');
            exit;

        }else {
            redirect('auth/forbidden');
            exit;
        }
    }
}