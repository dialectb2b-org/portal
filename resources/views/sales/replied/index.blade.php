@extends('sales.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('sales.layouts.header')
    <!-- Header Ends -->

    <!-- Main Content Starts -->
    <section class="container-fluid pleft-56">
        <div class="row">
            <div class="col-md-3 pr-0 bid-tap">
                <div class="bid-inbox">
                    <div class="bid-header d-flex align-items-center">
                        <h1 class="mr-auto">Replied</h1>
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
                            <!-- <div class="custom-select" style="margin-left: 0; "> -->
                                <select id="mode_filter" name="mode_filter" class="form-select">
                                    <option value=" ">All</option>
                                    <option value="today">Today </option>
                                    <option value="yesterday">Yesterday </option>
                                    <option value="this_week">This week </option>
                                    <option value="last_week">Last week </option>
                                    <option value="this_month">This month </option>
                                    <option value="last_month">Last month </option>
                                </select>
                            <!-- </div> -->
                        </div>
                    </div>
                    <div id="replied-list" class="list-group">
                        @forelse($enquiries as $key => $enquiry)
                            <a href="{{ route('sales.repliedEnquiry', ['id' => Crypt::encryptString($enquiry->id)]) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="list-item-inner blue-border">
                                    <small class="text-primary bid-date">{{ $enquiry->enquiry->sub_category->name }}</small>
                                    <div class="d-flex w-100 justify-content-between">
                                        <h3>{{ $enquiry->enquiry->sender->company->name }}</h3>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <small class="bid-date">Replied On: {{ $enquiry->enquiry->reply->created_at }}</small>
                                        </div>
                                        <small class="bid-hours"></small>
                                    </div>
                                </div>
                            </a>
                        @empty
                        
                        @endforelse
                    </div>
                </div>
            </div>
            @if($selected_enquiry)
            <div id="quote-content" class="col-md-6 pl-0 reply-msg-white-bg4" style="display: block;">
                <div class="bid-detail-head reply-msg-white-bg">
                    <div class="d-flex justify-content-between">
                        <h1>{{ $selected_enquiry->enquiry->sender->company->name }}</h1>
                        <div class="d-flex date-status"></div>
                    </div>
                    <div class="d-flex date-status justify-content-between mt-2">
                        <div class="d-flex"><h2>Date : {{ \Carbon\Carbon::parse($selected_enquiry->enquiry->created_at)->format('d-m-Y') }} | Time : {{ \Carbon\Carbon::parse($selected_enquiry->enquiry->created_at)->format('h:i:s A') }}</h2></div>
                        <div class="d-flex">Valid Upto : <h2>{{ \Carbon\Carbon::parse($selected_enquiry->enquiry->expired_at)->format('d F, Y') }}</h2></div>
                    </div>
                </div>
                <div class="bid-detail-content reply-msg-white-bg3">
                    <article>
                        {!! $selected_enquiry->enquiry->reply->body !!}
                        <hr>
                        <div class="position-relative d-flex justify-content-start align-item-center">
                            <img src="{{ env('APP_URL') }}{{ $selected_enquiry->enquiry->reply->sender->company->logo }}" height="55px" />
                            <p class="ms-4"><strong>{{ $selected_enquiry->enquiry->reply->sender->name }}</strong><br>{{ $selected_enquiry->enquiry->reply->sender->designation }}<br>{{ $selected_enquiry->enquiry->reply->sender->company->name }}</p>
                        </div>
                        <div class="position-relative d-flex justify-content-start align-item-center">
                            <p style="font-size:9px;line-height: 1.1;text-align:justify;">Disclaimer: The information provided in this communication is intended solely for the recipient's 
                            consideration and does not constitute any endorsement or guarantee by Dialectb2b.com. We cannot be held responsible 
                            for the accuracy or reliability of the content herein. Recipients are advised to independently evaluate the products, services, 
                            and businesses mentioned before entering into any agreements or transactions. Dialectb2b.com disclaims all liability for losses, 
                            damages, or disputes arising from communications facilitated through our platform.</p>
                        </div>
                        <div class="d-flex justify-content-end reply_attachments">
                            @foreach($selected_enquiry->enquiry->reply->attachments as $key => $attachment)
                                <span class="d-flex doc-preview align-items-center justify-content-between mb-2">
                                    {{ $attachment->file_name }}
                                    <div class="d-flex align-items-center">
                                        <a id="attachmets-list" href="{{ config('setup.application_url') }}{{ $attachment->path }}" class="doc-preview-view" target="_blank"></a>
                                        <a id="attachmets-list" href="{{ config('setup.application_url') }}{{ $attachment->path }}" class="" download>D</a>
                                    </div>
                                </span>
                            @endforeach
                        </div>
                    </article>
                </div>
                <div class="bid-detail-content reply-msg-white-bg3 mt-2">
                    <div class="d-flex justify-content-between">
                        <h3 class="mb-3">
                            Country : {{ $selected_enquiry->enquiry->country->name }}<br>
                            Region : {{ $selected_enquiry->enquiry->region->name ?? 'All Region' }}<br>
                            Reference No : {{ $selected_enquiry->enquiry->reference_no }}<br>
                            Subject : {{ $selected_enquiry->enquiry->subject }}
                        </h3>
                        <div>
                            <div class="d-flex date-status justify-content-end">
                                 Expiry:<h2>{{ \Carbon\Carbon::parse($selected_enquiry->enquiry->expired_at)->format('d F, Y') }}</h2>
                            </div>
                            @if($selected_enquiry->enquiry->sender->company->is_verified == 1)
                                <span class="verified">Verified</span>
                            @else    
                                <span class="not-verified">Not Verified</span>
                            @endif    
                        </div>
                    </div>
                    <article>
                    {!! $selected_enquiry->enquiry->body !!}
                    </article>
                    <hr>
                    <div class="position-relative d-flex justify-content-start align-item-center">
                        <img src="{{ env('APP_URL') }}{{ $selected_enquiry->enquiry->sender->company->logo }}" height="55px" />
                        <p class="ms-4"><strong>{{ $selected_enquiry->enquiry->sender->name }}</strong><br>{{ $selected_enquiry->enquiry->sender->designation }}<br>{{ $selected_enquiry->enquiry->sender->company->name }}</p>
                    </div>
                    <div class="position-relative d-flex justify-content-start align-item-center">
                        <p style="font-size:9px">Disclaimer: The information provided in this communication is intended solely for the recipient's 
                        consideration and does not constitute any endorsement or guarantee by Dialectb2b.com. We cannot be held responsible 
                        for the accuracy or reliability of the content herein. Recipients are advised to independently evaluate the products, services, 
                        and businesses mentioned before entering into any agreements or transactions. Dialectb2b.com disclaims all liability for losses, 
                        damages, or disputes arising from communications facilitated through our platform.</p>
                    </div>
                    <div class="d-flex justify-content-end attachments">
                        @foreach($selected_enquiry->enquiry->attachments as $key => $attachment)
                            <span class="d-flex doc-preview align-items-center justify-content-between mb-2">
                                {{ $attachment->file_name }}
                                <div class="d-flex align-items-center">
                                    <a id="attachmets-list" href="{{ config('setup.application_url') }}{{ $attachment->path }}" class="doc-preview-view" target="_blank"></a>
                                    <a id="attachmets-list" href="{{ config('setup.application_url') }}{{ $attachment->path }}" class="" download>D</a>
                                </div>
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>    
            <div class="col-md-3 pl-0 questions-ask">
                <div class="last-sec-main">
                    <div class="last-sec-main-inner">
                        <div class="d-flex justify-content-between last-sec-header">
                            <h1>Questions Asked</h1>
                        </div>
                        <div class="d-flex w-100 justify-content-between b-bottom">
                            <!-- Tabs navs -->
                            <ul class="nav nav-tabs tab mb-2" role="tablist2">
                                <li class="nav-item">
                                    <a class="nav-link tablinks2 active" onclick="openCity2(event, 'open')">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tablinks2" onclick="openCity2(event, 'closed')">My Questions</a>
                                </li>
                            </ul>
                            <!-- Tabs navs -->
                        </div>
                        <!-- Tabs 1-->
                        <div id="open" class="tabcontent2 scroll-q-asked" style="display: block;">
                            @forelse($selected_enquiry->enquiry->all_faqs as $key => $all_faq)
                                <div class="open-close-list">
                                    <h1>{{ $all_faq->question }}</h1>
                                    <h3>{{ $all_faq->sender->company->name }}</h3>
                                    <small class="bid-date">{{ $all_faq->created_at }}</small>
                                    @if($all_faq->answer)
                                    <div class="colsed-description">
                                        {{ $all_faq->answer }}
                                    </div>
                                    @endif
                                </div>
                            @empty
                            
                            @endforelse
                        </div>
                        <!-- Tabs 2 -->
                        <div id="closed" class="tabcontent2 scroll-q-asked" style="display: none;">
                            @forelse($selected_enquiry->enquiry->my_faqs as $key => $my_faq)
                                <div class="open-close-list">
                                    <h1>{{ $my_faq->question }}</h1>
                                    <small class="bid-date">{{ $my_faq->created_at }}</small>
                                    @if($my_faq->answer)
                                    <div class="colsed-description">
                                        {{ $my_faq->answer }}
                                    </div>
                                    @endif
                                </div>
                            @empty

                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            @else
            
            @endif
        </div>
    </section>
    <!-- Main Content Ends -->

    @push('scripts')




<script>
    $( function() {

        //loadRepliedList();


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

        $('body').on('keyup','#keyword',function(){
            //loadRepliedList();
        });

        $('body').on('change','#mode_filter',function(){
            //loadRepliedList();
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

        
    });


    function loadRepliedList(){
            var fetchReceivedItemsAction = "{{ route('sales.replied.fetchAllEnquiries') }}";
            var mode_filter = $('#mode_filter option:selected').val();
            var keyword = $('#keyword').val();
            axios.post(fetchReceivedItemsAction, {mode_filter:mode_filter, keyword:keyword})
                 .then((response) => {
                    // Handle success response
                    //<h2 class="mb-2 round-bullet">${enquiry.reference_no}</h2>
                    if(response.data.status === true){
                        let enquiries = response.data.enquiries;
                        
                        $('#replied-list').empty();
                        enquiries.forEach(function(enquiry) {
                            var content = `<a id="enquiry-${enquiry.id}" href="#" data-id="${enquiry.id}" class="list-group-item list-group-item-action flex-column align-items-start enquiry_item">
                                                <div class="list-item-inner blue-border">
                                                    
                                                    <small class="text-primary bid-date">${enquiry.category}</small>
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h3>${enquiry.company}</h3>
                                                    </div>
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <div>
                                                            <small class="bid-date">Replied On: ${enquiry.reply.created_at}</small>
                                                        </div>
                                                        <small class="bid-hours"></small>
                                                    </div>
                                                </div>
                                            </a>`;
                            $('#replied-list').append(content);
                        });

                        
                        
                    }
                 })
                
                 .catch((error) => { 
                    // Handle error response
                    console.log(error);
                 });
        } 


       
</script>
@endpush    
                
@endsection    