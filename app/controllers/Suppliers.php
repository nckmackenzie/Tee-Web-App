<?php
class Suppliers extends Controller 
{
    public function __construct()
    {
        if(!isset($_SESSION['userid'])){
            redirect('auth');
            exit();
        }

        if(!$_SESSION['ishead'] || intval($_SESSION['usertypeid']) > 2 ){
            redirect('auth/unauthorized');
            exit();
        }

        $this->suppliermodel = $this->model('Supplier');
    }

    public function index()
    {
        $suppliers = $this->suppliermodel->GetSuppliers();
        $data = [
            'title' => 'Suppliers',
            'has_datatable' => true,
            'suppliers' => $suppliers
        ];
        $this->view('suppliers/index',$data);
        exit();
    }

    public function add()
    {
        $data = [
            'title' => 'Add Supplier',
            'touched' => false,
            'isedit' => false,
            'id' => '',
            'suppliername' => '',
            'contact' => '',
            'email' => '',  
            'contactperson' => '',
            'address' => '',
            'pin' => '',
            'openingbal' => '',
            'asof' => date('Y-m-d'),
            'suppliername_err' => '',
            'contact_err' => '',
            'email_err' => '', 
            'pin_err' => '',
            'asof_err' => '',
        ];
        $this->view('suppliers/add',$data);
        exit();
    }

    public function createupdate()
    {
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $_POST = filter_input_array(INPUT_POST, FILTER_UNSAFE_RAW);
            $data = [
                'title' => converttobool(trim($_POST['isedit'])) ? 'Edit Supplier' : 'Add Supplier',
                'touched' => true,
                'isedit' => converttobool(trim($_POST['isedit'])),
                'id' => trim($_POST['id']),
                'suppliername' => !empty(trim($_POST['suppliername'])) ? trim($_POST['suppliername']) : '',
                'contact' => !empty(trim($_POST['contact'])) ? trim($_POST['contact']) : '',
                'email' => !empty(trim($_POST['email'])) ? trim($_POST['email']) : '',  
                'contactperson' => !empty(trim($_POST['contactperson'])) ? trim($_POST['contactperson']) : '',
                'address' => !empty(trim($_POST['address'])) ? trim($_POST['address']) : '',
                'pin' => !empty(trim($_POST['pin'])) ? trim($_POST['pin']) : '',
                'openingbal' => !empty(trim($_POST['openingbal'])) ? floatval(trim($_POST['openingbal'])) : '',
                'asof' => !empty(trim($_POST['asof'])) ? date('Y-m-d',strtotime(trim($_POST['asof']))) : '',
                'suppliername_err' => '',
                'contact_err' => '',
                'email_err' => '', 
                'pin_err' => '',
                'asof_err' => '',
            ];

            if(empty($data['suppliername'])){
                $data['suppliername_err'] = 'Enter supplier name';
            }else{
                if(!$this->suppliermodel->CheckFieldAvailability('SupplierName',$data['suppliername'],$data['id'])){
                    $data['suppliername_err'] = 'Supplier name exists';
                }
            }

            if(empty($data['contact'])){
                $data['contact_err'] = 'Enter contact';
            }

            if(!empty($data['email']) && !validateemail($data['email'])){
                $data['email_err'] = 'Invalid email address';
            }

            if(!empty($data['pin']) && strlen($data['pin']) !== 11){
                $data['pin_err'] = 'Invalid pin number';
            }

            if(!empty($data['pin']) && !$this->suppliermodel->CheckFieldAvailability('PIN',$data['pin'],$data['id'])){
                $data['pin_err'] = 'PIN number exists';
            }

            if(!empty($data['openingbal']) && empty($data['asof']) || !validatedate($data['asof'])){
                $data['asof_err'] = 'Select a valid date';
            }

            if(!empty($data['suppliername_err']) || !empty($data['pin_err']) || !empty($data['contact_err'])
               || !empty($data['email_err']) || !empty($data['asof_err'])){
                
                $this->view('suppliers/add',$data);
                exit();
            }

            if(!$this->suppliermodel->CreateUpdate($data)){
                flash('supplier_msg',null,'Unable to save! Retry or contact admin',flashclass('alert','danger'));
                redirect('suppliers');
                exit();
            }

            flash('supplier_flash_msg',null,'Saved successfully',flashclass('toast','success'));
            redirect('suppliers');
            exit();

        }else{
            redirect('auth/forbidden');
            exit();
        }
    }
}