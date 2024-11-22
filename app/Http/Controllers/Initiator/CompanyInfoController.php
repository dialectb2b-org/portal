<?php

namespace App\Http\Controllers\Initiator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Initiator\CompanyInfoRequest;
use App\Http\Requests\Initiator\DocumentUploadRequest;
use App\Http\Requests\Initiator\LogoUploadRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Models\Company;
use App\Models\CompanyDocument;
use App\Models\CompanyLocation;
use App\Models\CompanyUser;
use App\Models\Country;
use App\Models\Region;
use App\Models\Document;
use App\Models\RegistrationToken;
use DB;
use Mail;
use App\Services\SendGridEmailService;
use Carbon\Carbon;

class CompanyInfoController extends Controller
{
    
    protected $sendGridEmailService;

    public function __construct(SendGridEmailService $sendGridEmailService)
    {
        $this->sendGridEmailService = $sendGridEmailService;
    }
    
    public function index(){
        $comp = session('company');
        if(!$comp){
            return redirect('/');
        }

        $countries = Country::where('status',1)->get();
        $regions = Region::where('country_id',$comp->country_id)->get();
        $company = Company::find($comp->id);
        $document = Document::where('country_id',$company->country_id)->first();
        $company_locations = CompanyLocation::where('company_id',$company->id)->pluck('region_id')->toArray();
       
        return view('initiator.company-info',compact('company','regions','document','company_locations','countries'));
    }

    public function store(CompanyInfoRequest $request){
        $comp = session('company');
        if(!$comp){
            return redirect('/');
        }

        $company = Company::find($comp->id);
        $input = $request->validated();
        DB::beginTransaction();
        $docExists = CompanyDocument::where('doc_number',$input['document_no'])->where('company_id','!=',$company->id)->first();
        if($docExists){
            $admin = CompanyUser::where('company_id',$docExists->company_id)->where('role',1)->first();
            $proct = CompanyUser::where('company_id',$docExists->company_id)->where('role',2)->first();
            $sales = CompanyUser::where('company_id',$docExists->company_id)->where('role',3)->first();
            $existcompany = Company::find($docExists->company_id);
            return response()->json([
                'status' => false,
                'type' => 'superseed',
                'admin' => $admin,
                'proct' => $proct,
                'sales' => $sales,
                'company' => $existcompany,
                'data' => $input,
                'message' => 'Document already exists!',
            ], 422);
        }

        try {

            $document = Document::where('country_id',$company->country_id)->first();
            
            $company->update([
                'name' => $input['name'],
                'address' => $input['address'],
                'street' => $input['street'],
                'building' => $input['building'],
                'country_id' => $input['country_id'],
                'pobox' => $input['pobox'],
                'landline' => $input['landline'],
                'fax' => $input['fax'],
                'domain' => $input['domain'],
                'current_plan' => 1
            ]);

            // $parsedDate = Carbon::createFromFormat('d-m-Y', $input['expiry_date']);
            CompanyDocument::updateOrCreate([
                'company_id'   => $company->id,
            ],[
                'doc_type' => $document->id,
                'expiry_date' => $input['expiry_date'],
                'doc_number' => $input['document_no']
            ]);

            $company_locations = CompanyLocation::where('company_id',$company->id)->exists();
            if($company_locations == true){
                CompanyLocation::where('company_id',$company->id)->delete();
            }

            foreach($request->region_id as $key => $region){
                CompanyLocation::create([
                    'company_id' => $company->id,
                    'region_id' => $region
                ]);
            }

            $company = Company::find($comp->id);
            $token = RegistrationToken::where('company_id',$company->id)->first();
            
            // $details = [
            //     'name' =>  $company->name ?? 'User',
            //     'subject' =>'DialectB2B Registration Process.',
            //     'body' => "<div><p>We're excited to tell you that you've successfully updated your company registration document at Dialectb2b.com.<br><p>To proceed with the registration please follow the link below.</p></div></div>",
            //     'link'	=> url('registration/'.$token->token),
            // ];
            
            // \Mail::to($company->email)->send(new \App\Mail\CommonMail($details));

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Success!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function storeSupersede(CompanyInfoRequest $request){
        $comp = session('company');
        if(!$comp){
            return redirect('/');
        }

        $company = Company::find($comp->id);
        $input = $request->validated();
        DB::beginTransaction();
       
        $docExists = CompanyDocument::where('doc_number',$input['document_no'])->where('company_id','!=',$company->id)->first();
        if($docExists){
            $originalCompany = Company::find($docExists->company_id);
            if($originalCompany->is_verified == 1){
                return response()->json([
                    'status' => false,
                    'type' => 'isverifiedcompany',
                    'message' => 'Company already verified!',
                ], 422);
            }
            
        }
        
        
        try {

            $document = Document::where('country_id',$company->country_id)->first();
            
            $company->update([
                'name' => $input['name'],
                'address' => $input['address'],
                'street' => $input['street'] ?? '',
                'building' => $input['building'],
                'country_id' => $input['country_id'],
                'unit' => $input['unit'] ?? null,
                'pobox' => $input['pobox'] ?? null,
                'landline' => $input['landline'],
                'fax' => $input['fax'] ?? null,
                'domain' => $input['domain'] ?? null,
                'current_plan' => 1,
                'is_overlap' => 1
            ]);

            CompanyDocument::updateOrCreate([
                'company_id'   => $company->id,
            ],[
                'doc_type' => $document->id,
                'expiry_date' => $input['expiry_date'],
                'doc_number' => $input['document_no']
            ]);

            $company_locations = CompanyLocation::where('company_id',$company->id)->exists();
            if($company_locations == true){
                CompanyLocation::where('company_id',$company->id)->delete();
            }

            foreach($request->region_id as $key => $region){
                CompanyLocation::create([
                    'company_id' => $company->id,
                    'region_id' => $region
                ]);
            }

            $company = Company::find($comp->id);
            $token = RegistrationToken::where('company_id',$company->id)->first();
            
            // $details = [
            //     'name' =>  $company->name ?? 'User',
            //     'subject' =>'DialectB2B Registration Process.',
            //     'body' => "<div><p>We're excited to tell you that you've successfully updated your company registration document at Dialectb2b.com.<br><p>To proceed with the registration please follow the link below.</p></div></div>",
            //     'link'	=> url('registration/'.$token->token),
            // ];
            
            // \Mail::to($company->email)->send(new \App\Mail\CommonMail($details));

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Success!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadDocument(DocumentUploadRequest $request){
        $comp = session('company');
        if(!$comp){
            return redirect('/');
        }

        DB::beginTransaction();
        try{
            $company = Company::find($comp->id);
            $doc = Document::where('country_id',$company->country_id)->first();
            $folder = 'uploads/'.$company->id;
            if ($request->hasFile('document_file')) {
                $document = $request->file('document_file');
                $originalName = $document->getClientOriginalName();
                $fileName = 'company_document.' . $document->getClientOriginalExtension();
                //$filePath = $document->storeAs($folder, $fileName);
                $filePath = $document->move(public_path($folder), $fileName);
            }
            $document = CompanyDocument::updateOrCreate([
                'company_id'   => $company->id,
            ],[
                'doc_type' => $doc->id,
                'doc_name' => $originalName,
                'doc_file' => $folder.'/'.$fileName,
            ]);
            DB::commit();
             
            return response()->json([
                'status' => true,
                'data' => $document,
                'filepath' => asset($folder.'/'.$fileName),
                'message' => 'Success!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    } 

    public function deleteDocument(Request $request){
        $comp = session('company');
        if(!$comp){
            return redirect('/');
        }

        DB::beginTransaction();
        try{
            $company = Company::find($comp->id);
            $doc = Document::where('country_id',$company->country_id)->first();
            $folder = 'uploads/'.$company->id;
            if ($request->hasFile('document_file')) {
                
            }
            $document = CompanyDocument::updateOrCreate([
                'company_id'   => $company->id,
            ],[
                'doc_type' => $doc->id,
                'doc_file' => '',
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Success!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    } 

    public function uploadLogo(LogoUploadRequest $request){
        //$comp = Cache::get('company');
        $comp = session('company');
        if(!$comp){
            return redirect('/');
        }

        DB::beginTransaction();
        try{
            $company = Company::find($comp->id);
            $folder = 'uploads/'.$company->id;
            if ($request->hasFile('logo_file')) {
                $logo = $request->file('logo_file');
                $originalName = $logo->getClientOriginalName();
                $fileName = 'company_logo.' . $logo->getClientOriginalExtension();
                //$filePath = $logo->storeAs($folder, $fileName);
                $filePath = $logo->move(public_path($folder), $fileName);
            }
            
            $company->update(['logo' => $folder.'/'.$fileName]);

            DB::commit();

            return response()->json([
                'status' => true,
                'data' => $company,
                'filepath' => asset($folder.'/'.$fileName),
                'message' => 'Success!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    } 
    
    public function deleteLogo(Request $request){
        $comp = session('company');
        if(!$comp){
            return redirect('/');
        }

        DB::beginTransaction();
        try{
            $company = Company::find($comp->id);
            $company->logo = '';
            $company->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Success!',
            ], 200);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    public function companyExists(){
        return view('initiator.company-already-exists');
    }

    
}
