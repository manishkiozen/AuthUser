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
        return view('admin.catlog');
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

        $catlogName = $request->only('name');

        if( $catlogName['name'] != '' ){

            $catlog = new Catlog;
            $imageName = '';

            if( $request->file('icon_file') ){

                // uncomment extension=php_fileinfo.dll from php.ini file
                $this->validate($request, [
                    'icon_file' => 'required|image|mimes:png|size:650000',
                ]);

                 $imageName = '/public/images/catalog/'.strtotime(date('Y-m-d H:i:s')).'.'.$request->file('icon_file')->getClientOriginalExtension();

                $request->file('icon_file')->move(
                    base_path() . $imageName
                );
            }

            $catlog->name = $catlogName['name'];
            $catlog->icon_file = $imageName;
            $catlog->save();

           

            //Session::set('flush_message', 'Catlog add successfully!');
            return Redirect::to('admin/catlog/addnew')->with('flush_message', 'Catlog add successfully!');
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
         return view('admin.catlog-show');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getlist()
    {
        $totalRecords = Catlog::get()->count();
        $main_data = array();
        
        if( $totalRecords > 0 ){
            
            $main_data['draw'] = 1;
            $main_data['recordsTotal'] = $totalRecords;
            $main_data['recordsFiltered'] = $totalRecords;

            $records = Catlog::select( array('name', 'icon_file', 'created_at') )->get();

            foreach( $records as $key=>$value ){
               $main_data['data'][] = array($value->name, $value->icon_file, date('ds M Y H:i:s', strtotime($value->created_at)) ); 
            }
        }

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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
