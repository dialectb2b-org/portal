<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppFaqCategory;
use App\Models\Content;

class WebsiteController extends Controller
{
    public function index(){
        return view('welcome');
    }
    
    public function aboutUs(){
        return view('about-us');
    }
    
    public function communityGuidelines(){
        $content = Content::first();
        return view('community-guidelines',compact('content'));
    }
    
    public function faq(){
        $categories = AppFaqCategory::with('faqs')->get();
        return view('faq',compact('categories'));
    }
    
    public function privacyPolicy(){
        $content = Content::first();
        return view('privacy-policy',compact('content'));
    }
    
    public function userAgreement(){
        $content = Content::first();
        return view('user-agreement',compact('content'));
    }
    
    
}
