<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Package;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Redirect;
use Session;
use Response;
use Config;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $totalRecords = Package::where('status','1')->get()->count();
        return view('admin.package-show', array('totalRecords'=>$totalRecords));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.package', array('update'=>false));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if has product validation
        if( $request->has('has_product') ){
            // validate value
            $this->validate($request, [
                'name' => 'required|min:2',
                'price' => 'required|min:1|numeric',
                'has_product'=> 'required'
            ]);
        }
        else{
            // validate value
            $this->validate($request, [
                'name' => 'required|min:2',
                'price' => 'required|min:1|numeric'
            ]);
        }

        $package = new Package;
        $package->name = $request->input('name');
        $package->price = $request->input('price');
        $package->status = '1';
        $package->created_at = date('Y-m-d H:i:s');
        // set has product
        if( $request->has('has_product') )
            $package->has_product = $request->input('has_product');

        $package->save();

        return Redirect::to('admin/package')->with('flush_message', 'Package add successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        // get total rows   
        $totalRecords = Package::where('status','1')->get()->count();
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
            $query = Package::query();
            $query->select('id', 'name', 'price', 'created_at')->where('status', '1');

            // get request from database
            if( $request->has('search') ){
                $req = $request->only('search', 'order', 'start', 'length', 'draw');

                // pre defined filters list
                $filters = array('name','price','created_at');
              
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
                $edit ='<a href="'.Config::get("app.url").'admin/package/edit/'.$value->id.'mlm'.$unique.'"><i class="glyphicon glyphicon-edit"></i></a>';
                $delete = '<a href="javascript:;" data-id="'.$value->id.$unique.'" data-token="mlm'.$unique.'ppa" class="package-delete"><i class="glyphicon glyphicon-remove"></i></a>'; 
                $main_data['data'][] = array($value->name, $value->price, date('d M Y H:i:s', strtotime($value->created_at)), $edit, $delete ); 
            }
        }

        // return response 
        return Response::json($main_data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = str_replace('mlm'.Session::get('secure_url'), '', $id);
        $record = Package::select( array('id', 'name', 'price','has_product') )->where('id', $id)->get();
       
        if( count($record) > 0 ){
            Session::forget('secure_url');
            // set unique token into session
            Session::set('secure_id', $this->getUniqueNo() ); 
            return view('admin.package', array('record'=>$record,'update'=>true));
        }

        return Redirect::back();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // if has product validation
        if( $request->has('has_product') ){
            // validate value
            $this->validate($request, [
                'name' => 'required|min:2',
                'price' => 'required|min:1|numeric',
                'has_product'=> 'required'
            ]);
        }
        else{
            // validate value
            $this->validate($request, [
                'name' => 'required|min:2',
                'price' => 'required|min:1|numeric'
            ]);
        }

        // get original id
        $id = str_replace(Session::get('secure_id'), '', $id);
        $package = Package::find($id);
        $package->name = $request->input('name');
        $package->price = $request->input('price');
        $package->has_product = $request->input('has_product');
        $package->status = '1';
        $package->created_at = date('Y-m-d H:i:s');
       
        $package->save();

        return Redirect::to('admin/package')->with('flush_message', 'Package update successfully!');
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
           
            $package = Package::find($id);
            $package->status = '0';
            $package->save();
            
            return Redirect::to('admin/package')->with('flush_message', 'Package deleted!');  
        }
        catch(Exception $e){
            return Redirect::to('admin/package')->with('flush_message', 'There is an error in application');  
        }
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
