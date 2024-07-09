<div class="modal fade" id="answer-question" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form id="respond-faq-form" action="{{ route('procurement.answerFaq') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLongTitle">Respond</h1>
                    <button type="button" class="close close-respond-faq" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-md-12 common-popup">
                        <label>Question</label>
                        <h3 id="question">This is question</h3>
                        <input type="hidden" id="faq_id" />
                    </div>
                    <div class="col-md-12 common-popup">
                        <div class="form-group position-relative">
                            <label>Answer</label>
                            <textarea class="form-control" id="answer" rows="3" name="answer"></textarea>
                            <div class="invalid-msg2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer model-footer-padd">
                    <div class="d-flex justify-content-end">
                        <div class="form-group proceed-btn">
                            <button type="button" class="btn btn-third close-respond-faq" data-dismiss="modal">Cancel</button>
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
        
        $('body').on('click','.respond',function () {
            var id = $(this).data('id');
            var question = $(this).data('question');
            var answer = $(this).data('answer');
            $('#faq_id').val(id);
            $('#question').text(question);
            $('#answer').text(answer);
            $('#answer-question').modal('show');
        });
        
        $('body').on('click','.close-respond-faq',function () {
            $('#answer-question').modal('hide');
        });
        
        $('body').on('submit', '#respond-faq-form', function(event) {
            event.preventDefault();
            
            var answerFaqAction = $(this).attr('action');
            var answer = $('#answer').val();
            var id = $('#faq_id').val();
            
            showLoading();
            
            axios.post(answerFaqAction, {id:id, answer : answer})
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
                    $('#answer').val(' ');
                    $('#answer').removeClass('red-border');
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