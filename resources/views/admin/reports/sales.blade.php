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
                        <input type="button" value="Back" class="btn btn-third"
                            onclick="window.location.href = '{{ route('admin.dashboard') }}'">
                        <input type="submit" value="Send" class="btn btn-secondary">
                    </div>
                </form>
            </div>
            <div class="table-responsive" style="max-height: 14cm;">
                <table class="table report-main-tbl">
                    <thead>
                        <tr>
                            <th scope="row">Enquiry Received</th>
                            <th scope="row">Enquiry Categorized</th>
                            <th scope="row">Type of Enquiry</th>
                            <th scope="row">Enquiry Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($enquiries as $key => $enquiry)
                            <tr>
                                <td>
                                    <span>
                                        <h3>{{ $enquiry->company_name ?? '' }}</h3>
                                        {{ \Carbon\Carbon::parse($enquiry->created_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($enquiry->created_at)->format('h-i-s A') }}
                                    </span>
                                </td>
                                <td>{{ $enquiry->category_name }}</td>
                                <td>{{ $enquiry->is_limited == 0 ? 'Normal' : 'Limited Enquiry' }}</td>
                                <td>
                                    @if ($enquiry->is_replied == 1)
                                        {{ 'Replied' }}
                                    @elseif ($enquiry->is_overlap == 1)
                                        {{ '<span title="This Company Has Superseded">Invalid</span>' }}
                                    @else
                                        {{ $enquiry->expired_at < now() ? 'Expired' : 'Open' }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Team Content Section End -->

    </section>

    <script>
        // Show the modal if there's a success message
        document.addEventListener('DOMContentLoaded', function () {
            @if(isset($toEmail))
                Swal.fire({
                    title: 'Report Forwarded',
                    text: 'The report has been successfully forwarded to {{ $toEmail }}',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonText: 'Back to Dashboard',
                    cancelButtonText: 'Ok'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location = "{{ url('/admin/dashboard') }}";
                    }
                });
            @endif
        });
    </script>
    <!-- Main Content Ends -->
