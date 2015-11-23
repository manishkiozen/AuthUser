<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Redirect;
use Illuminate\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Session;


class AdministratorController extends Controller {

    public function index() {

        if(Session::get('_admin_token')){
            return view('admin.home');
        }

        return view('admin.admin_login');
    }

    public function postLogin(Request $request) {
      
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (\Auth::attempt($credentials)) {
            $name = \Auth::user()->name;
            Session::set('_admin_token', rand());
            //return view('admin.home');
            Redirect::to('admin');
        }
       
        return Redirect::back()->withInput()->withErrors([
            'emails' => 'The credentials you entered did not match our records. Try again?',
        ]);
        
    }

    public function logout() {
        if(Session::get('_admin_token')){
            Session::flush();
        }

        return Redirect::to('admin');
    }

}