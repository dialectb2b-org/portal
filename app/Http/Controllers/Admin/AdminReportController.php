<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTP;
use App\Models\Company;
use App\Models\CompanyUser;
use App\Models\Country;
use App\Models\Enquiry;
use App\Models\EnquiryRelation;
use DB;
use Auth;
use PDF;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class AdminReportController extends Controller{
    
    public function sales(Request $request){
        
        $dateRangeInput = $request->daterangesales;
        list($startDate, $endDate) = explode(" - ", $dateRangeInput);
       
        $enquiries = DB::table('enquiries')
                        ->leftJoin('enquiry_relations', 'enquiry_relations.enquiry_id', '=', 'enquiries.id')
                        ->leftJoin('companies', 'companies.id', '=', 'enquiries.company_id')
                        ->leftJoin('sub_categories', 'sub_categories.id', '=', 'enquiries.sub_category_id')
                        ->where('enquiry_relations.to_id', '=', 27)
                        //->whereBetween('enquiries.created_at', [$startDate, $endDate])
                        ->select(
                            'enquiries.created_at',
                            'enquiries.company_id',
                            'companies.name AS company_name',
                            'enquiries.sub_category_id',
                            'sub_categories.name AS category_name',
                            'enquiries.is_limited',
                            'enquiries.is_completed',
                            'enquiry_relations.is_replied',
                            'enquiries.expired_at'
                        )
                        ->get();
      
        return view('admin.reports.sales', compact('enquiries','dateRangeInput'));
                    
    } 

    public function salesPdf(Request $request){
        
        $dateRangeInput = $request->daterangesales;
        list($startDate, $endDate) = explode(" - ", $dateRangeInput);
       
        $enquiries = DB::table('enquiries')
                        ->leftJoin('enquiry_relations', 'enquiry_relations.enquiry_id', '=', 'enquiries.id')
                        ->leftJoin('companies', 'companies.id', '=', 'enquiries.company_id')
                        ->leftJoin('sub_categories', 'sub_categories.id', '=', 'enquiries.sub_category_id')
                        ->where('enquiry_relations.to_id', '=', 27)
                        //->whereBetween('enquiries.created_at', [$startDate, $endDate])
                        ->select(
                            'enquiries.created_at',
                            'enquiries.company_id',
                            'companies.name AS company_name',
                            'enquiries.sub_category_id',
                            'sub_categories.name AS category_name',
                            'enquiries.is_limited',
                            'enquiries.is_completed'
                        )
                        ->get()->toArray();

        view()->share('enquiries',$enquiries);
        $pdf = PDF::loadView('admin.reports.sales-pdf', $enquiries)->setPaper('a3', 'landscape');
        //return $pdf->download('sales.pdf');
        
        $pdfContent = $pdf->output(); 

        // Send Email with PDF Attachment
        $toEmail = auth()->user()->email;
        $subject = 'Dialectb2b Procurement Report';
        $message = 'Please find the attached PDF file.';
        
        Mail::send([], [], function ($mail) use ($toEmail, $subject, $message, $pdfContent) {
            $mail->to($toEmail)
                    ->subject($subject)
                    ->html($message)
                    ->attachData($pdfContent, 'sales-report-pdf.pdf');
        });
        
        return redirect()->route('admin.dashboard');
                    
    } 

    public function procurement (Request $request){
        $dateRangeInput = $request->daterangeprocurement;
        list($startDate, $endDate) = explode(" - ", $dateRangeInput);

        $enquiries = Enquiry::with('sender','sub_category','all_replies','action_replies')
                        //->whereBetween('enquiries.created_at', [$startDate, $endDate]) 
                        ->get();
      
        return view('admin.reports.procurement', compact('enquiries','dateRangeInput'));
    }

    public function procurementPdf (Request $request){
        $dateRangeInput = $request->daterangeprocurement;
        list($startDate, $endDate) = explode(" - ", $dateRangeInput);

        $enquiries = Enquiry::with('sender','sub_category','all_replies','action_replies','sender.company','all_replies.sender','all_replies.sender.company')
                        //->whereBetween('enquiries.created_at', [$startDate, $endDate]) 
                        ->get()->toArray();
        //return view('admin.reports.procurement-pdf', compact('enquiries','dateRangeInput'));

        view()->share('enquiries',$enquiries);
        $pdf = PDF::loadView('admin.reports.procurement-pdf', $enquiries)->setPaper('a1', 'landscape');
        //return $pdf->download('procurement.pdf');
        
        $pdfContent = $pdf->output(); 

        // Send Email with PDF Attachment
        $toEmail = auth()->user()->email;
        $subject = 'Dialectb2b Procurement Report';
        $message = 'Please find the attached PDF file.';
        
        Mail::send([], [], function ($mail) use ($toEmail, $subject, $message, $pdfContent) {
            $mail->to($toEmail)
                    ->subject($subject)
                    ->html($message)
                    ->attachData($pdfContent, 'procurement-report-pdf.pdf');
        });
        
        return redirect()->route('admin.dashboard');
    }
    
}