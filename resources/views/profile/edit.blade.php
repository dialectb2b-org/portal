@php
   $role = auth()->user()->role;
   if($role == 1){
       $extends = 'admin.layouts.app';
       $header = 'admin.layouts.header';
   }
   else if($role == 2){
       $extends = 'procurement.layouts.app';
       $header = 'procurement.layouts.header';
   }
   else if($role == 3){
       $extends = 'sales.layouts.app';
       $header = 'sales.layouts.header';
   }
   else if($role == 4){
       $extends = 'member.layouts.app';
       $header = 'member.layouts.header';
   }
@endphp

@extends($extends)
@section('content')
    <!-- Header Starts -->
        @include($header)
    <!-- Header Ends -->
    <!-- Main Content -->
    <style>
 
        .profile-container {
            text-align: center;
            position: relative;
            display: inline-block;
        }

        #profile-image {
            position: relative;
            width: 250px;
            height: 250px;
            overflow: hidden;
            border-radius: 50%;
        }

        #profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        #edit-button-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        #profile-image:hover #edit-button-overlay {
            opacity: 1;
        }

        #edit-button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            padding: 8px 16px;
        }

        #edit-icon {
            margin-right: 8px;
        }

        .edit-input {
            display: none;
        }
    </style>
    <section class="container-fluid pleft-77">
        <div class="px-4 py-3">
            <div class="sub-plans-head">
                <h1><a href="{{ route('profile.index') }}" class="back-btn"></a>Edit Profile</h1>
            </div>
            <form action="{{ route('profile.update') }}" method="post">
                @csrf
                <input id="staff_id" type="hidden"  value="{{ $user->id }}" />
                <div class="sub-plans-main edit-fields-main">
                    <div class="row">
                        <div class="col-md-4 col-sm-12">
                            <div class="profile-container">
                                <div id="profile-image">
                                    <img src="{{ asset($user->profile_image)  }}" alt="Profile Image">
                                    <div id="edit-button-overlay">
                                        <button id="edit-button" type="button">
                                            <i id="edit-icon" class="fas fa-edit"></i>
                                            Edit Image
                                        </button>
                                    </div>
                                </div>
                                <input type="file" id="upload" class="edit-input" accept="image/*" onchange="updateProfileImage(this)">
                                <span>Format: jpeg, jpg, png, gif, svg  <br> Max-Size: 2MB </span>
                            </div>
                            
                            <div id="progressBarLogo" style="display: none;">
                                <div id="progressLogo" style="width: 0%;"></div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group position-relative">
                                        <label>Name<span class="mandatory">*</span></label>
                                        <input id="name" name="name" type="text" value="{{ old('name') ?? $user->name ?? '' }}" placeholder="Name" class="form-control website">
                                        <small class="text-danger">@error('name'){{ $message }}@enderror</small>
                                    </div>
                                </div>    
                                <div class="col-md-12">
                                    <div class="input-group position-relative">
                                        <label>Email<span class="mandatory">*</span></label>
                                        <input id="email" name="email" type="text" value="{{ old('email') ?? $user->email ?? '' }}" placeholder="Email" class="form-control website" readonly>
                                        <small class="text-danger">@error('email'){{ $message }}@enderror</small>
                                    </div>
                                </div>  
                                <div class="col-md-12">    
                                    <label>Landline</label>
                                    <div class="d-flex">
                                        <input type="text" value="{{ $country->phonecode }}"  class="form-control mobile-code" readonly>
                                        <input id="landline" name="landline" type="text" value="{{ old('landline') ?? $user->landline ?? '' }}" placeholder="Landline" class="form-control mobile-number">
                                        <small class="text-danger">@error('landline'){{ $message }}@enderror</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group position-relative">
                                        <label>Designation <span class="mandatory">*</span></label>
                                        <input id="designation" name="designation" type="text" value="{{ old('designation') ?? $user->designation ?? '' }}"  placeholder="Designation" class="form-control website">
                                        <small class="text-danger">@error('designation'){{ $message }}@enderror</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label>Mobile</label>
                                    <div class="d-flex">
                                        <input id="country_code" name="country_code"  type="text" value="{{ $country->phonecode }}" class="form-control mobile-code" readonly>
                                        <input id="mobile" name="mobile" type="text" value="{{ old('mobile') ?? $user->mobile ?? '' }}" placeholder="Mobile No." class="form-control mobile-number">
                                        <small class="text-danger">@error('mobile'){{ $message }}@enderror</small>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-group position-relative">
                                        <label>Extension</label>
                                        <input id="extension" name="extension" type="text" value="{{ old('extension') ?? $user->extension ?? '' }}" placeholder="Extension" class="form-control website">
                                        <small class="text-danger">@error('extension'){{ $message }}@enderror</small>
                                    </div>
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

        // Function to open file input when clicking on the profile image
        function openFileInput() {
            $('.edit-input').click();
        }

        // Function to update profile image on file selection
        function updateProfileImage(input) {
            const file = input.files[0];

            if (file) {
                const formData = new FormData();
                formData.append('logo_file', file);

                const apiUrl = '/profile/update-profile-pic';

                axios.post(apiUrl, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function (progressEvent) {
                        // Handle upload progress if needed
                        const percent = Math.round((progressEvent.loaded * 100) / progressEvent.total);
                        console.log('Upload Progress: ' + percent + '%');
                    }
                })
                    .then(response => {
                        // Update the profile image on the front end
                        $('#profile-image').find('img').attr('src', response.data.filepath);
                    })
                    .catch(error => {
                        // Handle error response
                        console.error('Error uploading image:', error);
                    });
            }
        }

        // Attach event listeners
        $('#profile-image').on('click', openFileInput);
        $('.edit-input').on('change', function () {
            updateProfileImage(this);
        });
    });
</script>
  
@endpush
 
@endsection    