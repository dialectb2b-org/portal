@extends('procurement.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('procurement.layouts.header')
    <!-- Header Ends -->

    <!-- Main Content Start -->
    <section class="container-fluid pleft-56">
        <!-- Approval List Section Start-->
        <div class="row team-accnt-tab  b-bottom">
            <div class="col-md-12">
                <div class="d-flex w-100 justify-content-between">
                    <!-- Tabs navs -->
                    <ul class="nav nav-tabs tab mb-2" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link tablinks3  active" href="{{ route('procurement.teamAccount.approval') }}">Approvals</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link tablinks3" href="{{ route('procurement.teamAccount.settings') }}">Team Settings</a>
                        </li>
                    </ul>
                    <!-- Tabs navs -->
                </div>
            </div>
        </div>
        <!-- Approval List Section End -->

        <!-- Approval Content Section Start -->
        <div class="tabcontent3" id="sent" style="display: block;">
            <div class="row">
                <!-- Left Pane Starts -->
                <div class="col-md-3 pr-0 bid-tap">
                    <div class="bid-inbox">
                        <!-- Search & Filter Starts -->
                        <div class="team-accnt-search d-flex align-items-center justify-content-between">
                            <div id="search" class="account-search-box-main">
                                <input type="text" placeholder="Search" class="form-control">
                            </div>
                            <div id="filter" class="account-search-box-main" style="display: none;">
                                <select id="mode_filter" name="mode_filter" class="form-select">
                                    <option value=" ">All</option>
                                    <option value="today">Today </option>
                                    <option value="yesterday">Yesterday </option>
                                    <option value="last_week">Last week </option>
                                    <option value="this_week">This week </option>
                                    <option value="last_month">Last month </option>
                                    <option value="this_month">This month </option>
                                </select>
                            </div>
                            <a href="#" class="filter-ico2 float-right search_filter" data-option="search"></a>
                        </div>
                        <!-- Search & Filter Ends -->

                        <!-- Enquiry List Starts -->
                            <div id="inbox-list" class="list-group ">
                                @forelse($enquiries as $key => $enquiry)
                                    <a href="{{ route('procurement.teamAccount.approval', ['ref' => Crypt::encryptString($enquiry->reference_no)]) }}" data-id="{{ $enquiry->id }}" class="list-group-item list-group-item-action flex-column align-items-start enquiry-list {{ $selected_enquiry->id == $enquiry->id ? 'active' : '' }}">
                                        <div class="list-item-inner blue-border">
                                            <div class="d-flex justify-content-between">
                                                <h2 class="mb-2  bullet-light-blue d-flex">{{ $enquiry->reference_no }}</h2>
                                                <!--<span href="#" class="dots-ico"></span>-->
                                            </div>
                                            <div class="d-flex w-100 justify-content-between">
                                                <h3>{{ $enquiry->subject}}</h3>
                                            </div>
                                            <div class="d-flex w-100 justify-content-between">
                                                <small class="bid-date"> {{ \Carbon\Carbon::parse($enquiry->created_at)->format('d F, Y') }}</small>
                                                <small class="bid-hours">
                                                    @if($enquiry->expired_at > today())
                                                        <small class="bid-hours text-success">Valid Until : {{ \Carbon\Carbon::parse($enquiry->expired_at)->format('d-m-Y') }}</small>
                                                    @else
                                                        <small class="bid-hours text-danger">Expired On : {{ \Carbon\Carbon::parse($enquiry->expired_at)->format('d-m-Y') }}</small>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                    </a>
                                @empty    
                                    <div class="vh-100 d-flex flex-column justify-content-center align-items-center w-100">
                                        <img src="https://img.freepik.com/free-vector/no-data-concept-illustration_114360-626.jpg" alt="Image description" class="img-fluid mb-3">
                                        <h2>You’re all caught up</h2>
                                        <p>Nice work!</p>
                                    </div>
                                @endforelse
                            </div>
                        <!-- Enquiry List Ends -->
                    </div>
                </div>
                <!-- Left Pane Ends -->
                
                <!-- Right Pane Starts -->
                @if($selected_enquiry)
                <div class="col-md-9 pl-0 pr-0 scnd-section-main">
                    
                    <div id="my-quote">
                        <!-- Content Starts -->
                        <div class="mid-sec-main">
                                        <div class="mid-second-sec d-flex justify-content-between bg-white">
                                            <div class="">
                                                <div class="w-100">
                                                    <h2>{{ $selected_enquiry->subject }}</h2>
                                                    <small class="created-date"><span>Created on: </span>{{ \Carbon\Carbon::parse($selected_enquiry->created_at)->format('d F, Y') }}</small>
                                                    <small class="created-date ms-4"><span>Bids accepted till: </span>{{ \Carbon\Carbon::parse($selected_enquiry->expired_at)->format('d F, Y') }}</small>
                                                </div>

                                                <div class="d-flex mt-2">
                                                    <h3>Quote For: {{ $selected_enquiry->sender?->name }}, {{ $selected_enquiry->sender?->designation }}, {{ $selected_enquiry->sender?->company_name }}  </h3>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-end flex-column">
                                                <div class="requested-by">Requested by:<br>
                                                    <a href="#"> {{ $selected_enquiry->sender?->email }}</a>
                                                </div>
                                                <div class="form-group proceed-btn float-right">
                                                    <button type="button" class="btn btn-secondary approve" data-url="{{ route('procurement.teamAccount.approveQuote') }}" data-id="{{ $selected_enquiry->id }}" >Approve</button>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="bid-detail-content">
                                            <p>{!! $selected_enquiry->body !!}</p>
                                        </div>

                                        <h1 class="mt-2">{{ count($selected_enquiry->attachments) > 0 ? 'Attachments' : '' }}</h1>
                                        <div class="d-flex flex-column align-items-left float-start mt-2 attachments">
                                            @foreach($selected_enquiry->attachments as $key => $attachment)
                                                <span class="d-flex doc-preview align-items-center justify-content-between mb-2">
                                                    {{ $attachment->file_name }}
                                                    <div class="d-flex align-items-center">
                                                        <a id="doc-preview-link" href="{{ config('setup.application_url') }}{{ $attachment->path }}" class="doc-preview-view" target="_blank"></a>
                                                        <a id="doc-preview-link" href="{{ config('setup.application_url') }}{{ $attachment->path }}" class="" download>D</a>
                                                    </div>
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    <!-- Content Ends -->
                    </div>
                    
                    
                </div>
                @else
                 <div class="col-md-9">
                    <div class="vh-100 d-flex flex-column justify-content-center align-items-center w-100">
                        
                    </div>
                </div>
                @endif
                <!-- Right Pane Ends -->
            </div>    
        </div>
        <!-- Approval Content Section End -->
    </section>
    <!-- Main Content Ends -->

@push('scripts')
    
    
    <script>
    
        
        
        jQuery.noConflict();
        jQuery(document).ready(function($) {

            $('body').on('click','.search_filter',function(){
                $('#filter').toggle();
                $('#search').toggle();
            });

            $('body').on('keyup','#keyword',function(){
                loadBidInboxList();
            });

            $('body').on('change','#mode_filter',function(){
                loadBidInboxList();
            });

            $('body').on('click','.approve',function () {
                var approveQuoteAction = $(this).data('url');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to approve the quote!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then(function (willUpdate) {
                    if (willUpdate.isConfirmed === true) {
                        
                        showLoading();
                        
                        axios.post(approveQuoteAction, {id:id})
                             .then((response) => {
                                // Handle success response
                                Swal.fire({
                                    toast: true,
                                    icon: 'success',
                                    title: response.data.message,
                                    animation: false,
                                    position: 'top-right',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true,
                                    didOpen: (toast) => {
                                        toast.addEventListener('mouseenter', Swal.stopTimer)
                                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                                    }
                                });
                                window.location.reload();
                                //loadBidInboxList();
                             })
                             .catch((error) => { 
                                // Handle error response
                                console.log(error);
                             });
                    }
                    else{
                        Swal.fire({
                            title: 'Cancelled',
                            icon: "error",
                        });
                    }
                });
                
            });
            
             setTimeout(function() { // Adding a small delay to ensure all elements are rendered
                const inboxList = $('#inbox-list');
                const activeElement = inboxList.find('.active');
                if (activeElement.length) {
                    // Calculate the scroll position relative to the container
                    const scrollOffset = activeElement.offset().top - inboxList.offset().top + inboxList.scrollTop();
                    inboxList.animate({ scrollTop: scrollOffset }, 'slow');
                }
            }, 100); // 100ms delay
            
             $(document).keydown(function(event) {
                const inboxList = $('#inbox-list');
                const activeElement = inboxList.find('.active');

                if (!activeElement.length) return; 

                let nextElement;
                switch (event.key) {
                    case 'ArrowUp':
                        nextElement = activeElement.prev('.list-group-item');
                        break;
                    case 'ArrowDown':
                        nextElement = activeElement.next('.list-group-item');
                        break;
                    case 'Enter':
                        window.location.href = activeElement.attr('href');
                        return;    
                    default:
                        return; 
                }

                if (nextElement.length) {
                    activeElement.removeClass('active');
                    nextElement.addClass('active');
                    inboxList.animate({
                        scrollTop: inboxList.scrollTop() + nextElement.position().top - inboxList.position().top
                    }, 'slow');
                }

                event.preventDefault(); 
            });
            
            function showLoading() {
            $('.btn-secondary').attr('disabled', true).addClass('btn-loading').html('Approving...');
        }
        
        function hideLoading() {
            $('.btn-secondary').attr('disabled', false).removeClass('btn-loading').html('Approve');
        }
        
            //end

        });


        </script>
        



@endpush
 
@endsection    