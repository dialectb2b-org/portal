@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->
    
        <section class="container-fluid pleft-77">
            <div class="px-4 py-3">
                <h1 class="mb-4 mt-2">Account Verification</h1>
                <form action="" method="post">
                    <div class="sub-plans-main">
                        <div class="row">
                            <div class="col-md-12 order-summ-content">
                                <p>Value of getting verified with Dialectb2b.com</p>
                                <div class="mt-2">
                                    <div class="row">
                                   <p>
                                       The verified badge on Dialectb2b.com provides tangible benefits beyond mere symbolism. It empowers your company with increased credibility, visibility, and protection against impersonation, ultimately contributing to a more secure and trusted online environment.</p>

<p>Boosted Credibility: The verified badge on Dialectb2b.com is more than just a status symbol; it's a testament to your credibility. By having a verified account, you showcase authenticity and trustworthiness to your audience and potential collaborators. This heightened credibility can lead to stronger connections, increased client trust, and an enhanced reputation within the industry.</p>

<p>Enhanced Visibility: A verified account enjoys increased visibility on Dialectb2b.com. It stands out in search of results and recommendations, making it easier for clients and partners to find and engage with your company. This heightened visibility can transform into more opportunities, partnerships, and business growth.</p>

<p>Protection Against Impersonation: Verification on Dialectb2b.com offers robust protection against impersonation and fraudulent activities. By verifying your account, you mitigate the risk of malicious entities creating fake accounts in your company's name. This safeguard not only protects your brand integrity, but also ensures a secure online environment for your clients and collaborators.</p>

<p>Improved Risk Management: Verification serves as a key component of risk management. It helps in identifying genuine entities from potential imposters, reducing the likelihood of falling victim to scams or deceptive practices. This proactive approach to risk mitigation showcases your commitment to maintaining a safe and trustworthy online presence of all stakeholders involved.</p>
                                   </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-5">
                            <div class="form-group proceed-btn">
                               
                            </div>
                            <div class="form-group proceed-btn">
                                <a href="{{ route('subscription.plans') }}" class="btn btn-secondary">Proceed</a>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </section>
    
    
<!-- Modal -->
<!--<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">-->
<!--  <div class="modal-dialog" role="document">-->
<!--    <div class="modal-content">-->
<!--      <div class="modal-header">-->
<!--        <h5 class="modal-title" id="exampleModalLabel">Instruction</h5>-->
<!--        <button type="button" class="close" data-dismiss="modal" aria-label="Close">-->
<!--          <span aria-hidden="true">&times;</span>-->
<!--        </button>-->
<!--      </div>-->
<!--      <div class="modal-body">-->
<!--       Use the company bank account for verification payments to proceed.-->
<!--      </div>-->
<!--    </div>-->
<!--  </div>-->
<!--</div>-->
<!--@push('scripts')-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>-->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>-->
<!--<script>-->
<!--    $(document).ready(function(){-->
<!--      $('#exampleModal').modal('show'); -->
<!--    });-->
<!--</script>-->
<!--@endpush-->
@endsection