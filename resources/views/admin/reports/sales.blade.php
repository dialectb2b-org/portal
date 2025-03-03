@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->


     <!-- Main Content Start -->

    <!-- Team List Section Start-->
    <section class="container-fluid pleft-77">
      
    <div class="px-4 py-3">
                
                <div class="sub-plans-head-admin mt-2 mb-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h1><a href="{{ route('admin.dashboard') }}" class="back-btn"></a>Sales Activity Report</h1>
                    </div>
                    <form action="{{ route('admin.sales.reportpdf') }}" method="post">
                        @csrf
                        <input type="hidden" name="daterangesales" value="{{ $dateRangeInput ?? '' }}" />
                        <div class="form-group">
                            <input type="button" value="Back" class="btn btn-third" onclick="window.location.href = '{{ route('admin.dashboard') }}'">
                            <input type="submit" value="Send" class="btn btn-secondary" >
                        </div>
                    </form>
                </div>
                <div class="reports-main ">
                    <div class="table-responsive">
                    <table class="table tbl-report mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Time</th>
                                    <th scope="col">Enquiry Received</th>
                                    <th scope="col">Enquiry Categorized</th>
                                    <th scope="col">Type of Enquiry</th>
                                    <th scope="col">Enquiry Status</th>
                                </tr>   
                            </thead>
                            <tbody>
                                @foreach($enquiries as $key => $enquiry)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($enquiry->created_at)->format('d-m-Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($enquiry->created_at)->format('h-i-s A') }}</td>
                                        <td>{{ $enquiry->company_name ?? '' }}</td>
                                        <td>{{ $enquiry->category_name }}</td>
                                        <td>{{ $enquiry->is_limited == 0 ? 'Normal' : 'Limited Enquiry' }}</td>
                                        <td>@if($enquiry->is_replied == 1) 
                                              {{ 'Replied' }}
                                            @else
                                              {{ $enquiry->expired_at > now() ? 'Expired' : 'Open' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach                  
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

<!-- Team Content Section End -->

</section>
<!-- Main Content Ends -->

  