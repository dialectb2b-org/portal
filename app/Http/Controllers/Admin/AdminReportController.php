<?php

namespace App\Http\Controllers\Admin;

use App\Exports\EnquiriesExport;
use App\Exports\SalesReportExport;
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
        
        // $dateRangeInput = $request->daterangesales;
        // list($startDate, $endDate) = explode(" - ", $dateRangeInput);
        $startDate = $request->input('start_date_sales');
        $endDate = $request->input('end_date_sales');
        $dateRangeInput = $startDate . ' - ' . $endDate;
       
        $company_id = auth()->user()->company_id;
        $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
        $enquiries = DB::table('enquiries')
                        ->leftJoin('enquiry_relations', 'enquiry_relations.enquiry_id', '=', 'enquiries.id')
                        ->leftJoin('companies', 'companies.id', '=', 'enquiries.company_id')
                        ->leftJoin('sub_categories', 'sub_categories.id', '=', 'enquiries.sub_category_id')
                        ->where('enquiry_relations.to_id', $sales->id)
                        //->whereBetween('enquiries.created_at', [$startDate, $endDate])
                        ->select(
                            'enquiries.created_at',
                            'enquiries.company_id',
                            'companies.name AS company_name',
                            'companies.is_overlap',
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
       
        $company_id = auth()->user()->company_id;
        $sales = CompanyUser::where('company_id',$company_id)->where('role',3)->first();
        $enquiries = DB::table('enquiries')
                        ->leftJoin('enquiry_relations', 'enquiry_relations.enquiry_id', '=', 'enquiries.id')
                        ->leftJoin('companies', 'companies.id', '=', 'enquiries.company_id')
                        ->leftJoin('sub_categories', 'sub_categories.id', '=', 'enquiries.sub_category_id')
                        ->where('enquiry_relations.to_id', $sales->id)
                        //->whereBetween('enquiries.created_at', [$startDate, $endDate])
                        ->select(
                            'enquiries.created_at',
                            'enquiries.company_id',
                            'companies.name AS company_name',
                            'companies.is_overlap',
                            'enquiries.sub_category_id',
                            'sub_categories.name AS category_name',
                            'enquiries.is_limited',
                            'enquiries.is_completed',
                            'enquiry_relations.is_replied',
                            'enquiries.expired_at'
                        )
                        ->get()->toArray();

        view()->share('enquiries',$enquiries);
        // $pdf = PDF::loadView('admin.reports.sales-pdf', $enquiries)->setPaper('a3', 'landscape');
        //return $pdf->download('sales.pdf');
        
        // $pdfContent = $pdf->output(); 
        $excelContent = Excel::raw(new SalesReportExport($enquiries), \Maatwebsite\Excel\Excel::XLSX);

        // Send Email with PDF Attachment
        $toEmail = auth()->user()->email;
        $subject = 'Sales Activity Report Delivery';

        $details = [
            'subject' => 'Sales Activity Report Delivery',
            'salutation' => '<p style="text-align: left; font-weight: bold;">Dear '.auth()->user()->name ?? ',</p>',
            'introduction' => "<p>We are pleased to provide you with the attached Sales Activity report as requested. Please review the document at your earliest convenience and let us know if any further details or clarifications are required.</p>",
            'body' => "<p>Our team is available to assist with any additional information you may need. For immediate support, please connect with our customer care team via the chat box on Dialectb2b.com.</p>",
            'closing' => "<p>Thank you for your continued partnership. We look forward to supporting your goals.</p>",
            'otp' => null,
            'link' => null,
            'link_text' => null,
            "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
        ];
        
        // Render the email content using a view
        $htmlBody = view('email.common', compact('details'))->render();
    
        // Send the email with the PDF attachment
        Mail::send([], [], function ($mail) use ($toEmail, $subject, $htmlBody, $excelContent) {
            $mail->to($toEmail)
                ->subject($subject)
                ->html($htmlBody)
                ->attachData($excelContent, 'sales-report.xlsx', [
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
        });
        //->attachData($pdfContent, 'sales-report-pdf.pdf');
        // $message = 'Please find the attached PDF file.';
        
        // Mail::send([], [], function ($mail) use ($toEmail, $subject, $message, $pdfContent) {
        //     $mail->to($toEmail)
        //             ->subject($subject)
        //             ->html($message)
        //             ->attachData($pdfContent, 'sales-report-pdf.pdf');
        // });
        
        // return redirect()->route('admin.dashboard');
        return view('admin.reports.sales', compact('enquiries','dateRangeInput','toEmail'));
                    
    } 

    public function procurement (Request $request){
        // $dateRangeInput = $request->daterangeprocurement;
        // list($startDate, $endDate) = explode(" - ", $dateRangeInput);
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $dateRangeInput = $startDate . ' - ' . $endDate;
        $company_id = auth()->user()->company_id;

        $enquiries = Enquiry::with('sender','sub_category','all_replies','action_replies')
                ->where('enquiries.company_id',$company_id)
                        //->whereBetween('enquiries.created_at', [$startDate, $endDate]) 
                        ->get();
      
        return view('admin.reports.procurement', compact('enquiries','dateRangeInput'));
    }

    public function procurementPdf (Request $request){
        $dateRangeInput = $request->daterangeprocurement;
        list($startDate, $endDate) = explode(" - ", $dateRangeInput);
        $company_id = auth()->user()->company_id;

        $enquiry = Enquiry::with('sender','sub_category','all_replies','action_replies','sender.company','all_replies.sender','all_replies.sender.company')
                ->where('enquiries.company_id',$company_id)
                //->whereBetween('enquiries.created_at', [$startDate, $endDate]) 
                        ->get()->toArray();
        //return view('admin.reports.procurement-pdf', compact('enquiries','dateRangeInput'));
        $enquiries = Enquiry::with('sender','sub_category','all_replies','action_replies')
            ->where('enquiries.company_id',$company_id)
                        //->whereBetween('enquiries.created_at', [$startDate, $endDate]) 
                        ->get();

        view()->share('enquiries',$enquiry);
        // $pdf = PDF::loadView('admin.reports.procurement-pdf', $enquiry)->setPaper('a1', 'landscape');
        //return $pdf->download('procurement.pdf');
        
        // $pdfContent = $pdf->output(); 
        $excelContent = Excel::raw(new EnquiriesExport($enquiry), \Maatwebsite\Excel\Excel::XLSX);

        // Send Email with PDF Attachment
        $toEmail = auth()->user()->email;
        $subject = 'Procurement Activity Report Delivery';

        $details = [
            'subject' => 'Procurement Activity Report Delivery',
            'salutation' => '<p style="text-align: left; font-weight: bold;">Dear '.auth()->user()->name ?? ',</p>',
            'introduction' => "<p>We are pleased to provide you with the attached Procurement Activity report as requested. Please review the document at your earliest convenience and let us know if any further details or clarifications are required.</p>",
            'body' => "<p>Our team is available to assist with any additional information you may need. For immediate support, please connect with our customer care team via the chat box on Dialectb2b.com.</p>",
            'closing' => "<p>Thank you for your continued partnership. We look forward to supporting your goals.</p>",
            'otp' => null,
            'link' => null,
            'link_text' => null,
            "closing_salutation" => "<p style='font-weight: bold;'>Best Regards,<br>Team Dialectb2b.com</p>"
        ];
        
        // Render the email content using a view
        $htmlBody = view('email.common', compact('details'))->render();
    
        // Send the email with the PDF attachment
        Mail::send([], [], function ($mail) use ($toEmail, $subject, $htmlBody, $excelContent) {
            $mail->to($toEmail)
                ->subject($subject)
                ->html($htmlBody)
                ->attachData($excelContent, 'procurement-report.xlsx', [
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                ]);
        });
        // ->attachData($pdfContent, 'procurement-report-pdf.pdf');
        // $message = 'Please find the attached PDF file.';
        
        // Mail::send([], [], function ($mail) use ($toEmail, $subject, $message, $pdfContent) {
        //     $mail->to($toEmail)
        //             ->subject($subject)
        //             ->html($message)
        //             ->attachData($pdfContent, 'procurement-report-pdf.pdf');
        // });
        
        return view('admin.reports.procurement', compact('enquiries','dateRangeInput','toEmail'));
        // return redirect()->route('admin.dashboard');
        // return back()->with('success', 'Email successfully sent to ' . $toEmail);
    }
    
}