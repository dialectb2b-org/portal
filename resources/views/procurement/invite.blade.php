@extends('procurement.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('procurement.layouts.header')
    <!-- Header Ends -->

    <!-- Main Content -->
    <div class="container-fluid reg-bg2">
        <section class="container">
            <div class="row registration">
                <h1>Invite Team Member</h1>
                <section class="reg-content-main">
                    <div class="row">
                          <div class="d-flex align-item-center justify-content-center">
                              <p class="m-4">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed consequat imperdiet diam eget porta</p>
                           </div>
                           <div class="d-flex align-item-center justify-content-center">
                              <div class="row">
                                  <form action="{{ route('sendInvite') }}" method="post">
                                      @csrf
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label for="name">Team Member's Name:</label>
                                              <input type="text" id="name" name="name" class="form-control" required>
                                          </div>
                                      </div>
                                      <div class="col-md-12">
                                          <div class="form-group">
                                              <label for="email">Team Member's Email:</label>
                                              <input type="email" id="email" name="email" class="form-control" required>
                                          </div>
                                      </div>
                                      <div class="col-md-12">
                                         <button type="submit" class="btn btn-primary m-4">Send Invitation</button>
                                      </div>
                                  </form>
                              </div>
                          </div>
                    </div>
                </section>
            </div>   
        </section>
    </div>
@endsection