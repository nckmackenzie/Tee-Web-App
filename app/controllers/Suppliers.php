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
            'suppliername_err' => '',
            'contact_err' => '',
            'email_err' => '', 
            'pin_err' => '',
        ];
        $this->view('suppliers/add',$data);
        exit();
    }
}