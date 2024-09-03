@extends('sales.layouts.app')
@section('content')
  <!-- Header Starts -->
    @include('sales.layouts.header')
    <!-- Header Ends -->
  <link rel="stylesheet" href="{{ asset('jodit/jodit.min.css') }}" />
    <!-- Main Content Starts -->
    <section class="container-fluid pleft-56">
        <div class="row">
            <!-- Left Pane (Received List) Starts -->
            <div class="col-md-3 pr-0 bid-tap">
                <div class="bid-inbox"> 
                    <div class="bid-header d-flex align-items-center">
                        <h1 class="mr-auto">Received</h1>
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
                    <div id="received-list" class="list-group">
                        @forelse($enquiries as $key => $enquiry)
                            <a href="{{ route('sales.dashboard', ['id' => Crypt::encryptString($enquiry->id)]) }}" class="list-group-item list-group-item-action flex-column align-items-start">
                                <div class="list-item-inner blue-border">
                                    <small class="text-primary bid-date">{{ $enquiry->enquiry->sub_category->name }}</small>
                                    <div class="d-flex w-100 justify-content-between">
                                        <h3>{{ $enquiry->enquiry->sender->company->name ?? '' }}</h3>
                                    </div>
                                    <div class="d-flex w-100 justify-content-between">
                                        <div>
                                            <small class="bid-date">Posted On: {{ \Carbon\Carbon::parse($enquiry->enquiry->created_at)->format('d F, Y') }}</small>
                                            <small class="bid-date">Valid Upto: {{ \Carbon\Carbon::parse($enquiry->enquiry->expired_at)->format('d F, Y') }}</small>
                                        </div>
                                        <small class="bid-hours">{{ \Carbon\Carbon::parse($enquiry->enquiry->expired_at)->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </a>
                        @empty
                        
                        @endforelse
                    </div>
                </div>
            </div>
            <!-- Left Pane (Received List) Ends -->
            @if($selected_enquiry)
            <div id="quote-content" class="col-md-6 pl-0 received-open" style="display: block;">
                <div class="bid-detail-head">
                    <div class="d-flex justify-content-between">
                        <h1>{{ $selected_enquiry->enquiry->sender->company->name }}</h1>
                        <div class="d-flex date-status">
                            Valid Upto: <h2>{{ \Carbon\Carbon::parse($selected_enquiry->enquiry->expired_at)->format('d F, Y') }}</h2>
                        </div>
                    </div>
                    <div class="d-flex date-status justify-content-between mt-2">
                        <div class="d-flex"><h2>Date : {{ \Carbon\Carbon::parse($selected_enquiry->enquiry->created_at)->format('d-m-Y') }} | Time : {{ \Carbon\Carbon::parse($selected_enquiry->enquiry->created_at)->format('h:i:s A') }}</h2></div>
                        <div class="d-flex">
                            <div class="dropdown">
                                <button onclick="myFunction()" class="dropbtn">Report</button>
                                <div id="myDropdown" class="dropdown-content">
                                    <a href="#" class="report" data-category="enquiry" data-type="Spam" data-enquiry_id="${enquiry.id}">Spam</a>
                                    <a href="#" class="report" data-category="enquiry" data-type="Illegal activity" data-enquiry_id="${enquiry.id}">Illegal activity</a>
                                    <a href="#" class="report" data-category="enquiry" data-type="Advertisement" data-enquiry_id="${enquiry.id}">Advertisement</a>
                                    <a href="#" class="report" data-category="enquiry" data-type="Cyberbullying" data-enquiry_id="${enquiry.id}">Cyberbullying</a>
                                </div>
                            </div>
                            @if($selected_enquiry->enquiry->sender->company->is_verified == 1)
                                <span class="verified">Verified</span>
                            @else    
                                <span class="not-verified">Not Verified</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div id="bid-compose-area">
                    <form id="bid-compose-form" method="post">
                        <input type="hidden" id="enquiry_id" name="enquiry_id" value="{{ $selected_enquiry->enquiry->id }}" />
                        <input type="hidden" id="reply_id" name="reply_id" value="{{ $selected_enquiry->enquiry->reply?->id }}" />
                        <div class="bid-detail-content reply-msg-white-bg2 position-relative">
                            <textarea id="body" name="body" class="reply-area">{!! $selected_enquiry->enquiry->reply?->body !!}</textarea>
                            <small id="body-invalid-msg2" class="text-danger"></small>
                        </div>
                        <div id="attachment-add-preview" class="d-flex align-items-left">
                            
                        </div>
                        <div class="m-4">
                            <div id="progressBar" style="display: none;">
                                <div id="progress" style="width: 0%;"></div>
                            </div>
                            <div class="form-group position-relative">
                                <div class="invalid-msg2 mb-2 attchment-error"></div>
                            </div>
                        </div>    
                        <div class="reply-msg-btns d-flex justify-content-end reply-msg-white-bg2">
                            <input type="file" id="upload" name="document_file" hidden />
                            <label for="upload" class="attachment-ico"></label>
                            <div class="form-group proceed-btn">
                                <button id="saveAsDraft" type="button" class="btn btn-third" >Save as Draft</button>
                            </div>
                            <div class="form-group proceed-btn">
                                <button id="discard" type="button" class="btn btn-third" data-id="{{ $selected_enquiry->enquiry->id }}" >Discard</button>
                            </div>
                            <div class="form-group proceed-btn">
                                <input id="send-respone" type="button" value="Send" class="btn btn-secondary">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="bid-detail-content">
                    <div class="d-flex justify-content-between">
                        <h3 class="mb-3">
                        Country : {{ $selected_enquiry->enquiry->country->name }}<br>
                        Region : {{ $selected_enquiry->enquiry->region->name ?? 'All Region' }}<br>
                        Reference No : {{ $selected_enquiry->enquiry->reference_no }}<br>
                        Subject : {{ $selected_enquiry->enquiry->subject }}
                        </h3>
                        @if($selected_enquiry->enquiry->is_limited == 1) 
                            <div class="form-group proceed-btn float-right">
                                @if($selected_enquiry->enquiry->reply) 
                                    <span class="expressed-interest mb-4">
                                        <span>{{ $selected_enquiry->enquiry->reply->participation_approved != null ?  'Interest Approved' : 'Expressed Interest' }}</span>
                                        {{ $selected_enquiry->enquiry->reply->participation_approved != null ?  $selected_enquiry->enquiry->reply->participation_approved : $selected_enquiry->enquiry->reply->is_interested }}
                                    </span>
                                @else
                                    <a id="send-interest" href="#" class="btn btn-secondary" data-id="{{ $selected_enquiry->enquiry->id }}">Interested</a>
                                @endif
                            </div>
                        @else    
                            <div class="form-group proceed-btn float-right"> 
                                @if($selected_enquiry->enquiry->reply) 
                                    <a id='send-reply' href='#' class='btn btn-secondary'>Edit Draft</a>
                                @else    
                                    <a id="send-reply" href="#" class="btn btn-secondary">Reply</a> 
                                @endif
                            </div> 
                        @endif
                        
                        <div class="float-right">
                            @if($selected_enquiry->enquiry->is_limited == 1) 
                                @if($selected_enquiry->enquiry->reply && $selected_enquiry->enquiry->reply->participation_approved != null)
                                    @if($selected_enquiry->enquiry->is_replied != 2) 
                                        <a id="send-reply" href="#" class="btn btn-secondary">Reply</a>
                                    @endif
                                @endif    
                            @endif
                        </div>
                    </div>
                    
                    <article>
                        {!! $selected_enquiry->enquiry->body !!}
                        <hr>
                         <div class="position-relative d-flex justify-content-start align-item-center">
                            <img src="{{ env('APP_URL') }}{{ $selected_enquiry->enquiry->sender->company->logo }}" height="55px" />
                            <p class="ms-4"><strong>{{ $selected_enquiry->enquiry->sender->name }}</strong><br>{{ $selected_enquiry->enquiry->sender->designation }}<br>{{ $selected_enquiry->enquiry->sender->company->name }}</p>
                        </div>
                        <div class="position-relative d-flex justify-content-start align-item-center">
                            <p style="font-size:9px;line-height: 1.1;text-align:justify;">Disclaimer: The information provided in this communication is intended solely for the recipient's 
                            consideration and does not constitute any endorsement or guarantee by Dialectb2b.com. We cannot be held responsible 
                            for the accuracy or reliability of the content herein. Recipients are advised to independently evaluate the products, services, 
                            and businesses mentioned before entering into any agreements or transactions. Dialectb2b.com disclaims all liability for losses, 
                            damages, or disputes arising from communications facilitated through our platform.</p>
                        </div>
                    </article>
                    @if($selected_enquiry->enquiry->attachments->count() > 0)
                    <h1 class="mt-4">Attachments</h1>
                    @endif
                    <div class="d-flex flex-wrap align-items-center enquiry-attachments">
                        @foreach($selected_enquiry->enquiry->attachments as $key => $attachment)
                            <span class="d-flex doc-preview align-items-center justify-content-between mb-2">
                                {{ $attachment->org_file_name ?  $attachment->file_name : $attachment->org_file_name }}
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
                        @if($package->sales_faq_option == 'open')
                        <div class="d-flex justify-content-between last-sec-header">
                            <h1>Questions Asked</h1>
                            <div id="new-question-area" class="form-group">
                                <input type="button" id="raise-question" class="btn btn-third" data-enquiry_id="{{ $selected_enquiry->enquiry->id }}" value="Raise Question">
                            </div>
                        </div>
                        <div class="d-flex w-100 justify-content-between b-bottom">
                            <!-- Tabs navs -->
                            <ul class="nav nav-tabs tab mb-2" role="tablist2">
                                <li class="nav-item">
                                    <a class="nav-link tablinks2 faq active" data-option="open">All</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link tablinks2 faq" data-option="closed">My Questions</a>
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
                        @else
                        <div class="d-flex justify-content-between last-sec-header">
                            <h1>Questions Asked</h1>
                        </div>
                        <div class="d-flex justify-content-center align-item-center">
                            <p>Upgrade to standard plan to gain access to FAQ</p>
                        </div>        
                        @endif
                    </div>
                </div>
            </div>
            @else
            
            @endif
        </div>
    </section>
    <!-- Main Content Ends -->



    <!-- Raise Question Model Starts-->
    @include('sales.received.raise-question-popup')
    <!-- Raise Question Model Ends -->
@push('scripts')





<script>
    $( function() {

        var editor;
        
        editor = new Jodit('textarea#body', {
                        toolbarButtonSize: "small",
                        buttons: 'bold,italic,underline,strikethrough,subscript,superscript,|,ul,ol,|,spellcheck,find,|,align,eraser,font,fontsize,classSpan,paragraph,|,cut,copy,paste,|,link,table,|,indent,outdent,|,undo,redo,|,selectAll,hr', 
                        hotkeys: {
                    		redo: 'ctrl+z',
                    		undo: 'ctrl+y,ctrl+shift+z',
                    		indent: 'ctrl+]',
                    		outdent: 'ctrl+[',
                    		bold: 'ctrl+b',
                    		italic: 'ctrl+i',
                    		removeFormat: 'ctrl+shift+m',
                    		insertOrderedList: 'ctrl+shift+7',
                    		insertUnorderedList: 'ctrl+shift+8',
                    		openSearchDialog: 'ctrl+f',
                    		openReplaceDialog: 'ctrl+r'
                    	},
                        minHeight: 350,
                    });
        
        $('#bid-compose-area').hide();
        
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
            //loadReceivedList();
        });

        $('body').on('change','#mode_filter',function(){
            //loadReceivedList();
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

        $('body').on('click','#raise-question',function () {
            var enquiry_id = $(this).data('enquiry_id');
            $('#enquiry_id').val(enquiry_id);
            $('#raise-question-model').modal('show');
        });
        
        $('body').on('click','.close, .cancel-change',function () {
            $('#raise-question-model').modal('hide');
        });

        $('body').on('click','#send-reply',function(){
            $('#bid-compose-area').show();
            $(this).hide();
        });

        $('body').on('click','#discard',function(){
             var id= $(this).data('id');
             var actionDiscard = "{{ route('sales.discard') }}";
             Swal.fire({
                title: "Are you sure?",
                text: "Bid will be discarded!",
                icon: 'warning',
                showCancelButton: true,
            }).then(function (willDelete) {
                if (willDelete.isConfirmed === true) {
                    axios.post(actionDiscard, {id:id})
                    .then((response) => {
                        window.location.reload();
                    })
                    .catch((error) => { 
                        window.location.reload();
                    });
                }
                else{
                    window.location.reload();
                }
            });
        });

        $('body').on('click','#send-respone',function(){
            //var editorContent = tinymce.get('body').getContent();
            var editorContent = editor.value; 
            console.log(editorContent);
            var formData = new FormData();
            var serializedData = $('#bid-compose-form').serializeArray();
            $.each(serializedData, function(index, field) {
                formData.append(field.name, field.value);
            });
            formData.delete('body');
            formData.append('body', editorContent);

             var sendBidAction = "{{ route('sales.sendBid') }}";
             axios.post(sendBidAction, formData)
                    .then((response) => {
                        // Handle success response
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: "Mail Send!",
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
                        if (error.response.status == 422) {
                            $.each(error.response.data.errors, function(field, errors) {
                                var textarea = $('textarea[name="' + field + '"]');
                                textarea.addClass('red-border');
                                $('#body-invalid-msg2').text(errors[0]).show();
                            });
                        }  
                        if (error.response.status == 500) {
                             Swal.fire({
                                text: error.response.data.message,
                                showCancelButton: false,
                                confirmButtonText: "Go to Subscription",
                                cancelButtonText: "Undo",   
                            }).then(function (willDelete) {
                                  window.location.href = '/subscription';
                            });
                        }
                    });
        })

        $('body').on('click','#saveAsDraft',function(){
            //var editorContent = tinymce.get('body').getContent();
            var editorContent = editor.value; 
            var formData = new FormData();
            var serializedData = $('#bid-compose-form').serializeArray();
            $.each(serializedData, function(index, field) {
                formData.append(field.name, field.value);
            });
            formData.delete('body');
            formData.append('body', editorContent);

             var sendBidAction = "{{ route('sales.saveDraft') }}";
             axios.post(sendBidAction, formData)
                    .then((response) => {
                        // Handle success response
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: "Saved as draft!",
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
                        if (error.response.status == 422) {
                            $.each(error.response.data.errors, function(field, errors) {
                                var textarea = $('textarea[name="' + field + '"]');
                                textarea.addClass('red-border');
                                $('#body-invalid-msg2').text(errors[0]).show();
                            });
                        }      
                    });
        })        
        
        

        $('body').on('change','#upload',function() {
            var uploadAction = "{{ route('sales.bid.uploadAttachment') }}";
            var fileInput = $(this)[0];
            var file = fileInput.files[0]; 
            var formData = new FormData();
            var serializedData = $('#bid-compose-form').serializeArray();
            $.each(serializedData, function(index, field) {
                formData.append(field.name, field.value);
            });
            formData.append('document_file', file);
            
           
            axios.post(uploadAction, formData, {
                    headers: {
                    'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function(progressEvent) {
                        var percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        progress.style.width = percent + '%';
                    }
                })
                .then((response) => {
                    // Handle success response
                   
                    getAttchments(response.data.enquiry_id,response.data.reply_id);
                    progressBar.style.display = 'none';
                    $('#upload').val("");
                })
                .catch((error) => {
                    // Handle error response
                    if (error.response.status == 422) {
                        $.each(error.response.data.errors, function(field, errors) {
                            if(field === 'document_file'){
                                var document_error = $('.attchment-error');
                                console.log(document_error.text());
                                document_error.html(errors[0]).show();
                            }
                        });
                    }
                    progressBar.style.display = 'none';
                });
                progressBar.style.display = 'block';
        });

        $("body").on("click",".delete-attachment",function(){
            var docDeleteAction = $(this).data('url');
            var token = "{{ csrf_token() }}";
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "Attachment will be deleted!",
                icon: 'warning',
                showCancelButton: true,
            }).then(function (willDelete) {
                if (willDelete.isConfirmed === true) {
                    axios.post(docDeleteAction, {id:id})
                    .then((response) => {
                        // Handle success response
                        var attachment = response.data.attchment;
                        getAttchments(attachment.enquiry_id,attachment.reply_id);
                    })
                    .catch((error) => {
                        // Handle error response
                        console.log(error);
                    });
                } else {
                    Swal.fire({
                        title: 'Cancelled',
                        icon: "error",
                    });
                }
            });
        });

        $('body').on('click','.report',function () {
                 var category = $(this).data('category');
                 var type = $(this).data('type');
                 var enquiry_id = $(this).data('enquiry_id');
                 var question_id = $(this).data('question_id');
                 var reportAction = "{{ route('sales.report') }}";
                 axios.post(reportAction, {category:category,type:type,enquiry_id:enquiry_id,question_id:question_id})
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
                    })
                    .catch((error) => { 
                    // Handle error response
                    console.log(error);
                    });
            });

        $("body").on("click","#send-interest",function(){
            var interestedAction = "{{ route('sales.sendInterest') }}";
            var token = "{{ csrf_token() }}";
            var id = $(this).data('id');
            Swal.fire({
                title: "Are you sure?",
                text: "Send Participation Interest",
                icon: 'warning',
                showCancelButton: true,
            }).then(function (willDelete) {
                if (willDelete.isConfirmed === true) {
                    axios.post(interestedAction, {enquiry_id:id})
                    .then((response) => {
                        // Handle success response
                        if(response.data.status === true){
                            window.location.reload();
                        }
                    })
                    .catch((error) => {
                        // Handle error response
                        console.log(error);
                        if (error.response.status == 500) {
                             Swal.fire({
                                text: error.response.data.message,
                                showCancelButton: false,
                                confirmButtonText: "Go to Subscription",
                                cancelButtonText: "Undo",   
                            }).then(function (willDelete) {
                                 window.location.href = '/subscription';
                            });
                        }
                    });
                } else {
                    Swal.fire({
                        title: 'Cancelled',
                        icon: "error",
                    });
                }
            });
        });

        $('body').on('click','#save-question',function () {
                var questionFaqAction = "{{ route('sales.saveQuestion') }}";
                var enquiry_id = $('#enquiry_id').val();
                var question = $('#question').val();
                axios.post(questionFaqAction, {enquiry_id:enquiry_id, question : question})
                    .then((response) => {
                        // Handle success response
                        Swal.fire({
                            toast: true,
                            icon: 'success',
                            title: "Updated",
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
                        if (error.response.status == 422) {
                            $.each(error.response.data.errors, function(field, errors) {
                                var textarea = $('textarea[name="' + field + '"]');
                                textarea.addClass('red-border');
                                var textareafeedback = textarea.siblings('.invalid-msg2');
                                textareafeedback.text(errors[0]).show();
                            });
                        }      
                    });
            });


    }); 

    function getAttchments(enquiry_id,reply_id){
        var attchmentAction = "{{ route('sales.getEnquiryAttachments') }}";
        axios.post(attchmentAction, {enquiry_id:enquiry_id, reply_id:reply_id})
        .then((response) => {
        // Handle success response
            if(response.data.status === true){
                $("#attachment-add-preview").empty();
                var attachments = response.data.attachments;
                attachments.forEach(function(attachment) {
                    $("#attachment-add-preview").append(`<a href="#" class="no-attachmets-list d-flex ">${!attachment.org_file_name ? attachment.file_name : attachment.org_file_name} 
                                <span href="#" class="attachment-cross  delete-attachment" data-id="${attachment.id}" data-url="{{ route('sales.bid.deleteAttachments') }}"></span>
                    </a>`);
                });
            }
        })
        .catch((error) => {
            console.log(error);
        });
    }

    function loadReceivedList(){
            // <h2 class="mb-2 round-bullet">${enquiry.reference_no}</h2>
            var fetchReceivedItemsAction = "{{ route('sales.fetchAllEnquiries') }}";
            var mode_filter = $('#mode_filter option:selected').val();
            var keyword = $('#keyword').val();
            axios.post(fetchReceivedItemsAction, {mode_filter:mode_filter, keyword:keyword})
                 .then((response) => {
                    // Handle success response
                    if(response.data.status === true){
                        let enquiries = response.data.enquiries;
                        $('#received-list').empty();
                        enquiries.forEach(function(enquiry) {
                            var content = `<a id="enquiry-${enquiry.id}" href="#" data-id="${enquiry.id}" class="list-group-item list-group-item-action flex-column align-items-start enquiry_item">
                                                <div class="list-item-inner blue-border">
                                                   
                                                    <small class="text-primary bid-date">${enquiry.category}</small>
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <h3>${enquiry.company}</h3>
                                                    </div>
                                                    <div class="d-flex w-100 justify-content-between">
                                                        <div>
                                                            <small class="bid-date">Posted On: ${enquiry.date}</small>
                                                            <small class="bid-date">Valid Upto: ${enquiry.expiry_date}</small>
                                                        </div>
                                                        <small class="bid-hours">${enquiry.expire_in}</small>
                                                    </div>
                                                </div>
                                            </a>`;
                            $('#received-list').append(content);
                        });

                        
                        
                    }
                 })
                 .then(() => {
                    var id = $('.enquiry_item').data('id');
                    $("#enquiry-"+id).addClass("active");
                    openEnquiry(id);
                 })
                 .catch((error) => { 
                    // Handle error response
                    console.log(error);
                 });
        } 


        function openEnquiry(id){
            var fetchEnquiryAction = "{{ route('sales.fetchEnquiry') }}";
            axios.post(fetchEnquiryAction, {id:id})
                 .then((response) => {
                    // Handle success response
                    console.log(response.data.enquiry);
                    let enquiry = response.data.enquiry;
                    $('#quote-content').empty();
                    $('#open').empty();
                    $('#closed').empty();
                    $('#new-question-area').empty();
                    var content = `<div class="bid-detail-head">
                                        <div class="d-flex justify-content-between">
                                            <h1>${enquiry.sender.company.name}</h1>
                                            <div class="d-flex date-status">
                                                Valid Upto: <h2>${enquiry.expire_at}</h2>
                                            </div>
                                        </div>

                                        <div class="d-flex date-status justify-content-between mt-2">
                                            <div class="d-flex"><h2>Date : ${enquiry.created_date} | Time : ${enquiry.created_time}</h2></div>
                                            <div class="d-flex">
                                                <div class="dropdown">
                                                    <button onclick="myFunction()" class="dropbtn">Report</button>
                                                    <div id="myDropdown" class="dropdown-content">
                                                        <a href="#" class="report" data-category="enquiry" data-type="Spam" data-enquiry_id="${enquiry.id}">Spam</a>
                                                        <a href="#" class="report" data-category="enquiry" data-type="Illegal activity" data-enquiry_id="${enquiry.id}">Illegal activity</a>
                                                        <a href="#" class="report" data-category="enquiry" data-type="Advertisement" data-enquiry_id="${enquiry.id}">Advertisement</a>
                                                        <a href="#" class="report" data-category="enquiry" data-type="Cyberbullying" data-enquiry_id="${enquiry.id}">Cyberbullying</a>
                                                    </div>
                                                </div>
                                                ${enquiry.sender.company.is_verified == 1 ?
                                                    `<span class="verified">Verified</span>` :
                                                        `<span class="not-verified">Not Verified</span>` }
                                            </div>
                                        </div>
                                    </div>
                                    <div id="bid-compose-area">
                                        
                                    </div>
                                    <div class="bid-detail-content">
                                        <div class="d-flex justify-content-between">
                                            <h3 class="mb-3">
                                            Country : ${enquiry.country}<br>
                                            Region : ${enquiry.region}<br>
                                            Reference No : ${enquiry.reference_no}<br>
                                            Subject : ${enquiry.subject}
                                            </h3>
                                            ${enquiry.is_limited == 1 ? 
                                                `<div class="form-group proceed-btn float-right">
                                                    ${enquiry.reply && enquiry.reply ? 
                                                        `<span class="expressed-interest mb-4">
                                                            <span>${enquiry.reply.participation_approved != null ?  `Interest Approved` : `Expressed Interest` }</span>
                                                            ${enquiry.reply.participation_approved != null ?  enquiry.reply.participation_approved : enquiry.reply.is_interested }
                                                        </span>` :
                                                        `<a id="send-interest" href="#" class="btn btn-secondary" data-id="${enquiry.enquiry_id}">Interested</a>`  
                                                    }
                                                </div>` :
                                                `<div class="form-group proceed-btn float-right"> 
                                                    ${enquiry.reply ? 
                                                        `<a id='send-reply' href='#' class='btn btn-secondary' data-id='${enquiry.enquiry_id}' data-body_content='${enquiry.reply.body}' data-reply_id='${enquiry.reply.id}' >Edit Draft</a>` :   
                                                        `<a id="send-reply" href="#" class="btn btn-secondary" data-id="${enquiry.enquiry_id}">Reply</a>` 
                                                    }
                                                </div>` 
                                            }
                                            
                                            <div class="float-right">
                                                ${enquiry.is_limited == 1 ? 
                                                    enquiry.reply && enquiry.reply.participation_approved != null ?
                                                        enquiry.is_replied != 2 ? 
                                                            `<a id="send-reply" href="#" class="btn btn-secondary" data-id="${enquiry.enquiry_id}" data-reply_id="${enquiry.reply.id}" data-body_content="${enquiry.reply.body}">Reply</a>` : ``
                                                        : ''
                                                    : ''    
                                                }
                                            </div>
                                        </div>
                                        
                                        <article>
                                            ${enquiry.body}
                                            <hr>
                                             <div class="position-relative d-flex justify-content-start align-item-center">
                                                <img src="{{ env('APP_URL') }}${enquiry.sender.company.logo}" height="55px" />
                                                <p class="ms-4"><strong>${enquiry.sender.name}</strong><br>${enquiry.sender.designation}<br>${enquiry.sender.company.name}</p>
                                            </div>
                                            <div class="position-relative d-flex justify-content-start align-item-center">
                                                <p style="font-size:9px;line-height: 1.1;text-align:justify;">Disclaimer: The information provided in this communication is intended solely for the recipient's 
                                                consideration and does not constitute any endorsement or guarantee by Dialectb2b.com. We cannot be held responsible 
                                                for the accuracy or reliability of the content herein. Recipients are advised to independently evaluate the products, services, 
                                                and businesses mentioned before entering into any agreements or transactions. Dialectb2b.com disclaims all liability for losses, 
                                                damages, or disputes arising from communications facilitated through our platform.</p>
                                            </div>
                                        </article>
                                        <h1 class="mt-4">${enquiry.attachments.length != 0 ? 'Attachments' : '' }</h1>
                                        <div class="d-flex flex-wrap align-items-center enquiry-attachments">
                                            
                                        </div>
                                    </div>`;
                    $('#quote-content').append(content);  
                    
                    enquiry.attachments.forEach(function(attachment) {
                    $('.enquiry-attachments').append(`<span class="d-flex doc-preview align-items-center justify-content-between mb-2">
                                                    ${!attachment.org_file_name ?  attachment.file_name : attachment.org_file_name}
                                                    <div class="d-flex align-items-center">
                                                        <a id="attachmets-list" href="{{ config('setup.application_url') }}${attachment.path}" class="doc-preview-view" target="_blank"></a>
                                                        <a id="attachmets-list" href="{{ config('setup.application_url') }}${attachment.path}" class="" download>D</a>
                                                    </div>
                                                </span>`);
                    });  

                    $('#new-question-area').append(`<input type="button" id="raise-question" class="btn btn-third" data-enquiry_id="${enquiry.enquiry_id}" value="Raise Question">`);

                    enquiry.all_faqs.forEach(function(all_faq) {
                            $('#open').append(`<div class="open-close-list">
                                <h1>${all_faq.question}</h1>
                                <h3>${all_faq.created_by}</h3>
                                <small class="bid-date">${all_faq.created_at}</small>
                                <div class="colsed-description" ${!all_faq.answer ? 'hidden' : ''}>
                                    ${all_faq.answer}
                                </div>
                            </div>`);
                        });

                        enquiry.my_faqs.forEach(function(my_faq) {
                            $('#closed').append(`<div class="open-close-list">
                                    <h1>${my_faq.question}</h1>
                                    <small class="bid-date">${my_faq.created_at}</small>
                                    <div class="colsed-description" ${!my_faq.answer ? 'hidden' : ''}>
                                        ${my_faq.answer}
                                    </div>
                                </div>`);
                        });

                        

                    
                 })
                 .catch((error) => { 
                    // Handle error response
                    console.log(error);
                 });
        }
</script>
@endpush    
                
@endsection    