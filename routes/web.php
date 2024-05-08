<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/', function () {
    $categories = DB::table('categories')->get();
    return view('welcome',compact('categories'));
});
