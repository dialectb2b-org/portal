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
        <div class="container-fluid reg-bg2">
        <section class="container">
            <div class="row">
                <div class=" mt-4 mb-2">
                 
                <div class="sub-plans-head mt-2 mb-3 d-flex align-items-center justify-content-between">
                <div>
                    <h1 style="color:#20285B;"><a href="{{ route('subscription') }}" class="back-btn"></a>Purchase Categories</h1>
                </div>

                <div class="d-flex tab-btns-main">
                    <a href="{{ route('subscription') }}" class="btn tablinks7 btn-forth ">Portal Subscription</a>
                    <a href="{{ route('category-purchase.index') }}" class="btn tablinks7 btn-forth active">Category Subscription</a>
                </div>
            </div>
                    
                </div>
                <section class="reg-content-main mb-4">
                    <section class="reg-content-sec">
                        
                        <div class="signup-fields">
                            <div class="row mb-3">
                                <div class="col-md-12 d-flex justify-content-between justify-content-center">
                                    <h1>Add Business Category</h1>
                                    <div class="selected-basket d-flex">
                                        <span>Selected</span>
                                        <a href="#" class="basket-ico" data-toggle="modal" data-target="#selected-categories">
                                            <small class="basket-count d-flex align-items-center justify-content-center">0</small>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <input id="search" type="text" placeholder="Please Choose Category" class="form-control choose-category">
                                        </div>
                                        <div class="col-md-12">
                                           <ul class="alphabets d-flex flex-wrap align-items-center">
                                                <li><a href="#" class="alpha" data-alpha="All">All</a></li>
                                                <li><a href="#" class="alpha" data-alpha="A">A</a></li>
                                                <li><a href="#" class="alpha" data-alpha="B">B</a></li>
                                                <li><a href="#" class="alpha" data-alpha="C">C</a></li>
                                                <li><a href="#" class="alpha" data-alpha="D">D</a></li>
                                                <li><a href="#" class="alpha" data-alpha="E">E</a></li>
                                                <li><a href="#" class="alpha" data-alpha="F">F</a></li>
                                                <li><a href="#" class="alpha" data-alpha="G">G</a></li>
                                                <li><a href="#" class="alpha" data-alpha="H">H</a></li>
                                                <li><a href="#" class="alpha" data-alpha="I">I</a></li>
                                                <li><a href="#" class="alpha" data-alpha="J">J</a></li>
                                                <li><a href="#" class="alpha" data-alpha="K">K</a></li>
                                                <li><a href="#" class="alpha" data-alpha="L">L</a></li>
                                                <li><a href="#" class="alpha" data-alpha="M">M</a></li>
                                                <li><a href="#" class="alpha" data-alpha="N">N</a></li>
                                                <li><a href="#" class="alpha" data-alpha="O">O</a></li>
                                                <li><a href="#" class="alpha" data-alpha="P">P</a></li>
                                                <li><a href="#" class="alpha" data-alpha="Q">Q</a></li>
                                                <li><a href="#" class="alpha" data-alpha="R">R</a></li>
                                                <li><a href="#" class="alpha" data-alpha="S">S</a></li>
                                                <li><a href="#" class="alpha" data-alpha="T">T</a></li>
                                                <li><a href="#" class="alpha" data-alpha="U">U</a></li>
                                                <li><a href="#" class="alpha" data-alpha="V">V</a></li>
                                                <li><a href="#" class="alpha" data-alpha="W">W</a></li>
                                                <li><a href="#" class="alpha" data-alpha="X">X</a></li>
                                                <li><a href="#" class="alpha" data-alpha="Y">Y</a></li>
                                                <li><a href="#" class="alpha" data-alpha="Z">Z</a></li>
                                           </ul>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                           <ul class="categories-list d-flex flex-wrap align-items-center" id="category-list">
                                               @foreach($categories as $key => $category)
                                               <li><a href="#" class="category" data-id="{{ $category->id }}">{{ $category->name }}</a></li>
                                                @endforeach
                                                
                                           </ul>
                                        </div>
                                    </div>
                                    </div>
                                
                                <div class="col-md-4">
                                    <div class="airconditioning">
                                        <h1 id="current-category"></h1>
                                        <ul id="subcategory-list" class="right-categories-list">
                                            
                                       </ul>
                                    </div>

                                    <!--<div class="total-amount">-->
                                    <!--    <h1 class="pb-2">Total (<span class="basket-count-bottom">0</span>)</h1>-->
                                    <!--    <div class="d-flex align-items-center justify-content-between sub-total">-->
                                    <!--        Sub Total-->
                                    <!--        <span>$<span class="total-price">0.00</span></span>-->
                                    <!--    </div>-->
                                    <!--    <div class="d-flex align-items-center justify-content-between sub-total">-->
                                    <!--        Tax-->
                                    <!--        <span>$<span>0.00</span></span>-->
                                    <!--    </div>-->
                                    <!--    <div class="d-flex align-items-center justify-content-between total">-->
                                    <!--        Sub Total-->
                                    <!--        <span>$<span class="total-price">0.00</span></span>-->
                                    <!--    </div>-->
                                    <!--</div>-->


                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-between justify-content-center">
                            <div class="form-group proceed-btn">
                               
                            </div>
                            <div class="form-group proceed-btn">
                                <a href="{{ route('category-purchase.orderSummary') }}" class="btn btn-secondary" >Proceed To Summary</a>
                            </div>
                        </div>
                    </section>

                </section>
            </div>
        </section>
    </div>
    
    <!-- End Main Content -->
    <!-- Modal Starts-->
    <div class="modal fade" id="selected-categories" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLongTitle">Selected Categories</h1>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="business-catg-main">
                        <ul id="selected-subcategory" class="d-flex flex-wrap">
                            
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex justify-content-between justify-content-center">
                        <div class="form-group proceed-btn">
                            <button type="button" class="btn btn-third" data-dismiss="modal">Cancel</button>
                        </div>

                        <div class="form-group proceed-btn">
                            <a href="{{ route('category-purchase.orderSummary') }}" class="btn btn-secondary" >Proceed To Billing</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  <!-- Model Ends -->
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" integrity="sha512-uMtXmF28A2Ab/JJO2t/vYhlaa/3ahUOgj1Zf27M5rOo8/+fcTUVH0/E0ll68njmjrLqOBjXM3V9NiPFL5ywWPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function() {
        $('.subcategory-section').hide();
        selectedSubCategory();
        $('.loader').hide();
        var company = JSON.parse(localStorage.getItem('company'));
   
        $(document).on('click', '.category', function(e){
            e.preventDefault(); 
            var id = $(this).data('id');
            var action = '/sign-up/business-category/subcategory';
            var data = { 'id' : id }
            axios.post(action,data)
            .then((response) => {
                // Handle success response
                console.log(response.data);
                if(response.data.status === true){
                    $('.subcategory-section').show();
                    $('#subcategory-list').empty();
                    $('#current-category').text(response.data.category.name);
                    if(response.data.data.length){
                        $.each(response.data.data, function(key, val) {
                            var li = `<li id="sel`+val.id+`" class="d-flex align-items-center justify-content-between"><a href="#" class="subcategory" data-id="`+val.id+`">`+val.name+`</a>$`+val.price+`</li>`;
                            $('#subcategory-list').append(li);
                        });
                    }
                    else{
                        var li = `<li><img src="{{ asset('assets/images/no-data.png') }}" width="100%" alt=""></li>
                                    <li class="text-center">No Subcategories found for <br><h3>`+response.data.category.name+`</h3></li>`;
                        $('#subcategory-list').append(li);
                    }
                    
                }
                selectedSubCategory();
            })
            .catch((error) => {
                // Handle error response
                console.log(error);
            });
        });
    });

    $('.alpha').click(function(e) {
        e.preventDefault(); 
        var alpha = $(this).data('alpha');
        var action = "{{ route('sign-up.business-category.alpha') }}";
        axios.post(action,{alpha:alpha})
        .then((response) => {
            $('#category-list').empty();
            response.data.categories.forEach((item, i) => {
                $('#category-list').append('<li><a href="#" class="category" data-id="'+item.id+'">'+item.name+'</a></li>')
            });
        })
        .catch((error) => {
            // Handle error response
            //console.log(error);
        });
    });

    function selectedSubCategory(){
        var action = '/category-purchase/cart';
        axios.get(action)
        .then((response) => {
            // Handle success response
            if(response.data.status === true){
                $('#selected-subcategory').empty();
                if(response.data.data.length){
                    $('.basket-count').text(response.data.data.length);
                    $('.basket-count-bottom').text(response.data.data.length);
                    var sum = 0;

                    $.each(response.data.data, function(key, val) {       
                        var li = `<li class="d-flex justify-content-between justify-content-between">`
                                            + val.subcategory.name +
                                            `<a href="#" data-id="`+val.id+`" class="categ-delete"></a>
                                    </li>`;

                        $('#selected-subcategory').append(li);
                        $('#sel'+val.subcategory.id).addClass('active');
                        sum += parseFloat(val.subcategory.price);
                    });
                    $('.total-price').text(sum);
                }
                else{
                    var li = `<li class="text-center"><h3>No Data Found!</h3></li>`;
                    $('#selected-subcategory').append(li);
                }
            }
            else{

            }
        })
        .catch((error) => {
            // Handle error response
            //console.log(error);
        });
    }


    $(document).on('click', '.categ-delete', function (e) {
        e.preventDefault();
        var activityId = $(this).data('id');
        var activityDeleteAction = '/category-purchase/delete/' + activityId;  
        Swal.fire({
            title: 'Are you sure?',
            text: 'Category will be deleted from the list!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(activityDeleteAction, activityId)
                .then((response) => {
                    // Handle success response
                    Swal.fire('Deleted!', 'The item has been deleted.', 'success');
                    selectedSubCategory();
                })
                .catch((error) => {
                    // Handle error response
                    Swal.fire('Error!', 'An error occurred while deleting the item.', 'error');
                    selectedSubCategory();
                });
            }
        });
    });

    $(document).on('click', '.subcategory', function (e) {
        e.preventDefault();
        var activityId = $(this).data('id');
        var action = '/category-purchase/add';
        var data = { 'id' : activityId }
        axios.post(action,data)
        .then((response) => {
            // Handle success response
            //console.log(response.data.message);
            if(response.data.status === true){
                Swal.fire({
                    toast: true,
                    icon: 'success',
                    title: response.data.message,
                    position: 'top-right',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })
                selectedSubCategory();
            }
            else{
                selectedSubCategory();
            }
        })
        .catch((error) => {
            // Handle error response
            //console.log(error.response.data.message)
            Swal.fire('Warning!', error.response.data.message, 'warning');
            selectedSubCategory();
        });
    });

    $('#search').on('keyup', (e) => {
        $('.subcategory-section').hide();
        $('#category-list').empty();
        let data = { 'search' : e.target.value }
        var searchAction = "{{ route('sign-up.business-category.search') }}";
        axios.post(searchAction, data)
        .then((response) => {
            // Handle success response
            $('#category-list').empty();
            response.data.forEach((item, i) => {
                $('#category-list').append('<li id="sel'+item.id+'"><a href="#" class="subcategory" data-id="'+item.id+'">'+item.name+'</a></li>')
            });
            selectedSubCategory();
        })
        .catch((error) => {
            // Handle error response
            
        });
    })

</script>
@endpush      
@endsection    