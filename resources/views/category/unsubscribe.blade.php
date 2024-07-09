@php
   $role = auth()->user()->role;
   if($role == 1){
       $extends = 'admin.layouts.app';
       $header = 'admin.layouts.header';
   }
   else if($role == 2){
       $extends = 'procurement.layouts.app';
       $header = 'procurement.layouts.header';
   }
   else if($role == 3){
       $extends = 'sales.layouts.app';
       $header = 'sales.layouts.header';
   }
@endphp

@extends($extends)
@section('content')
    <!-- Header Starts -->
        @include($header)
    <!-- Header Ends -->
    <!-- Main Content -->
    

  <section class="container-fluid pleft-77">
      <form action="{{ route('category-purchase.unsubsubscribeCategorySave') }}" method="post">
          @csrf
        <div class="px-4 py-3">
                <h1 class="mb-4 mt-2">Unsubscribe Category</h1>
            <div class="sub-plans-main">
                <div class="row">
                    <div class="col-md-8 order-summ-content">
                        <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.<br> Excepteur sint occaecat cupidatat non proident, sunt in culpa.</p>
                        <div class="mt-5">
                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="row">
                                <h2>Select Categories</h2>
                               <div class="mt-5"> 
                               @foreach($categories as $category)
                                 
                                    <div class="d-flex align-items-center justify-content-between content-summ mt-2">
                                        <input type="checkbox" value="{{ $category->id }}" name="activity_id[]"> {{ $category->name }}
                                        <span class="d-flex align-items-center">
                                           {{ $category->pivot->expiry_date }}
                                        </span>
                                    </div>
                                @endforeach    
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-4 order-summ-content">
                       
                        
                      
                      
                    </div>

                </div>

                <div class="d-flex justify-content-between align-items-center mt-5">
                    <div class="form-group proceed-btn">
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary" >Back</a>
                    </div>
                    <div class="form-group proceed-btn">
                        <button type="submit" class="btn btn-secondary" >Unsubscribe</a>
                    </div>
                </div>

            </div>
        </div>
       </form>
    </section>
    <!-- Main Content End -->

@endsection    