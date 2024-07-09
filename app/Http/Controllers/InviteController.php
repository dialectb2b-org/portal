<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Company;
use App\Models\CompanyActivity;
use App\Models\Cart;
use App\Models\Subscription;
use App\Models\Billing;
use App\Models\BillingDetail;
use Http;
use DB;
use Auth;
 use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class InviteController extends Controller
{
    public function __construct() 
    {
        $this->middleware('auth');
    }
    
    public function index(){
        return view('procurement.invite');
    }
    
    public function sendInvite(Request $request){
        
        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            // Add more validation rules as needed
        ];
    
        // Custom error messages
        $messages = [
            'name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            // Add more custom messages as needed
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);
    
        // If validation fails, return with error messages
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // If validation passes, proceed with sending the email
        $details = [
            'name' => $request->name ?? 'User',
            'subject' => 'DialectB2B Team Member Invitation.',
            'body' => "<div><p>Content</p></div></div>",
            'link' => url('member/sign-up'),
        ];

        try {
            \Mail::to($request->email)->send(new \App\Mail\CommonMail($details));
    
            // Email sent successfully
            return redirect()->back()->with('success', 'Invitation email sent successfully!');
        } catch (\Exception $e) {
            // Email sending failed
            return redirect()->back()->with('error', 'Failed to send invitation email. Please try again later.');
        }
    }
    
}