<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LoginController extends Controller
{
    public function login(Request $request){

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if (Auth::attempt($credentials)) {
            $user = auth()->user();

            if($user->status != 1){
                return back()->with('message', 'Your account has been deactivated! Request your admin to grant access.');
            }
          
            if($user->company && $user->company->is_superseded == 1){
                return back()->with('message', 'Your account has been superseded! Contact customer care for more info.');
            }

            if($user->role == 1){
                return redirect()->intended('admin/dashboard')
                ->withSuccess('Signed in');
            }  
            elseif($user->role == 2){
                return redirect()->intended('procurement/dashboard')
                ->withSuccess('Signed in');
            }  
            elseif($user->role == 3){
                return redirect()->intended('sales/dashboard')
                ->withSuccess('Signed in');
            }  
            elseif($user->role == 4){
                return redirect()->intended('member/dashboard')
                ->withSuccess('Signed in');
            }  
            else{
                return back();
            }
        }
    
        // Authentication failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
