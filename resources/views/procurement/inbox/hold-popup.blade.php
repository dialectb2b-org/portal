<div class="modal fade" id="hold-popup" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="hold-bid-form" action="{{ route('procurement.hold') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLongTitle">Hold</h1>
                    <button type="button" class="close close-hold" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 common-popup position-relative">
                        <input id="reply_id" type="hidden" name="reply_id" class="form-control">
                        <label>Remarks <span class="mandatory">*</span></label>
                        <textarea class="form-control" id="reason" name="reason" rows="3" maxlength="100"></textarea>
                        <div class="invalid-msg2"></div>
                    </div>
                </div>
                <div class="modal-footer model-footer-padd">
                    <div class="d-flex justify-content-end">
                        <div class="form-group proceed-btn">
                            <button type="button" class="btn btn-third close-hold" data-dismiss="modal">Cancel</button>
                        </div>
                        <div class="form-group proceed-btn">
                            <button type="submit" class="btn btn-secondary">Save</button>
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
            $('.btn-secondary').attr('disabled', true).addClass('btn-loading').html('Saving...');
        }
        
        function hideLoading() {
            $('.btn-secondary').attr('disabled', false).removeClass('btn-loading').html('Save');
        }
        
        $('body').on('click','.hold-button',function () {
            var id = $(this).data('reply_id');
            $('#reply_id').val(id);
            $('#hold-popup').modal('show');
        });
        
        $('body').on('click','.close-hold',function () {
            $('#hold-popup').modal('hide');
        });
        
        $('body').on('submit', '#hold-bid-form', function(event) {
            event.preventDefault();
    
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