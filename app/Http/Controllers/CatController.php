<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Catlog;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Support\Facades\Redirect;
use Session;
use Response;
use File;
use Config;

class CatController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.catlog-show');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.catlog', array('update'=>false));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $catlogName = $request->only('name', '_token');

        if( $catlogName['name'] != '' ){

            $catlog = new Catlog;
            $imageName = '';

            $position = $catlog->max('position');
            $position++;

            if( $request->file('icon_file') ){

                // uncomment extension=php_fileinfo.dll from php.ini file
                $this->validate($request, [
                    'icon_file' => 'required|image|mimes:png|max:10000',
                ]);

                $imageName = strtotime(date('Y-m-d H:i:s')).'.'.$request->file('icon_file')->getClientOriginalExtension();    
                $path = '/public/images/catalog/';

                $request->file('icon_file')->move(
                    base_path() . $path, $imageName
                );

                $imageName = $path.$imageName;
            }

            $catlog->name = $catlogName['name'];
            $catlog->icon_file = $imageName;
            $catlog->position = $position;
            $catlog->status = '1';
            $catlog->save();

           

            //Session::set('flush_message', 'Catlog add successfully!');
            return Redirect::to('admin/catlog/list')->with('flush_message', 'Catlog add successfully!');
        }

        return Redirect::back()->withInput()->withErrors([
            'error' => 'Please add catlog name.',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $totalRecords = Catlog::where('catlog_id','0')->get()->count();
        return view('admin.catlog-show', array('totalRecords'=>$totalRecords));
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getlist(Request $request)
    {
        // get total rows   
        $totalRecords = Catlog::where('catlog_id','0')->get()->count();
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
            $query = Catlog::query();
            $query->select('id', 'name', 'icon_file', 'created_at')->where('catlog_id', '0');

            // get request from database
            if( $request->has('search') ){
                $req = $request->only('search', 'order', 'start', 'length', 'draw');

                // pre defined filters list
                $filters = array('name','icon_file','created_at');
              
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
                $edit ='<a href="edit/'.$value->id.'mlm'.$unique.'"><i class="glyphicon glyphicon-edit"></i></a>';
                $delete = '<a href="javascript:;" data-id="'.$value->id.$unique.'" data-token="mlm'.$unique.'ppa" class="cateogry-delete"><i class="glyphicon glyphicon-remove"></i></a>'; 

                $img = '';
                if( $value->icon_file ){
                    $img = '<img src="'.Config::get('app.url').$value->icon_file.'" height="30" width="30" />'; 
                }

                $main_data['data'][] = array($value->name, $img, date('d M Y H:i:s', strtotime($value->created_at)), $edit, $delete ); 
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
        $record = Catlog::select( array('id', 'name', 'icon_file') )->where('id', $id)->get();
       
        if( count($record) > 0 ){
            Session::forget('secure_url');
            // set unique token into session
            Session::set('secure_id', $this->getUniqueNo() ); 
            return view('admin.catlog', array('record'=>$record,'update'=>true));
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
        $this->validate($request, [
           'name' => 'required',
        ]);

        $catlogName = $request->only('name', '_old_img');
        $imageName = $catlogName['_old_img'];

        if( $catlogName['name'] != '' ){

            // get original id
            $id = str_replace(Session::get('secure_id'), '', $id);
            $catlog = Catlog::find($id);
        
            if( $request->file('icon_file') ){

                // uncomment extension=php_fileinfo.dll from php.ini file
                $this->validate($request, [
                    'icon_file' => 'required|image|mimes:png|max:10000',
                ]);

                $imageName = strtotime(date('Y-m-d H:i:s')).'.'.$request->file('icon_file')->getClientOriginalExtension();    
                $path = '/public/images/catalog/';

                $request->file('icon_file')->move(
                    base_path() . $path, $imageName
                );

                // set new image 
                $imageName = $path.$imageName;

                // remove old file
                $filename =  base_path().$catlogName['_old_img'];
                if (File::exists($filename)) {
                    File::delete($filename);
                } 

            }
            
            $catlog->name = $catlogName['name'];
            $catlog->icon_file = $imageName;
            $catlog->save();         

            // delete data from session variable
            Session::forget('secure_id');

            return Redirect::to('admin/catlog/list')->with('flush_message', 'Catlog update successfully!');
        }

        return Redirect::back()->withInput()->withErrors([
           'error' => 'Please add catlog name.',
        ]);
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
           
            $catlog = Catlog::find($id);
            $record = Catlog::select('icon_file')->where('id', $id)->get();


            // delete record
            if( $catlog->delete() ){

                // remove old file
                $filename =  base_path().$record[0]->icon_file;
                if (File::exists($filename)) {
                    File::delete($filename);
                } 

                $this->deletChildCatlog( $id );

                return Redirect::to('admin/catlog/list')->with('flush_message', 'Catlog deleted successfully!');
            }

            return Redirect::to('admin/catlog/list')->with('flush_message', 'Catlog could not deleted!');  
        }
        catch(Exception $e){
            return Redirect::to('admin/catlog/list')->with('flush_message', 'There is an error in application');  
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

    /**
     * Remove the specified resource from storage.
     * @param integer parent id
     * @return boolean value
     */
    private  function deletChildCatlog( $id )
    {
        $flag = false;

        $record = Catlog::select('id', 'icon_file')->where('catlog_id', $id)->get();

        if( count( $record ) > 0 ){
            foreach( $record as $key=>$value){

                $catlog = Catlog::find($value->id);
                // delete record
                if( $catlog->delete() ){

                    $flag = true;

                    // remove old file
                    $filename =  base_path().$value->icon_file;
                    if (File::exists($filename)) {
                        File::delete($filename);
                    } 
                }
            }
        }        
            
        return $flag;
    }
}
