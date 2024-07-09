<?php

namespace App\Services;

use App\Models\Enquiry;
use App\Models\EnquiryFaq;
use App\Models\EnquiryReply;
use App\Models\CompanyUser;
use App\Models\ReportedIssue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use DB;

class ProcurementService
{
    
    // Get all team members
    public function getCompanyMembers()
    {
        return CompanyUser::where(['company_id' => auth()->user()->company_id, 'role' => 4])->get();
    }

    // Fetch all enquiries for bid inbox
    public function fetchAllEnquiries(Request $request)
    {
        
        try {
            $user = auth()->user();
            $query = Enquiry::with('all_replies')->verified()->where('from_id', $user->id)->where('is_draft', 0);
            if(!is_null($request->keyword)){
                $query->where('reference_no','like','%'.$request->keyword.'%');
                $query->orwhere('subject','like','%'.$request->keyword.'%');
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
            
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }
            else if($request->mode_filter == 'last_month'){
                $startOfMonth = now()->subMonth()->startOfMonth();
                $endOfMonth = now()->subMonth()->endOfMonth();
            
                $query->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            }

            return $query->notShared()->latest()->get();
        } catch (\Exception $e) {
            throw new \Exception('Error fetching enquiries: ' . $e->getMessage());
        }
    }

    // Fetch enquiry by enquiry id
    public function fetchEnquiry($id)
    {
        try {
            return Enquiry::with('all_replies', 'sender', 'sender.company','open_faqs','closed_faqs','pending_replies')->findOrFail($id);
        } catch (\Exception $e) {
            throw new \Exception('Error fetching enquiry details: ' . $e->getMessage());
        }
    }
    
    // Read Bid Details
    public function readReply (Request $request){
        
        DB::beginTransaction();
        try{
            $reply = EnquiryReply::with('sender','enquiry','relation')->findOrFail($request->reply_id);
            
            if($reply->is_read == 0){
                $reply->update(['is_read' => 1]);
                
                $reply = EnquiryReply::with('sender','enquiry','relation')->findOrFail($request->reply_id);
            }
            
            DB::commit();
            
            return $reply;
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            throw new \Exception('Error fetching enquiry details: ' . $e->getMessage());
        }
    }
    
    
    // Shortlist Bid
    public function shortlist (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'status' => 1
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return $reply;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            throw new \Exception('Error shortlisting bid: ' . $e->getMessage());
        }
    }
    
    // Hold Bid
    public function hold (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'status' => 2,
                'hold_reason' => $request->reason
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return $reply;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            throw new \Exception('Error holding bid: ' . $e->getMessage());
        }
    }


    // Skip FAQ
    public function skipFaq($id)
    {
        try {
            $faq = EnquiryFaq::findOrFail($id);
            $faq->update(['status' => 2]);

            return $faq;
        } catch (\Exception $e) {
            throw new \Exception('Error skipping FAQ question: ' . $e->getMessage());
        }
    }
    
    // Respond to FAQ
    public function answerFaq($id, $answer)
    {
        try {
            $faq = EnquiryFaq::findOrFail($id);
            $faq->update([
                'answer'       => $answer,
                'answered_at'  => now(),
                'status'       => 1
            ]);

            return $faq;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception('Error responding FAQ question: ' . $e->getMessage());
        }
    }
    
    // Approve Participation Interest 
    public function approveInterest (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'participation_approved' => now()
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return $reply;
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            throw new \Exception('Error approving participation interest: ' . $e->getMessage());
        }
    }
    
    // Select Bid
    public function select (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'is_selected' => 1
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return $reply;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            throw new \Exception('Error selecting bid: ' . $e->getMessage());
        }
    }
    
    // Unselect Bid
    public function unselect (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'is_selected' => 0
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return $reply;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            throw new \Exception('Error selecting bid: ' . $e->getMessage());
        }
    }
    
    // Shortlist Bid
    public function proceed (Request $request){
        DB::beginTransaction();
        try{
            EnquiryReply::findOrFail($request->reply_id)->update([
                'status' => 3 // proceed
            ]);

            $reply = EnquiryReply::findOrFail($request->reply_id);
            DB::commit();
            
            return $reply;
            
        } catch (\Exception $e) {
            DB::rollback();
            
            throw new \Exception('Error confirming bid: ' . $e->getMessage());
        }
    }
    
    // Report
    public function report(Request $request){
        DB::beginTransaction();
        try{
            ReportedIssue::create([
                'category' => $request->category,
                'type' => $request->type,
                'enquiry_id' => $request->enquiry_id,
                'question_id' => $request->question_id,
                'reported_by' => auth()->user()->id,
                'reported_at' => now()
            ]);
            DB::commit();
            
            return true;
            
        } catch (\Exception $e) {
            
            DB::rollback();
            
            throw new \Exception('Error reporting : ' . $e->getMessage());
        }
    }
    
    


    
}
