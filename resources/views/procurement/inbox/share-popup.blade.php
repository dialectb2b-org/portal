<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script>
    var searchUrl = "{{ route('procurement.teamAccount.fetchMembers') }}"
    
    $(document).ready(function() {
    $("#tags").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: searchUrl,
                dataType: 'json',
                data: {
                    query: request.term
                },
                success: function(data) {
                    response(data);
                }
            });
        },
        minLength: 0, // Minimum characters before triggering autocomplete
        select: function(event, ui) {
            console.log(ui);
            $('.shared_to').val(ui.item.id);
        },
        focus: function(event, ui) {
            // Manually trigger the search to fetch all data when the input is focused
            $('#search-msg').text('Fetching team members...');
            $("#tags").autocomplete("search", "");
        }
    });
});


</script>

  <style>

     /* Corrected CSS */
.ui-autocomplete {
    max-height: 200px;
    overflow-y: auto;
    z-index: 1061 !important;
    /* Add your desired styles here */
}
.ui-menu-item {
    padding: 8px 12px;
    /* Add your desired styles here */
}
.ui-menu-item:hover {
    background-color: #f0f0f0;
    /* Add your desired styles here */
}

    </style>
<div class="modal fade" id="share-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="share-form" action="{{ route('procurement.share') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLongTitle">Share</h1>
                    <button type="button" class="close close-share" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 common-popup">
                        <label>Selected Enquiry for sharing</label>
                        <input type="hidden" id="id" name="id" value="" />
                        <div class="sharing-content">
                            <span id="reference_no" class="share-cntent-id"></span>
                            <p id="subject"></p>
                            <span id="enquiry_date" class="date"></span>
                        </div>
                        <div class="row">
                            <div class="col-md-7 ">
                                <label>Select User <span class="mandatory">*</span></label>
                                <input id="tags" class="select-drop">
                                <input type="hidden"  name="shared_to" class="shared_to" value="">
                                <!--<div class="select-drop position-relative">-->
                                <!--    <select id="standard-select" name="shared_to" class="shared_to">-->
                                <!--        <option value=" ">Select Team Member</option>-->
                                <!--        @foreach($members as $key => $member)-->
                                <!--        <option value="{{ $member->id }}">{{ $member->name }} - ( {{ $member->email }} )</option>-->
                                <!--        @endforeach-->
                                <!--    </select>-->
                                    
                                <!--</div>-->
                                <small id="shared_to_error" class="text-danger"></small>
                                <small id="search-msg"></small>
                            </div>
                            <div class="col-md-5">
                                <label>Set Priority <span class="mandatory">*</span></label>
                                <div class="position-relative">
                                    <input type="checkbox"  name="share_priority"  class="share_priority ms-4" value="3"/>
                                    <span class="checkbox-text">High</span>
                                    <!--<select id="standard-select" name="share_priority" class="share_priority">-->
                                    <!--    <option value=" ">Set Priority</option>-->
                                    <!--    <option value="1">Low (SLA: 5 Days)</option>-->
                                    <!--    <option value="2">Medium (SLA: 2 Days)</option>-->
                                    <!--    <option value="3">High (SLA: 1 Day)</option>-->
                                    <!--</select>-->
                                    
                                </div>
                                <small id="share_priority_error" class="text-danger"></small>
                            </div>
                        </div>
                        <div class="bottom-msg mt-4">
                            <span class="text-danger">If the team member is not
                                a dialectb2b user.</span> <span>Please add them as a new team user in</span> <a href="#">Team
                                settings.</a> or send an invitation from your profile menu.
                        </div>
                </div>
            </div>
            <div class="modal-footer model-footer-padd">
                <div class="d-flex justify-content-end">
                    <div class="form-group proceed-btn">
                        <button type="button" class="btn btn-third close-share">Cancel</button>
                    </div>

                    <div class="form-group proceed-btn">
                        <button type="submit" class="btn btn-secondary">Share</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>
</div>
@push('scripts')
<script>
    jQuery.noConflict();
    jQuery(document).ready(function($) {
        
        function showLoading() {
            $('.share-btn').attr('disabled', true).addClass('btn-loading').html('Loading...');
        }
        
        function hideLoading() {
            $('.share-btn').attr('disabled', false).removeClass('btn-loading').html('Share');
        }
        
        function showShareLoading() {
            $('.btn-secondary').attr('disabled', true).addClass('btn-loading').html('Sharing...');
        }
        
        function hideShareLoading() {
            $('.btn-secondary').attr('disabled', false).removeClass('btn-loading').html('Share');
        }
        
        $('body').on('click','.share-btn',function () {
            
            var id = $(this).data('id');
            var ref_no = $(this).data('reference_no');
            var subject = $(this).data('subject');
            var enquiry_date = $(this).data('date');
            var fetchEnquiryAction = "{{ route('procurement.fetchEnquiry') }}";
            
            showLoading();
             
            axios.post(fetchEnquiryAction, {id:id})
                 .then((response) => {
                    // Handle success response
                    let enquiry = response.data.enquiry;
                    if(enquiry.shortlisted.length > 0){
                        $('#id').val(id);
                        $('#reference_no').text(ref_no);
                        $('#subject').text(subject);
                        $('#enquiry_date').text(enquiry_date);
                        $('#share-popup').modal('show');
                        hideLoading();
                    }
                    else{
                        hideLoading();
                        Swal.fire({
                            toast: true,
                            icon: 'warning',
                            title: "Shortlist atleast 1 bid to continue!",
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
                    }
                })
                .catch((error) => { 
                    hideLoading();
                    // Handle error response
                    console.log(error);
                });
        });
        
        $('body').on('click','.close-share',function () {
            $('#share-popup').modal('hide');
        });
        
        $('body').on('submit', '#share-form', function(event) {
            event.preventDefault();
            
            var shareAction = $(this).attr('action');
            var id = $('#id').val();
            var shared_to = $('.shared_to').val();
            var share_priority = $('.share_priority').val();
            
            showShareLoading();
                
            axios.post(shareAction, {id:id, shared_to : shared_to, share_priority: share_priority})
                .then((response) => {
                    // Handle success response
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: "Shared Quote",
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
                    window.location.href="/procurement/review-list/send";
                })
                .catch((error) => { 
                    // Handle error response
                    if (error.response.status == 422) {
                        $.each(error.response.data.errors, function(field, errors) {
                            var select = $('select[name="' + field + '"]');
                            select.addClass('red-border');
                            var span = $("#"+field+"_error");
                            span.text(errors[0]).show();
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
            });
        
        $('body').on('submit', '#hold-bid-form', function(event) {
            event.preventDefault();
            console.log(123);
            var holdAction = $(this).attr('action');
            var reply_id = $('#reply_id').val();
            var reason = $('#reason').val();
           
            showLoading();
            
            axios.post(holdAction, {reply_id:reply_id,reason:reason})
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
                        $('#reason').val(' ');
                        $('#reason').removeClass('red-border');
                        $('.invalid-msg2').text(' ');
                        window.location.reload();
                    })
                    .catch((error) => { 
                        if (error.response.status == 422) {
                            $.each(error.response.data.errors, function(field, errors) {
                                var textarea = $('textarea[name="' + field + '"]');
                                textarea.addClass('red-border');
                                var textareafeedback = textarea.siblings('.invalid-msg2');
                                textareafeedback.text(errors[0]).show();
                            });
                        }
                        else{
                             window.location.reload();
                        }
                })
                .finally(() => {
                     hideLoading();
                     //window.location.reload();
                });
                    
        });
        
    });
</script>
@endpush
