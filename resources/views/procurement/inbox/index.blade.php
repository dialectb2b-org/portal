@extends('procurement.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('procurement.layouts.header')
    <!-- Header Ends -->

    <!-- Main Content -->
    <section class="container-fluid pleft-56">
        <div class="row">
            <!-- Left Pane Starts -->
            <div class="col-md-3 pr-0 bid-tap">
                <div class="bid-inbox">
                    <div class="bid-header d-flex align-items-center">
                        <h1 class="mr-auto">Bid Inbox</h1>
                        <a href="#"  class="search-ico  float-right tablinks4 search_filter" data-option="search"></a>
                        <a href="#"  class="filter-ico float-right tablinks4 search_filter" data-option="filter"></a>
                    </div>
                    <div id="search" class="tabcontent4" style="display: none;">
                        <div class="my-quotes-search d-flex align-items-center justify-content-between">
                            <div class="account-search-box-main">
                                <input id="keyword" type="text" placeholder="Search by reference no." class="form-control">
                            </div>
                        </div>
                    </div>  
                    <div id="filter" class="tabcontent4" style="display: none;">
                        <div class="my-quotes-search d-flex align-items-center justify-content-left">
                            <select id="mode_filter" name="mode_filter" class="form-select">
                                <option value=" ">All</option>
                                <option value="today">Today </option>
                                <option value="yesterday">Yesterday </option>
                                <option value="this_week">This week </option>
                                <option value="last_week">Last week </option>
                                <option value="this_month">This month </option>
                                <option value="last_month">Last month </option>
                            </select>
                        </div>
                    </div>
                    <div id="inbox-list" class="list-group">
                        @forelse($enquiries as $key => $enquiry)
                            <a href="{{ route('procurement.dashboard', ['ref' => Crypt::encryptString($enquiry->reference_no)]) }}" class="list-group-item list-group-item-action flex-column align-items-start enquiry-list {{ $selected_enquiry->id == $enquiry->id ? 'active' : '' }}">
                                <div class="list-item-inner blue-border">
                                    <h2 class="mb-2 round-bullet">{{ $enquiry->reference_no }}</h2>
                                    <div class="d-flex w-100 justify-content-between">
                                        <h3>{{ $enquiry->subject }}</h3>
                                        <small class="bid-count d-flex align-items-center justify-content-center">{{ $enquiry->all_replies->count() }}</small>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="bid-date">{{ \Carbon\Carbon::parse($enquiry->created_at)->format('d-m-Y') }}</small>
                                        @if($enquiry->expired_at > today())
                                            <small class="bid-hours text-success">Valid Until : {{ \Carbon\Carbon::parse($enquiry->expired_at)->format('d-m-Y') }}</small>
                                        @else
                                            <small class="bid-hours text-danger">Expired On : {{ \Carbon\Carbon::parse($enquiry->expired_at)->format('d-m-Y') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="vh-100 d-flex flex-column justify-content-center align-items-center w-100">
                                <img src="https://img.freepik.com/free-vector/no-data-concept-illustration_114360-626.jpg" alt="Image description" class="img-fluid mb-3">
                                <h2>No RFQ Generated</h2>
                                <p class="mt-4 text-center">Currently, there are no Requests for Quotations (RFQs) generated. Please initiate a new RFQ from the bottom right (+) icon to get started.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- Left pane ends -->

            <!-- Middle pane starts -->
            @if($selected_enquiry)
            <div class="col-md-6 pl-0 pr-0 scnd-section-main">
                <div class="mid-sec-main">
                    <div class="mid-sec-header d-flex justify-content-between  justify-content-center">
                        <!-- Bid analysis rybbon starts -->
                        <div class="first-sec d-flex align-items-center" id="quote-options">
                            <div class="completed-screening d-flex align-items-center">
                                <small>Completed<br>Screening</small>
                                <h1><span class="green">{{ $selected_enquiry->action_replies->count() }}</span><span class="grey">/</span>{{ $selected_enquiry->all_replies->count() }}</h1>
                            </div>
                            <div class="completed-screening d-flex align-items-center">
                                <small>Accepted<br>Bids till</small>
                                <h1>{{ \Carbon\Carbon::parse($selected_enquiry->expired_at)->format('d') }}</h1>
                                <small class="date">{{ \Carbon\Carbon::parse($selected_enquiry->expired_at)->format('F') }}<br>{{ \Carbon\Carbon::parse($selected_enquiry->expired_at)->format('Y') }}</small>
                                <a href="#" id="change-date" data-id="{{ $selected_enquiry->id }}" class="edit-ico"></a>
                            </div>
                            <div class="completed-screening d-flex align-items-center">
                                <a href="#" class="share-btn" data-id="{{ $selected_enquiry->id }}" data-reference_no="{{ $selected_enquiry->reference_no }}" data-subject="{{ $selected_enquiry->subject }}" data-date="{{ $selected_enquiry->created_at }}">Share</a>
                            </div>
                        </div>
                        <!-- Bid analysis rybbon ends -->

                        
                        <div class="scond-sec d-flex align-items-center">
                            <!-- Bid notification starts -->
                            <div class="completed-screening d-flex align-items-center position-relative" style="border-right: 0px !important;">
                                <small class="alert-count d-flex align-items-center justify-content-center" id="pending-count">{{ $selected_enquiry->pending_replies->count() }}</small>
                                <a href="#" class="alert-btn"></a>
                                <div id="mark-drop3">
                                <a href="#" class="dummy-btn d-flex"></a>
                                    <ul class="drop-notf" id="notify">
                                        @forelse($selected_enquiry->pending_replies as $key => $pending)
                                            <li class="bid-detail" data-reply_id="{{ $pending->id }}">
                                                New Bid from {{ $pending->sender->company->name }}
                                                <span class="time"> {{ \Carbon\Carbon::parse($pending->created_at)->diffForHumans() }}</span>
                                            </li>
                                        @empty
                                        
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                            <!-- Bid notification ends -->
                        </div>
                    </div>
                    
                    <!-- My Quote Starts -->
                    <div id="my-quote">
                        <div class="mid-second-sec">
                            <div class="d-flex w-100 justify-content-between">
                                <h2>{{ $selected_enquiry->subject }}</h2>
                                <small class="created-date"><span>Created on:</span><br>{{ \Carbon\Carbon::parse($selected_enquiry->created_at)->format('d F, Y') }}</small>
                                <a href="#" class="question-asked">Questions Asked</a>
                            </div>
                            <h3>Quote For: {{ $selected_enquiry->sender->name }}, {{ $selected_enquiry->sender->designation }}, {{ $selected_enquiry->sender->company->name }} </h3>
                            <div class="position-relative msg-expand-main" id="msg-expand">
                                <a href="#" class="read-more">Read More</a>
                                <a href="#" class="read-less">Read Less</a>
                                <article>
                                    {!! $selected_enquiry->body !!}
                                    <div class="position-relative d-flex justify-content-start align-item-center">
                                        <hr>
                                        <img src="{{ env('APP_URL') }}{{ $selected_enquiry->sender->company->logo }}" height="55px" />
                                        <p class="ms-4"><strong>{{ $selected_enquiry->sender->name }}</strong><br>{{ $selected_enquiry->sender->designation }}<br>{{ $selected_enquiry->sender->company->name }}</p>
                                    </div>
                                    <div class="position-relative d-flex justify-content-start align-item-center">
                                        <p style="font-size:9px;line-height: 1.1;text-align:justify">Disclaimer: The information provided in this communication is intended solely for the recipient's 
                                                consideration and does not constitute any endorsement or guarantee by Dialectb2b.com. We cannot be held responsible 
                                                for the accuracy or reliability of the content herein. Recipients are advised to independently evaluate the products, services, 
                                                and businesses mentioned before entering into any agreements or transactions. Dialectb2b.com disclaims all liability for losses, 
                                                damages, or disputes arising from communications facilitated through our platform.</p>
                                    </div>
                                </article>
                                <h1 class="mt-2">{{ count($selected_enquiry->attachments) > 0 ? 'Attachments' : '' }}</h1>
                                <div class="d-flex flex-column align-items-left float-start mt-2 attachments">
                                    @foreach($selected_enquiry->attachments as $key => $attachment)
                                        <span class="attachmets-list" data-file="{{ config('setup.application_url') }}{{ $attachment->path }}">
                                            {{ !$attachment->org_file_name  ? $attachment->file_name : $attachment->org_file_name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- My Quote Ends -->
                    
                    
                    <div class="mid-third-sec">
                        <div class="d-flex w-100 justify-content-between b-bottom">
                            <!-- Bid list tab buttons starts -->
                            <ul class="nav nav-tabs tab mb-2" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link tablinks active" onclick="openCity(event, 'all-bids')">All Bids</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tablinks" onclick="openCity(event, 'shortlisted')">Shortlisted</a>
                                </li>
                            </ul>
                            <!-- Bid list tab buttons ends -->
                        </div>

                        <!-- All bids tab starts -->
                        <div id="all-bids" class="tabcontent" style="display: block;">
                            <div class="row bid-list-head d-flex align-items-center justify-content-center">
                                <div class="col-md-6">
                                    <a href="#" class="sort_company" data-status="true"> 
                                        Company Name 
                                        <i class="ic_sort"><img src="{{ asset('assets/images/ic_sort.svg') }}"></i>
                                    </a>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-center">
                                    <a href="#" class="sort_date" data-status="true">
                                        Date
                                        <i class="ic_sort"><img src="{{ asset('assets/images/ic_sort.svg') }}"></i>
                                    </a>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-center">
                                    <a href="#" class="sort_status" data-status="true">
                                         Status
                                         <i class="ic_sort"><img src="{{ asset('assets/images/ic_sort.svg') }}"></i>
                                    </a>
                                </div>
                            </div>
                            <ul id="all_replies_list" class="all-bid-ul">
                                @forelse($selected_enquiry->all_replies as $key => $all_reply)
                                    <li class="bid-detail" data-reply_id="{{ $all_reply->id }}" tabindex="{{ $key + 1 }}">
                                        <div class="row all-bid-list d-flex align-items-center justify-content-center">
                                            <div class="col-md-6">
                                                <a href="#" >{{ $all_reply->sender->company->name }}</a>
                                                <p>{{ $all_reply->short_content }}</p>
                                            </div>
                                            <div class="col-md-3 d-flex align-items-center justify-content-center"><span
                                                    class="date">{{ $all_reply->created_at }}</span></div>
                                            <div class="col-md-3 d-flex align-items-center justify-content-center">
                                                <span class="status-change status-{{ $all_reply->status_color }}" title="{{ $all_reply->hold_reason }}">{{ $all_reply->status_text }}</span>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                
                                @endforelse
                            </ul>
                        </div>
                        <!-- All bids tab ends -->

                        <!-- Shortlisted bids tab starts -->
                        <div id="shortlisted" class="tabcontent">
                            <div class="row bid-list-head d-flex align-items-center justify-content-center">
                                <div class="col-md-6">
                                    <a href="#" class="sort_company" data-status="true"> 
                                        Company Name 
                                        <i class="ic_sort"><img src="{{ asset('assets/images/ic_sort.svg') }}"></i>
                                    </a>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-center">
                                    <a href="#" class="sort_date" data-status="true">
                                        Date
                                        <i class="ic_sort"><img src="{{ asset('assets/images/ic_sort.svg') }}"></i>
                                    </a>
                                </div>
                                <div class="col-md-3 d-flex align-items-center justify-content-center">
                                    <a href="#" class="sort_status" data-status="true">
                                         Status 
                                        <i class="ic_sort"><img src="{{ asset('assets/images/ic_sort.svg') }}"></i>
                                    </a>
                                </div>
                            </div>
                            <ul id="shortlisted_list" class="all-bid-ul">
                                @forelse($selected_enquiry->shortlisted_replies as $key => $shortlisted)
                                    <li class="bid-detail" data-reply_id="{{ $shortlisted->id }}">
                                        <div class="row all-bid-list d-flex align-items-center justify-content-center">
                                            <div class="col-md-6">
                                                <a href="#" >{{ $shortlisted->sender->company->name }}</a>
                                                <p>{{ $shortlisted->short_content }}</p>
                                            </div>
                                            <div class="col-md-3 d-flex align-items-center justify-content-center"><span
                                                    class="date">{{ $shortlisted->created_at }}</span></div>
                                            <div class="col-md-3 d-flex align-items-center justify-content-center"><span
                                                    class="status-change status-{{ $shortlisted->status_color }}">{{ $shortlisted->status_text }}</span></div>
                                        </div>
                                    </li>
                                @empty
                                
                                @endforelse
                            </ul>
                        </div>
                        <!-- Shortlisted bids tab ends -->
                    </div>

                </div>
            </div>
            <!-- Middle pane ends -->

            <!-- Right pane starts -->

            <!-- Bid read pane starts -->
            <div class="col-md-6 pl-0 bid-open">
                
            </div>
            <!-- Bid read pane ends -->

            <!-- Faq section starts -->
            <div class="col-md-3 pl-0 questions-ask">
                <div class="last-sec-main">
                    <div class="last-sec-main-inner">
                        <div class="d-flex justify-content-between last-sec-header">
                            <h1>Questions Asked</h1>
                            <a href="#" class="cross"></a>
                        </div>
                        <div class="d-flex w-100 justify-content-between b-bottom">
                            <!-- Faq tabs buttons starts -->
                            <ul class="nav nav-tabs tab mb-2" role="tablist2">
                                <li class="nav-item">
                                    <a class="nav-link tablinks2 faq active" data-option="open">Open</a>
                                    <small id="open-count" class="tab-notf-count d-flex align-items-center justify-content-center">{{ $selected_enquiry->open_faqs->count() }}</small>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tablinks2 faq" data-option="closed">Closed</a>
                                    <small id="closed-count" class="tab-notf-count d-flex align-items-center justify-content-center">{{ $selected_enquiry->closed_faqs->count() }}</small>
                                </li>
                            </ul>
                            <!-- Faq tabs buttons ends -->
                        </div>

                        <!-- Open questions tab starts -->
                        <div id="open" class="tabcontent2 scroll-q-asked" style="display: block;">
                            @forelse($selected_enquiry->open_faqs as $key => $open_faq)
                                <div class="open-close-list">
                                    <h4>{{ $open_faq->question }}</h4>
                                    <small class="bid-date">{{ $open_faq->created_at }}</small>
                                    <div class="d-flex w-100 justify-content-between">
                                        <div class="respo-skip-btn">
                                            @if($open_faq->status == 0)
                                                <a href="#" class="respond" data-id="{{ $open_faq->id }}" data-question="{{ $open_faq->question }}">Respond</a>
                                                <a href="#" class="skip" data-id="{{ $open_faq->id }}">Skip</a> 
                                            @else
                                                 <span class="skiped-status">Skipped</span>
                                            @endif
                                        </div>
                                        <div class="dropdown">
                                            <button onclick="myFunction()" class="dropbtn mt-2">Report</button>
                                            <div id="myDropdown" class="dropdown-content"> 
                                                <a href="#" class="report" data-category="question" data-type="Spam" data-enquiry_id="{{ $selected_enquiry->id }}" data-question_id="{{ $open_faq->id }}">Spam</a>
                                                <a href="#" class="report" data-category="question" data-type="Illegal activity" data-enquiry_id="{{ $selected_enquiry->id }}" data-question_id="{{ $open_faq->id }}">Illegal activity</a>
                                                <a href="#" class="report" data-category="question" data-type="Advertisement" data-enquiry_id="{{ $selected_enquiry->id }}" data-question_id="{{ $open_faq->id }}">Advertisement</a>
                                                <a href="#" class="report" data-category="question" data-type="Cyberbullying" data-enquiry_id="{{ $selected_enquiry->id }}" data-question_id="{{ $open_faq->id }}">Cyberbullying</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            
                            @endforelse
                        </div>
                        <!-- Open questions tab ends -->

                        <!-- Closed questions tab starts -->
                        <div id="closed" class="tabcontent2 scroll-q-asked" style="display: none;">
                            @forelse($selected_enquiry->closed_faqs as $key => $closed_faq)
                                <div class="open-close-list">
                                    <h4>{{ $closed_faq->question }}</h4>
                                    <small class="bid-date">{{ $closed_faq->created_at }}</small>
                                    <div class="colsed-description">
                                        {{ $closed_faq->answer }}
                                        <span>You Answered on {{ $closed_faq->answered_at }}</span>
                                    </div>
                                    <a href="#" class="respond" data-id="{{ $closed_faq->id }}" data-question="{{ $closed_faq->question }}" data-answer="{{ $closed_faq->answer }}">Edit Response</a>
                                </div>
                            @empty
                            
                            @endforelse
                        </div>
                        <!-- Closed questions tab ends -->
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-9">
                <div class="vh-100 d-flex flex-column justify-content-center align-items-center w-100">
                    
                </div>
            </div>
            @endif
        </div>
    </section>
    <!-- End Main Content -->

    <!-- Accepted Till Model Starts -->
    @include('procurement.inbox.expiry-popup')
    <!-- Accepted till model ends -->

    <!-- Faq answer model starts -->
    @include('procurement.inbox.faq-popup')
    <!-- Faq answer model ends -->

    <!-- Hold model starts -->
    @include('procurement.inbox.hold-popup')
    <!-- Hold model ends -->

    <!-- Hold model starts -->
    @include('procurement.inbox.share-popup')
    <!-- Hold model ends -->

    <input type="hidden" id="skip-faq-url" value="{{ route('procurement.skipFaq') }}" />
    <input type="hidden" id="report-action-url" value="{{ route('procurement.report') }}" />
    <input type="hidden" id="highlightedReplyId" value="{{ $highlighted_reply_id }}" />

    @push('scripts')
   <script>
      jQuery.noConflict();
      jQuery(document).ready(function($) {

            var highlighted_id = $('#highlightedReplyId').val();
            if(highlighted_id){
                setTimeout(fetchBidDetails(highlighted_id),2000);
            }

            $('body').on('click','.search_filter',function(){
            
                var search_filter = $(this).data('option');

                if(search_filter === "search"){
                    $('#filter').hide();
                    $('#search').show();
                }
                else if(search_filter === "filter"){
                    $('#filter').show();
                    $('#search').hide();
                }
                else{
                    $('#filter').hide();
                    $('#search').hide();
                }
            });

            $('body').on('click','.faq',function(){
                $('.faq').removeClass('active');
                $(this).addClass('active');
                var faq_filter = $(this).data('option');
                
                if(faq_filter === "open"){
                    $('#closed').hide();
                    $('#open').show();
                }
                else if(faq_filter === "closed"){
                    $('#closed').show();
                    $('#open').hide();
                }
                else{
                    $('#closed').hide();
                    $('#open').hide();
                }
            });

            

            $('body').on('keyup','#keyword',function(){
                //loadBidInboxList();
            });

            $('body').on('change','#mode_filter',function(){
                //loadBidInboxList();
            });

            

            $('body').on('click','.read-more',function () {
                $('#msg-expand').removeClass('msg-expand-main');
                $('#msg-expand').addClass('msg-less-main');
                $('.read-more').hide();
                $('.read-less').show();
                $("ul.all-bid-ul").css("height", "calc(100vh - 646px)");
            });

            $('body').on('click','.read-less',function () {
                $('#msg-expand').addClass('msg-expand-main');
                $('#msg-expand').removeClass('msg-less-main');
                $('.read-more').show();
                $('.read-less').hide();
                $("ul.all-bid-ul").css("height", "calc(100vh - 369px)");
            });
 

            $('body').on('click','.cross',function () {
                $('.questions-ask').hide('300');
                $('.scnd-section-main').addClass('col-md-9');
                $('.scnd-section-main').removeClass('col-md-6');
                $('.question-asked').show();
            });

            $('body').on('click','.question-asked',function () {
                $('.questions-ask').show('300');
                $('.scnd-section-main').removeClass('col-md-9');
                $('.scnd-section-main').addClass('col-md-6');
                $('.question-asked').hide();
            });


            $('body').on('click','.report',function () {
                 var category = $(this).data('category');
                 var type = $(this).data('type');
                 var enquiry_id = $(this).data('enquiry_id');
                 var question_id = $(this).data('question_id');
                 var reportAction = $('#report-action-url').val();
                 axios.post(reportAction, {category:category,type:type,enquiry_id:enquiry_id,question_id:question_id})
                    .then((response) => {
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
                    })
                    .catch((error) => { 
                        console.log(error);
                    });
            });

            

            $('body').on('click','.skip',function () {
                var skipFaqAction = $('#skip-faq-url').val();
                var id = $(this).data('id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to skip this question!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then(function (willUpdate) {
                    if (willUpdate.isConfirmed === true) {
                        axios.post(skipFaqAction, {id:id})
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

            
            function fetchBidDetails(reply_id){
                var readReplyAction = "{{ route('procurement.readReply') }}";
                axios.post(readReplyAction, {reply_id:reply_id})
                    .then((response) => {
                        $('.bid-open').empty();
                        $('.bid-tap, .questions-ask').hide('500');
                        $('.bid-open').show('500');
                        $('.scnd-section-main').removeClass('col-md-9');
                        $('.scnd-section-main').addClass('col-md-6');
                        var reply = response.data.reply;
                                $(this).find('.status-change').text(reply.status_text);  
                                $('.bid-open').append(`<div class="bid-detail-head">
                                    <div class="d-flex">
                                        <h1>${reply.sender_company.name}</h1>
                                        ${reply.sender_company.is_verified == 1 ?
                                            `<span class="verified">Verified</span>` :
                                                `<span class="not-verified">Not Verified</span>` }
                                    </div>
                                    <div class="d-flex date-status">
                                        Date <h2>${reply.created_at}</h2> <span class="status">${reply.status_text}</span>
                                    </div>
                                    <div class="d-flex mt-3 justify-content-between">
                                        ${reply.enquiry.is_limited === 1 ?
                                            `<div class="add-hold-btn d-flex">
                                             ${reply.is_interested != null && reply.participation_approved === null ?
                                                `<a href="#" class="approve-interest" data-reply_id="${reply.id}">Approve</a>`
                                                :
                                                reply.relation.is_replied === 1 ? 
                                                    reply.status == 0 ? `
                                                        <a href="#" class="add-shortlist" data-reply_id="${reply.id}">Add to Shortlist</a>
                                                        <a href="#" class="hold-btn hold-button" data-reply_id="${reply.id}">Hold</a>
                                                        ` : 
                                                             reply.status == 1 ? `<a href="#" class="hold-btn hold-button" data-reply_id="${reply.id}">Hold</a>` 
                                                               : `<a href="#" class="add-shortlist" data-reply_id="${reply.id}">Add to Shortlist</a>`
                                                    : ``    
                                                }
                                            </div>` : 
                                            `<div class="add-hold-btn d-flex">
                                                ${reply.status == 0 ? `
                                                    <a href="#" class="add-shortlist" data-reply_id="${reply.id}">Add to Shortlist</a>
                                                    <a href="#" class="hold-btn hold-button" data-reply_id="${reply.id}">Hold</a>`
                                                     : 
                                                      reply.status == 1 ? `<a href="#" class="hold-btn hold-button" data-reply_id="${reply.id}">Hold</a>` 
                                                           : `<a href="#" class="add-shortlist" data-reply_id="${reply.id}">Add to Shortlist</a>`
                                                    
                                                }
                                            </div>`
                                        }

                                        <div class="dropdown">
                                            <button onclick="myFunction()" class="dropbtn">Report</button>
                                            <div id="myDropdown" class="dropdown-content">
                                                <a href="#" class="report" data-category="reply" data-type="Spam" data-enquiry_id="${reply.enquiry_id}" data-question_id="${reply.id}">Spam</a>
                                                <a href="#" class="report" data-category="reply" data-type="Illegal activity" data-enquiry_id="${reply.enquiry_id}" data-question_id="${reply.id}">Illegal activity</a>
                                                <a href="#" class="report" data-category="reply" data-type="Advertisement" data-enquiry_id="${reply.enquiry_id}" data-question_id="${reply.id}">Advertisement</a>
                                                <a href="#" class="report" data-category="reply" data-type="Cyberbullying" data-enquiry_id="${reply.enquiry_id}" data-question_id="${reply.id}">Cyberbullying</a>
                                            </div>
                                        </div>

                                    </div>
                                    <a href="#" class="cross-second"></a>
                                </div>
                                
                                <div class="bid-detail-content">
                                    <article>${reply.body}
                                     <hr>
                                     <div class="position-relative d-flex justify-content-start align-item-center">
                                        <img src="{{ env('APP_URL') }}${reply.sender_company.logo}" height="55px" />
                                        <p class="ms-4"><strong>${reply.sender.name}</strong><br>${reply.sender.designation}<br>${reply.sender_company.name}</p>
                                    </div>
                                    <div class="position-relative d-flex justify-content-start align-item-center">
                                        <p style="font-size:9px;line-height: 1.1;text-align:justify">Disclaimer: The information provided in this communication is intended solely for the recipient's 
                                        consideration and does not constitute any endorsement or guarantee by Dialectb2b.com. We cannot be held responsible 
                                        for the accuracy or reliability of the content herein. Recipients are advised to independently evaluate the products, services, 
                                        and businesses mentioned before entering into any agreements or transactions. Dialectb2b.com disclaims all liability for losses, 
                                        damages, or disputes arising from communications facilitated through our platform.</p>
                                    </div>
                                    </article>

                                    <h1 class="mt-2">${reply.attachments.length != 0 ? 'Attachments' : '' }</h1>
                                    <div class="d-flex flex-wrap align-items-center reply-attachments">
                                       
                                    </div>
                                </div>
                                
                                <div class="bid-detail-content limted-enq-2">
                                        ${reply.is_interested != null ? 
                                            `<h3>Company Mini Profile<h3>
                                            <hr>
                                            <article>   
                                            Company Name : ${reply.sender_company.name}<br>
                                            Website : ${reply.sender_company.domain}

                                            </article>` : `` }
                                    </div>`);

                                reply.attachments.forEach(function(attachment) {
                                $('.reply-attachments').append(`<span class="d-flex doc-preview align-items-center justify-content-between mb-2 attachmets-list">
                                                                ${!attachment.org_file_name ? attachment.file_name : attachment.org_file_name }
                                                                <div class="d-flex align-items-center">
                                                                    <a id="doc-preview-link" href="{{ config('setup.application_url') }}${attachment.path}" class="doc-preview-view" target="_blank"></a>
                                                                    <a id="doc-preview-link" href="{{ config('setup.application_url') }}${attachment.path}" class="" download><i class="fa fa-download" aria-hidden="true"></i></a>
                                                                </div>
                                                            </span>`);
                                                            
                                                                  
                               
                                                       
                                                           
                    });  
                    
                    })
                    .catch((error) => { 
                        // Handle error response
                        
                    });
            }
            $('body').on('click','.bid-detail',function () {
                var reply_id = $(this).data('reply_id');
                setTimeout(fetchBidDetails(reply_id),2000);
            });

            $('body').on('click','.cross-second',function () {
                $('.bid-tap, .questions-ask').show('300');
                $('.bid-open').hide('300');
            });

            $('body').on('click','.add-shortlist',function() {
                var reply_id = $(this).data('reply_id');
                var shortlistAction = "{{ route('procurement.shortlist') }}";
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to shortlist this bid!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then(function (willUpdate) {
                    if (willUpdate.isConfirmed === true) {
                        axios.post(shortlistAction, {reply_id:reply_id})
                            .then((response) => {
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
                            })
                            .catch((error) => { 
                                // Handle error response
                                
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

            $('body').on('click','.approve-interest',function() {
                var reply_id = $(this).data('reply_id');
                var approveAction = "{{ route('procurement.approveInterest') }}";
                Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to approve this company!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then(function (willUpdate) {
                    if (willUpdate.isConfirmed === true) {
                        axios.post(approveAction, {reply_id:reply_id})
                            .then((response) => {
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
                            })
                            .catch((error) => { 
                                // Handle error response
                                 window.location.reload();
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

            

            });

        $('body').on('click','.sort_status',function() {
            var status = $(this).data('status');
            if(status === true){
                $(this).data("status",false)
            }
            else{
                $(this).data("status",true)
            }
            sortByStatus(status);
            sortByStatusShortlist(status);
        });

        $('body').on('click','.sort_date',function() {
            var status = $(this).data('status');
            if(status === true){
                $(this).data("status",false)
            }
            else{
                $(this).data("status",true)
            }
            sortByDate(status);
            sortByDateShortlist(status);
        });

        $('body').on('click','.sort_company',function() {
            var status = $(this).data('status');
            if(status === true){
                $(this).data("status",false)
            }
            else{
                $(this).data("status",true)
            }
            sortByCompany(status);
            sortByCompanyShortlist(status);
        });
                 

        $('body').on('click','.attachmets-list',function () {
             var filepath = $(this).data('file');
             Swal.fire({
                html:'<iframe width="100%" height="315" src="'+filepath+'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>',
                
            })
                            
        });
        

        
        

        


      // Function to sort the bid list by company name
        function sortByCompanyShortlist(ascending) {
            const bidList = document.querySelector('#shortlisted_list');
            const bids = Array.from(bidList.children);

            bids.sort((a, b) => {
                const statusA = a.querySelector('.bid-detail').textContent.trim();
                const statusB = b.querySelector('.bid-detail').textContent.trim();

                if (ascending === true) {
                    return statusA.localeCompare(statusB);
                } else {
                    return statusB.localeCompare(statusA);
                }
            });

            bids.forEach((bid) => bidList.appendChild(bid));
        }

        function sortByCompany(ascending) {
            const bidList = document.querySelector('.all-bid-ul');
            const bids = Array.from(bidList.children);

            bids.sort((a, b) => {
                const statusA = a.querySelector('.bid-detail').textContent.trim();
                const statusB = b.querySelector('.bid-detail').textContent.trim();

                if (ascending === true) {
                    return statusA.localeCompare(statusB);
                } else {
                    return statusB.localeCompare(statusA);
                }
            });

            bids.forEach((bid) => bidList.appendChild(bid));
        }

        // Function to sort the bid list by status
        function sortByDate(ascending) {
            const bidList = document.querySelector('.all-bid-ul');
            const bids = Array.from(bidList.children);

            bids.sort((a, b) => {
                const statusA = a.querySelector('.date').textContent.trim();
                const statusB = b.querySelector('.date').textContent.trim();

                if (ascending === true) {
                    return statusA.localeCompare(statusB);
                } else {
                    return statusB.localeCompare(statusA);
                }
            });

            bids.forEach((bid) => bidList.appendChild(bid));
        }

        function sortByDateShortlist(ascending) {
            const bidList = document.querySelector('#shortlisted_list');
            const bids = Array.from(bidList.children);

            bids.sort((a, b) => {
                const statusA = a.querySelector('.date').textContent.trim();
                const statusB = b.querySelector('.date').textContent.trim();

                if (ascending === true) {
                    return statusA.localeCompare(statusB);
                } else {
                    return statusB.localeCompare(statusA);
                }
            });

            bids.forEach((bid) => bidList.appendChild(bid));
        }

        // Function to sort the bid list by status
        function sortByStatus(ascending) {
            const bidList = document.querySelector('.all-bid-ul');
            const bids = Array.from(bidList.children);

            bids.sort((a, b) => {
                const statusA = a.querySelector('.status-yellow, .status-green, .status-red').textContent.trim();
                const statusB = b.querySelector('.status-yellow, .status-green, .status-red').textContent.trim();

                if (ascending === true) {
                    return statusA.localeCompare(statusB);
                } else {
                    return statusB.localeCompare(statusA);
                }
            });

            bids.forEach((bid) => bidList.appendChild(bid));
        }

        function sortByStatusShortlist(ascending) {
            const bidList = document.querySelector('#shortlisted_list');
            const bids = Array.from(bidList.children);

            bids.sort((a, b) => {
                const statusA = a.querySelector('.status-yellow, .status-green, .status-red').textContent.trim();
                const statusB = b.querySelector('.status-yellow, .status-green, .status-red').textContent.trim();

                if (ascending === true) {
                    return statusA.localeCompare(statusB);
                } else {
                    return statusB.localeCompare(statusA);
                }
            });

            bids.forEach((bid) => bidList.appendChild(bid));
        }

        
   
    </script>     
    
<script>
    $(document).ready(function() {
        const inboxList = $('#inbox-list');

        // Adding a small delay to ensure all elements are rendered
        setTimeout(function() {
            const activeElement = inboxList.find('.active');
            if (activeElement.length) {
                // Calculate the scroll position relative to the container
                const scrollOffset = activeElement.offset().top - inboxList.offset().top + inboxList.scrollTop();
                inboxList.animate({ scrollTop: scrollOffset }, 'slow');
            }

            // Set focus on the inbox list
            inboxList.focus();
        }, 100); // 100ms delay

        // Variable to track if the inbox-list is focused
        let isInboxFocused = false;

        // Set isInboxFocused to true when #inbox-list gains focus  
        inboxList.on('focus', function() {
            isInboxFocused = true;
        });

        // Set isInboxFocused to false when #inbox-list loses focus
        inboxList.on('blur', function() {
            isInboxFocused = false;
        });

        // Prevent default scrolling behavior when #inbox-list is focused
        inboxList.on('wheel', function(event) {
             if (isInboxFocused) {
                 event.preventDefault();
                 const delta = event.originalEvent.deltaY;
                 inboxList.scrollTop(inboxList.scrollTop() + delta);
             }
        });

        $(document).keydown(function(event) {
             // Check if inbox-list is focused
             if (!isInboxFocused) return;

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
    });
</script>


@endpush
 
@endsection    