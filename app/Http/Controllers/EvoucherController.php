<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Evoucher;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Redirect;
use Session;
use Response;
use Config;

class EvoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalRecords = Evoucher::where('status','1')->get()->count();
        return view('admin.evoucher-show-fresh', array('totalRecords'=>$totalRecords));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.evoucher-add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate voucher code form
        $this->validate($request, [
            'no_of_vouchers' => 'required|min:1|numeric',
            'price' => 'required|min:1|numeric'
        ]);

        // get no of voucher created
        $no_of_voucher = $request->input('no_of_vouchers');

        // create no of voucher code
        while( $no_of_voucher ){

            // create voucher table object
            $voucher = new Evoucher;

            // get random voucher code
            $voucher_code = $this->getDyanamicVoucher(8);
            
            $voucher->voucher_code = $voucher_code;
            $voucher->price = $request->input('price');
            $voucher->created_by = '1';
            $voucher->status = '1';
            $voucher->created_at = date('Y-m-d H:i:s');
           
            $voucher->save(); 

            $no_of_voucher--;   
        }
        
        return Redirect::to('admin/evoucher')->with('flush_message', 'E-voucher add successfully!');
    }

    /**
     * Display fresh e-vouchers
     *
     * @return \Illuminate\Http\Response json
     */
    public function show(Request $request)
    {
        // get total rows   
        $totalRecords = Evoucher::where('status','1')->get()->count();
        $main_data = array();
        
        if( $totalRecords > 0 ){
                   
            // set unique token into session
            $unique = $this->getUniqueNo();
            Session::set('secure_url', $unique );

            $main_data['draw'] = 1;
            $main_data['recordsTotal'] = $totalRecords;
            $main_data['recordsFiltered'] = $totalRecords;
            
            // condition
            $orderby = 'id';
            $dir = 'asc';
            
            // set starting form to lenght record
            $skip = 0;
            $take = 10;

            // query object 
            $query = Evoucher::query();
            $query->select('id', 'voucher_code', 'price', 'created_by', 'created_at')->where('status', '1');

            // get request from database
            if( $request->has('search') ){
                $req = $request->only('search', 'order', 'start', 'length', 'draw');

                // pre defined filters list
                $filters = array('voucher_code','price','created_by');
              
                // serarch records
                if(!empty( $req['search']['value'])){
                    $query->where('name', 'like', '%'.$req['search']['value'].'%');
                }

                // record get according 
                $orderby =  $filters[ $req['order'][0]['column'] ];
                $dir = $req['order'][0]['dir'];

                // serarch records from to len
                $skip = $req['start'];
                $take = $req['length'];

                $main_data['draw'] = $req['draw'];
            }

            // get records
            $records = $query->orderby($orderby, $orderby)->skip($skip)->take($take)->get();
            
            foreach( $records as $key=>$value ){
                $delete = '<a href="javascript:;" data-id="'.$value->id.$unique.'" data-token="mlm'.$unique.'ppa" class="voucher-delete"><i class="glyphicon glyphicon-remove"></i></a>'; 
                $main_data['data'][] = array($value->voucher_code, $value->price, $value->created_by, date('d M Y H:i:s', strtotime($value->created_at)), $delete ); 
            }
        }

        // return response 
        return Response::json($main_data);
    }

    /**
     * Display used e-vouchers
     *
     * @return \Illuminate\Http\Response json
     */
    public function showDeleted()
    {
       $totalRecords = Evoucher::where('status','0')->get()->count();
       return view('admin.evoucher-show-used', array('totalRecords'=>$totalRecords));
    }

    /**
     * Display used e-vouchers
     *
     * @return \Illuminate\Http\Response json
     */
    public function showDeletedList(Request $request)
    {
        // get total rows   
        $totalRecords = Evoucher::where('status','0')->get()->count();
        $main_data = array();
        
        if( $totalRecords > 0 ){
                   
            // set unique token into session
            $unique = $this->getUniqueNo();
            Session::set('secure_url', $unique );

            $main_data['draw'] = 1;
            $main_data['recordsTotal'] = $totalRecords;
            $main_data['recordsFiltered'] = $totalRecords;
            
            // condition
            $orderby = 'id';
            $dir = 'asc';
            
            // set starting form to lenght record
            $skip = 0;
            $take = 10;

            // query object 
            $query = Evoucher::query();
            $query->select('id', 'voucher_code', 'price', 'created_by', 'created_at', 'deleted_by')->where('status', '0');

            // get request from database
            if( $request->has('search') ){
                $req = $request->only('search', 'order', 'start', 'length', 'draw');

                // pre defined filters list
                $filters = array('voucher_code','price','created_by');
              
                // serarch records
                if(!empty( $req['search']['value'])){
                    $query->where('name', 'like', '%'.$req['search']['value'].'%');
                }

                // record get according 
                $orderby =  $filters[ $req['order'][0]['column'] ];
                $dir = $req['order'][0]['dir'];

                // serarch records from to len
                $skip = $req['start'];
                $take = $req['length'];

                $main_data['draw'] = $req['draw'];
            }

            // get records
            $records = $query->orderby($orderby, $orderby)->skip($skip)->take($take)->get();
            
            foreach( $records as $key=>$value ){
                $main_data['data'][] = array($value->voucher_code, $value->price, $value->created_by, $value->deleted_by, date('d M Y H:i:s', strtotime($value->created_at)) ); 
            }
        }

        // return response 
        return Response::json($main_data);
    }

   
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $token)
    {
        try{
            // remove static keyword from token
            $token = str_replace('mlm', '', $token);
            $token = str_replace('ppa', '', $token);

            $id = str_replace($token, '', $id);
           
            $voucher = Evoucher::find($id);
            $voucher->status = '0';
            $voucher->deleted_by = '1'; 
            $voucher->save();
            
            return Redirect::to('admin/evoucher/getdeletedevouchers')->with('flush_message', 'E-voucher deleted!');  
        }
        catch(Exception $e){
            return Redirect::to('admin/evoucher')->with('flush_message', 'There is an error in application');  
        }
    }

    /**
     * get new dyanamic voucher code
     *
     * @param  int $digit
     * @return string 
     */
    private function getDyanamicVoucher($digit)
    {
        // create random no voucher code
        $voucher_code =  str_random($digit);

        // validate voucher code with database
        if( $this->validateVoucher($voucher_code) ){
            return $voucher_code;
        }

        // call self method again and again whenever get unquie voucher code after check database
        $this->getDyanamicVoucher($digit);
    }

    /**
     * validate voucher code with exists into database
     *
     * @param  string  $voucher_code
     * @return boolean value
     */
    private function validateVoucher($voucher_code)
    {
        // get no of rows
        $rows = Evoucher::where('voucher_code', $voucher_code)->get()->count();
        
        if( $rows > 0 ){
            return false;
        }

        return true;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return integer value
     */
    private  function getUniqueNo()
    {
        return md5(rand(0000,9999));
    }
}
