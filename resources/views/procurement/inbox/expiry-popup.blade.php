<div class="modal fade" id="change-date-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="date-change-form" action="{{ route('procurement.quote.editAcceptedDate') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLongTitle">Accepted Bids till</h1>
                    <button type="button" class="close close-change-date" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 common-popup">
                        <input id="enquiry_id" type="hidden" value="">
                        <div class="form-group position-relative">
                            <label>Accepted Bids till<span class="mandatory"> *</span></label>
                            <input id="expire_at" name="expire_at" type="text" placeholder="Date (DD-MM-YY)" class="form-control choose-category calendar-ico" autocomplete="off">
                            <div class="invalid-msg2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer model-footer-padd">
                    <div class="d-flex justify-content-end">
                        <div class="form-group proceed-btn">
                            <button type="button" class="btn btn-third close-change-date" data-dismiss="modal">Cancel</button>
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
        
        
        var currentDate = new Date();

        var maxDate = new Date();
        maxDate.setMonth(currentDate.getMonth() + 1);

        $("#expire_at").datepicker({
            minDate: 0,
            dateFormat: 'dd-mm-yy',
            maxDate: maxDate
        });
        
        function showLoading() {
            $('.btn-secondary').attr('disabled', true).addClass('btn-loading').html('Saving...');
        }
        
        function hideLoading() {
            $('.btn-secondary').attr('disabled', false).removeClass('btn-loading').html('Save');
        }
        
        $('body').on('click','#change-date',function () {
            var id = $(this).data('id');
            $('#enquiry_id').val(id);
            $('#change-date-model').modal('show');
        });
        
        $('body').on('click','.close-change-date',function () {
            $('#change-date-model').modal('hide');
        });
            
            
        $('body').on('submit', '#date-change-form', function(event) {
            event.preventDefault(); 
    
            var changeExpiryAction = $(this).attr('action');
            var expire_at = $('#expire_at').val();
            var id = $('#enquiry_id').val();
            
            showLoading();
    
            axios.post(changeExpiryAction, { id: id, expire_at: expire_at })
            .then((response) => {
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
                if (error.response.status == 422) {
                    $.each(error.response.data.errors, function(field, errors) {
                        var input = $('input[name="' + field + '"]');
                        input.addClass('red-border');
                        var feedback = input.siblings('.invalid-msg2');
                        feedback.text(errors[0]).show();
                    });
                }   
                else {
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