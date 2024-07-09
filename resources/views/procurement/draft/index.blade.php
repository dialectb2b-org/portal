@extends('procurement.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('procurement.layouts.header')
    <!-- Header Ends -->

    <!-- Main Content -->
    <section class="container-fluid pleft-56">
        <div class="row">
            <!-- Draft List Starts -->
            <div class="col-md-3 pr-0 bid-tap">
                <div class="bid-inbox">
                    <div class="review-list-header d-flex align-items-center">
                        <h1 class="mr-auto">Draft</h1>
                    </div>
                    <div id="draft-list" class="draft-list-group">
                        @forelse($drafts as $key => $draft)
                        <a href="{{ route('procurement.draft',['id' => Crypt::encryptString($draft->id)]) }}" class="list-group-item2 list-group-item-action flex-column align-items-start {{ $selected_draft->id == $draft->id ? 'active' : '' }}">
                            <div class="list-item-inner">
                                <div class="d-flex w-100 justify-content-between">
                                    <h3>{{ substr($draft->subject ?? 'No Subject',0,40).'...'  }}</h3>
                                </div>
                                <div class="d-flex w-100 justify-content-between">
                                    <small class="bid-date2"> {{ \Carbon\Carbon::parse($draft->updated_at)->format('d F, Y') }}</small>
                                </div>
                            </div>
                        </a>
                        @empty
                        <div class="vh-100 d-flex flex-column justify-content-center align-items-center w-100">
                            <img src="https://img.freepik.com/free-vector/no-data-concept-illustration_114360-626.jpg" alt="Image description" class="img-fluid mb-3">
                            <h2>No Drafts Found!</h2>
                            <p class="mt-4 text-center">There are currently no draft messages to display.</p>
                        </div>
                        @endforelse

                    </div>

                </div>

            </div>

            <!-- Draft List Ends -->

            <!-- Draft Content Starts -->
            <div id="draft-content" class="col-md-9 pl-0 pr-0 scnd-section-main">
                @if(!is_null($selected_draft))
                <div class="mid-sec-main">
                    <div class="mid-second-sec d-flex justify-content-between bg-white">
                        <div class="">
                            <div class="w-100">
                                <h2>{{ $selected_draft->subject }}</h2>
                                <small class="created-date"><span>Created on: </span>{{ \Carbon\Carbon::parse($selected_draft->updated_at)->format('d F, Y') }}</small>
                                <small class="created-date ms-4"><span>Bids accepted till: </span>{{ !$selected_draft->expired_at ? 'NA' : \Carbon\Carbon::parse($selected_draft->expired_at)->format('d F, Y') }}</small>
                            </div>
                           
                            <div class="d-flex mt-2">
                                <h3> Quote For: {{ $selected_draft->sender->name }}, {{ $selected_draft->sender->designation }}, {{ $selected_draft->sender->company->name }} </h3>
                            </div>

                            

                        </div>

                        <div class="d-flex mt-4">
                            <div class="form-group proceed-btn">
                                <button id="discard" type="button" class="btn btn-third" data-url="{{ route('procurement.discardDraft',$selected_draft->id) }}">Discard</button>
                            </div>

                            <div class="form-group proceed-btn">
                                <a href="{{ route('procurement.quote.compose',$selected_draft->id) }}" class="btn btn-secondary">Proceed</a>
                            </div>
                        </div>

                    </div>

                    <div class="bid-detail-content">
                        <p>{!! $selected_draft->body !!}</p>
                        <h1 class="mt-2">{{ count($selected_draft->attachments) != 0 ? 'Attachments' : '' }}</h1>
                        <div class="d-flex flex-column align-items-left float-start attachments">
                            @foreach($selected_draft->attachments as $key => $attachment)
                            <span class="d-flex doc-preview align-items-center justify-content-between mb-2">
                                {{ $attachment->file_name }}
                                <div class="d-flex align-items-center">
                                    <a id="doc-preview-link" href="{{ config('setup.application_url') }}{{ $attachment->path }}" class="doc-preview-view" target="_blank"></a>
                                </div>
                            </span>
                            @endforeach
                        </div>
                    </div>

                </div>
                @else
                <div class="mid-sec-main">
                    <div class="vh-100 d-flex flex-column justify-content-center align-items-center w-100">
                        
                    </div>
                </div>
                @endif
            </div>
            <!-- Draft Content Ends -->

        </div>
    </section>
    <!-- Main Content Ends -->
@push('scripts')
<script>

        function showLoading() {
            $('.btn-third').attr('disabled', true).addClass('btn-loading').html('Discarding...');
        }
        
        function hideLoading() {
            $('.btn-third').attr('disabled', false).removeClass('btn-loading').html('Discard');
        }
        
    jQuery.noConflict();
      jQuery(document).ready(function($) {

        $('body').on('click','#discard',function(){
             var action = $(this).data('url');
             var csrfToken = $('meta[name="csrf-token"]').attr('content');
             Swal.fire({
                title: "Are you sure?",
                text: "Draft will be discarded!",
                icon: 'warning',
                showCancelButton: true,
            }).then(function (willDelete) {
                if (willDelete.isConfirmed === true) {
                    
                    showLoading()
                    
                    var form = $('<form>', {
                        'method': 'POST',
                        'action': action
                    });

                    var inputCsrf = $('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': csrfToken
                    });

                    var inputMethod = $('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    });

                    form.append(inputCsrf, inputMethod);

                    $('body').append(form);

                    form.submit();
                }
                else{
                    
                }
            });
        });
        
        setTimeout(function() { // Adding a small delay to ensure all elements are rendered
            const draftList = $('#draft-list');
            const activeElement = draftList.find('.active');
            if (activeElement.length) {
                // Calculate the scroll position relative to the container
                const scrollOffset = activeElement.offset().top - draftList.offset().top + draftList.scrollTop();
                draftList.animate({ scrollTop: scrollOffset }, 'slow');
            }
        }, 100); // 100ms delay
        
        
        $(document).keydown(function(event) {
                const draftList = $('#draft-list');
                const activeElement = draftList.find('.active');

                if (!activeElement.length) return; 

                let nextElement;
                switch (event.key) {
                    case 'ArrowUp':
                        nextElement = activeElement.prev('.list-group-item2');
                        break;
                    case 'ArrowDown':
                        nextElement = activeElement.next('.list-group-item2');
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
                    draftList.animate({
                        scrollTop: draftList.scrollTop() + nextElement.position().top - draftList.position().top
                    }, 'slow');
                }

                event.preventDefault(); 
            });
        
        //end

    });

    

    
        
        
        
        

    </script>
@endpush
 
@endsection    