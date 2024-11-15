<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Http\Resources\Sales\{
        ReceivedListResource,
        EnquiryResource
    };
use App\Mail\OTP;
use App\Models\{
        Company,
        CompanyUser,
        Enquiry,
        EnquiryRelation
    };
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\{
        Validator,
        Cache,
        Mail,
        Hash,
        Crypt
    };
use Auth;
use Carbon\Carbon;
use DB;


class SalesRepliedEnquiryController extends Controller
{
    public function __construct() 
    {
      $this->middleware('auth');
    }
    
    public function index(Request $request, $id = null){
        $user = auth()->user();
        
        $query = EnquiryRelation::with('enquiry','enquiry.sub_category','enquiry.sender.company','enquiry.all_faqs','enquiry.my_faqs')->where('to_id',$user->id)
        ->join('enquiries', 'enquiries.id', '=', 'enquiry_relations.enquiry_id'); //->where('is_closed',0)
        $query->whereHas('enquiry', function ($query) use ($request){
            if(!is_null($request->keyword)){
                $query->where('enquiries.reference_no','like','%'.$request->keyword.'%');
                $query->orwhere('enquiries.subject','like','%'.$request->keyword.'%');
            }
            if($request->mode_filter == 'today'){
                $query->whereDate('enquiries.created_at', Carbon::today());
            }
            else if($request->mode_filter == 'yesterday'){
                $query->whereDate('enquiries.created_at', Carbon::yesterday());
            }
            else if($request->mode_filter == 'this_week'){
                $query->whereBetween('enquiries.created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            }
            else if($request->mode_filter == 'last_week'){
                $query->whereBetween('enquiries.created_at',[Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]);
            }
            else if($request->mode_filter == 'this_month'){
                $startOfMonth = now()->startOfMonth(); 
                $endOfMonth = now()->endOfMonth();
            
                $query->whereBetween('enquiries.created_at', [$startOfMonth, $endOfMonth]);
            }
            else if($request->mode_filter == 'last_month'){
                $startOfMonth = now()->subMonth()->startOfMonth();
                $endOfMonth = now()->subMonth()->endOfMonth();
              
                $query->whereBetween('enquiries.created_at', [$startOfMonth, $endOfMonth]);
            }
        });
        $query->orderBy('enquiries.created_at', 'desc');
        $enquiries = $query->replied()->get();
        
        $selected_enquiry = null;
        if ($enquiries->isNotEmpty()) {
            if($id){
               $id = Crypt::decryptString($id);  
               $selected_enquiry = EnquiryRelation::with('enquiry','enquiry.attachments','enquiry.sender','enquiry.sender.company','enquiry.reply')->findOrFail($id);
            }
            else{
                $id = $enquiries->first()->id;
                $selected_enquiry = EnquiryRelation::with('enquiry','enquiry.attachments','enquiry.sender','enquiry.sender.company','enquiry.reply')->findOrFail($id); 
            }
        }
        return view('sales.replied.index',compact('enquiries','selected_enquiry'));
    }

    public function fetchAllEnquiries(Request $request){
      $user = auth()->user();
      $query = EnquiryRelation::with('enquiry','enquiry.sub_category','enquiry.sender.company','enquiry.all_faqs','enquiry.my_faqs')->where('to_id',$user->id)
      ->join('enquiries', 'enquiries.id', '=', 'enquiry_relations.enquiry_id'); //->where('is_closed',0)
          $query->whereHas('enquiry', function ($query) use ($request){
              if(!is_null($request->keyword)){
                  $query->where('enquiries.reference_no','like','%'.$request->keyword.'%');
                  $query->orwhere('enquiries.subject','like','%'.$request->keyword.'%');
              }
              if($request->mode_filter == 'newest_on_top'){
                //   $query->whereDate('enquiries.created_at', Carbon::today());
                  $query->orderBy('enquiries.created_at', 'desc');
              }
              else if($request->mode_filter == 'oldest_on_top'){
                //   $query->whereDate('enquiries.created_at', Carbon::yesterday());
                  $query->orderBy('enquiries.created_at', 'asc');
              }
              else if($request->mode_filter == 'near_expiry'){
                // $query->whereBetween('enquiries.created_at',[Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                $query->orderBy('enquiries.expired_at', 'asc');
              }
          });
        $enquiries = $query->replied()->get();
        return response()->json([
            'status' => true,
            'enquiries' => ReceivedListResource::collection($enquiries),
        ], 200);
  }

    public function fetchEnquiry(Request $request){
        $enquiry = EnquiryRelation::with('enquiry','enquiry.attachments','enquiry.sender','enquiry.sender.company','enquiry.reply')->findOrFail($request->id);
        return response()->json([
            'status' => true,
            'enquiry' => new EnquiryResource($enquiry),
        ], 200);
    }

}

