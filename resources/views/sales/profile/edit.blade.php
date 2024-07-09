@extends('sales.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('sales.layouts.header')
    <!-- Header Ends -->

    <!-- Main Content -->
    <section class="container-fluid pleft-77">
        <div class="px-4 py-3">
            <div class="sub-plans-head">
                <h1><a href="{{ route('sales.profile') }}" class="back-btn"></a>Edit Profile</h1>
            </div>
            <form action="{{ route('sales.updateProfile',$user->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="sub-plans-main edit-fields-main">
                    <div class="row">
                            <div class="col-md-4">
                                <div class="row" id="upload-area">
                                    <div class="col-md-6">
                                        <input type="file" id="upload" hidden/>
                                        <label for="upload" class="browse-file">Drag a file or browse
                                            a file to upload</label>
                                    </div>
                                    <div class="col-md-6 d-flex align-items-center justify-content-center">
                                        <div class="uplaod-formats">
                                            Upload Photo
                                            <span>Format: jpeg, jpg, png, gif, svg
                                            Max-Size: 2MB </span>
                                        </div>
                                    </div>
                                </div>
                                <div id="logo-preview" class="d-flex flex-column align-items-left  mt-4 mb-4 {{ !$user->profile_image ? 'd-none' : '' }}">
                                    <span class="d-flex doc-preview align-items-center justify-content-between">
                                        Profile Picture
                                        <div class="d-flex align-items-center">
                                            <a href="{{ !$user->profile_image ? asset($user->profile_image) : '' }}" class="doc-preview-view" target="_blank"></a>
                                        </div>
                                    </span>
                                </div>
                                <div>
                                    <div id="progressBarLogo" style="display: none;">
                                        <div id="progressLogo" style="width: 0%;"></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group position-relative">
                                    <label>Name<span class="mandatory">*</span></label>
                                    <input id="name" name="name" type="text" value="{{ old('name') ?? $user->name ?? '' }}" placeholder="Name" class="form-control website">
                                    <small class="text-danger">@error('name'){{ $message }}@enderror</small>
                                </div>
                                <div class="input-group position-relative">
                                    <label>Email<span class="mandatory">*</span></label>
                                    <input id="email" name="email" type="text" value="{{ old('email') ?? $user->email ?? '' }}" placeholder="Email" class="form-control website">
                                    <small class="text-danger">@error('email'){{ $message }}@enderror</small>
                                </div>
                                <label>Landline</label>
                                <div class="d-flex">
                                    <input type="text" value="{{ $country->phonecode }}"  class="form-control mobile-code" readonly>
                                    <input id="landline" name="landline" type="text" value="{{ old('landline') ?? $user->landline ?? '' }}" placeholder="Landline" class="form-control mobile-number">
                                    <small class="text-danger">@error('landline'){{ $message }}@enderror</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="input-group position-relative">
                                    <label>Designation <span class="mandatory">*</span></label>
                                    <input id="designation" name="designation" type="text" value="{{ old('designation') ?? $user->designation ?? '' }}"  placeholder="Designation" class="form-control website">
                                    <small class="text-danger">@error('designation'){{ $message }}@enderror</small>
                                </div>
                                <label>Mobile</label>
                                <div class="d-flex">
                                    <input id="country_code" name="country_code"  type="text" value="{{ $country->phonecode }}" class="form-control mobile-code" readonly>
                                    <input id="mobile" name="mobile" type="text" value="{{ old('mobile') ?? $user->mobile ?? '' }}" placeholder="Mobile No." class="form-control mobile-number">
                                    <small class="text-danger">@error('mobile'){{ $message }}@enderror</small>
                                </div>
                                <div class="input-group position-relative">
                                    <label>Extension</label>
                                    <input id="extension" name="extension" type="text" value="{{ old('extension') ?? $user->extension ?? '' }}" placeholder="Extension" class="form-control website">
                                    <small class="text-danger">@error('extension'){{ $message }}@enderror</small>
                                </div>
                            </div>
                        </div>  
                        <div class="d-flex justify-content-between justify-content-center mt-5">
                            <div class="already-signup">
                                
                            </div>
                            <div class="form-group proceed-btn">
                                <input type="submit" value="Save" class="btn btn-secondary">
                            </div>
                        </div>
                    </div>
                </form>
        </div>

    </section>
    <!-- End Main Content -->
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" integrity="sha512-uMtXmF28A2Ab/JJO2t/vYhlaa/3ahUOgj1Zf27M5rOo8/+fcTUVH0/E0ll68njmjrLqOBjXM3V9NiPFL5ywWPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        
        // Logo
        var progressBarLogo = document.getElementById('progressBarLogo');
        var progressLogo = document.getElementById('progressLogo');
        var logoPreview = document.getElementById('logo-preview');
        var logoUploadArea = document.getElementById('upload-area');
        
        $('#upload').change(function() {
            var uploadAction = '/sales/update-profile-pic';
            var logoInput = $(this)[0];
            var logo = logoInput.files[0];
            var formData = new FormData();
            formData.append('logo_file', logo);
            

            axios.post(uploadAction, formData, {
                    headers: {
                    'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function(progressEvent) {
                        var percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        progressLogo.style.width = percent + '%';
                    }
                })
                .then((response) => {
                    // Handle success response
                    var logoContent = `<span class="d-flex doc-preview align-items-center justify-content-between">
                                      Company Logo
                                        <div class="d-flex align-items-center">
                                            <a href="${response.data.filepath}" class="doc-preview-view" target="_blank"></a>
                                        </div>
                                    </span>`;
                    logoPreview.classList.remove('d-none');
                    logoPreview.innerHTML = logoContent;
                    progressBarLogo.style.display = 'none';
                    $('#upload').val("");
                })
                .catch((error) => {
                    // Handle error response
                    console.log(error);
                    if (error.response.status == 422) {
                        $.each(error.response.data.errors, function(field, errors) {
                            if(field === 'logo_file'){
                                var logo_error = $('.logo-error');
                                console.log(errors[0]);
                                logo_error.html(errors[0]).show();
                            }
                        });
                    }
                    progressBarLogo.style.display = 'none';
                });
                progressBarLogo.style.display = 'block';
        });
        
    function handleFileSelect(event) {
        // Handle file selection here
        var files = event.target.files;
        // Access selected files from the 'files' variable and process them as needed
    }

    function handleDragOver(event) {
        event.preventDefault();
        event.dataTransfer.dropEffect = "copy";
        // Add any visual indicators or styles to indicate valid drop target
    }

    function handleDragLeave(event) {
        event.preventDefault();
        // Remove any visual indicators or styles when leaving the drop target
    }

    function handleDropLogo(event) {
        event.preventDefault();
        // Handle dropped files here
        var files = event.dataTransfer.files;
        // Access dropped files from the 'files' variable and process them as needed
        
        // Manually trigger file selection for the file input element
        var fileInput = document.getElementById("upload-area");
        fileInput.files = files;
        // Optionally, you can also trigger the 'change' event on the file input element
        var changeEvent = new Event("change");
        fileInput.dispatchEvent(changeEvent);
    }

    });
</script>
  
@endpush


 
@endsection    