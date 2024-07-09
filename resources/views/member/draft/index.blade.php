@extends('member.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('member.layouts.header')
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
                    <div class="draft-list-group">
                        @forelse($drafts as $key => $draft)
                        <a href="{{ route('member.draft',$draft->id) }}" class="list-group-item2 list-group-item-action flex-column align-items-start draft_item">
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
                        <a href="#" class="list-group-item2 list-group-item-action flex-column align-items-start">
                            <div class="list-item-inner">
                                <div class="d-flex w-100 justify-content-between">
                                    <h3>No Data Found</h3>
                                </div>
                            </div>
                        </a>
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
                                <button id="discard" type="button" class="btn btn-third" data-url="{{ route('member.discardDraft',$selected_draft->id) }}">Discard</button>
                            </div>
                            <div class="form-group proceed-btn">
                                <a href="{{ route('member.quote.compose',$selected_draft->id) }}" class="btn btn-secondary">Proceed</a>
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
                    
                </div>
                @endif
            </div>
            <!-- Draft Content Ends -->

        </div>
    </section>
    <!-- Main Content Ends -->
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script>
    $( function() {
        

        $('body').on('click','#discard',function(){
             var action = $(this).data('url');
             var id = $(this).data('id');
             Swal.fire({
                title: "Are you sure?",
                text: "Draft will be discarded!",
                icon: 'warning',
                showCancelButton: true,
            }).then(function (willDelete) {
                if (willDelete.isConfirmed === true) {
                    axios.post(action, {id:id})
                        .then((response) => {
                            Swal.fire({
                                toast: true, 
                                icon: 'success',
                                title: "Quote has been discarded!",
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
                }
            });
        });

    });
</script>    
@endpush
 
@endsection    