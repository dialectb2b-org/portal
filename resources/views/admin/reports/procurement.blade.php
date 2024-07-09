@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" integrity="sha512-uMtXmF28A2Ab/JJO2t/vYhlaa/3ahUOgj1Zf27M5rOo8/+fcTUVH0/E0ll68njmjrLqOBjXM3V9NiPFL5ywWPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<style>

.no-wrap-cell{
    white-space: nowrap !important;
}
</style>
     <!-- Main Content Start -->

    <!-- Team List Section Start-->
    <section class="container-fluid pleft-77">
          <div class="px-4 py-3">
                
                <div class="sub-plans-head-admin mt-2 mb-3 d-flex align-items-center justify-content-between">
                    <div>
                        <h1><a href="{{ route('admin.dashboard') }}" class="back-btn"></a>Procurement Activity Report</h1>
                    </div>
                    <form action="{{ route('admin.procurement.reportpdf') }}" method="post">
                        @csrf
                        <input type="hidden" name="daterangeprocurement" value="{{ $dateRangeInput ?? '' }}" />
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
                                <th scope="col">Owner</th>
                                <th scope="col">Enquiry Ref No.</th>
                                <th scope="col">Enquiry Categorized</th>
                                <th scope="col">Type Of Enquiry</th>
                                <th scope="col">List Of Participants</th>
                                <th scope="col">Date And Time</th>
                                <th scope="col">Screening Status</th>
                                <th scope="col">Screening Ratio</th>
                                <th scope="col">Shared To</th>
                                <th scope="col">Bid Rating</th>
                                <th scope="col">Status</th>
                            </tr>    
      </thead>
                            <tbody>
                                @foreach($enquiries as $key => $enquiry)
                                    <tr>
                                        <td class="no-wrap-cell">{{ \Carbon\Carbon::parse($enquiry->created_at)->format('d-m-Y') }}</td>
                                        <td class="no-wrap-cell">{{ \Carbon\Carbon::parse($enquiry->created_at)->format('h:i:s A') }}</td>
                                        <td class="no-wrap-cell">{{ $enquiry->sender->email ?? '' }}</td>
                                        <td class="no-wrap-cell">{{ $enquiry->reference_no }}</td>
                                        <td class="no-wrap-cell">{{ $enquiry->sub_category->name ?? '' }}</td>
                                        <td class="no-wrap-cell">{{ $enquiry->is_limited == 0 ? 'Normal' : 'Limited Enquiry' }}</td>
                                        <td class="no-wrap-cell">  
                                            <table>
                                            @foreach($enquiry->all_replies as $reply)
                                            <tr>
                                               <td class="no-wrap-cell">{{ $reply->sender->company->name }}</td>
                                            </tr>   
                                            @endforeach
                                            </table>
                                        </td>
                                        <td class="no-wrap-cell">
                                            <table>
                                                @foreach($enquiry->all_replies as $reply)
                                                <tr>
                                                    <td class="no-wrap-cell">{{ \Carbon\Carbon::parse($reply->created_at)->format('d-m-Y h:i:s A')}}</td>
                                                </tr>   
                                                @endforeach
                                            </table>
                                        </td>
                                        <td>
                                            <table>
                                                @foreach($enquiry->all_replies as $reply)
                                                <tr>
                                                <td class="no-wrap-cell">
                                                    @if($reply->status == 0 && $reply->is_read == 0)
                                                        @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                                {{ 'Recommanded' }}
                                                        @else
                                                            {{ 'Unread' }}
                                                        @endif
                                                    @elseif($reply->status == 0 && $reply->is_read == 1)
                                                        @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                            {{ 'Recommanded' }}
                                                        @else
                                                            {{ 'TBD' }} 
                                                        @endif
                                                    @elseif($reply->status == 1 )         
                                                        @if($reply->is_selected == 1 && $reply->shared_to !== null)
                                                            {{ 'Selected' }}
                                                        @else
                                                            {{  'Shortlisted' }}
                                                        @endif
                                                    @elseif($reply->status == 2)
                                                        @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                            {{  'Recommanded' }}
                                                        @else
                                                            {{ 'On Hold' }}
                                                        @endif
                                                    @elseif($reply->status == 3)
                                                        {{  'Proceed' }}
                                                @endif
                                                </td>
                                                </tr>   
                                                @endforeach
                                            </table>
                                        </td>
                                        <td class="no-wrap-cell">{{ count($enquiry->action_replies) }} : {{ count($enquiry->all_replies) }}</td>
                                        <td class="no-wrap-cell">
                                            @if($enquiry->shared_to)
                                            Shared to {{ $enquiry->shared->name ?? '' }} at {{ $enquiry->shared->email ?? '' }} on {{ $enquiry->shared_at }}
                                            @endif
                                        </td>
                                        <td class="no-wrap-cell">
                                        <table>
                                        @foreach($enquiry->all_replies as $reply)
                                        <tr>
                                            <td class="no-wrap-cell">
                                                @if($reply->status == 0 && $reply->is_read == 0)
                                                    @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                            {{ 'Recommanded' }} on {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y h:i:s A') }}
                                                    @endif
                                                @elseif($reply->status == 0 && $reply->is_read == 1)
                                                    @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                        {{ 'Recommanded' }} on {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y h:i:s A') }}
                                                    @endif
                                                @elseif($reply->status == 1 )         
                                                    @if($reply->is_selected == 1 && $reply->shared_to !== null)
                                                        {{ 'Selected' }} on {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y h:i:s A') }}
                                                    @else
                                                        {{  'Shortlisted' }} on {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y h:i:s A') }}
                                                    @endif
                                                @elseif($reply->status == 2)
                                                    @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                        {{  'Recommanded' }} on {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y h:i:s A') }}
                                                    @else
                                                        {{ 'On Hold' }} on {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y h:i:s A') }}
                                                    @endif
                                                @elseif($reply->status == 3)
                                                    {{  'Proceed' }} on {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y h:i:s A') }}
                                            @endif
                                            </tr>
                                        @endforeach  
                                        </table>  
                                        </td>
                                        <td>
                                            <table>
                                            @foreach($enquiry->all_replies as $reply)
                                                <tr>
                                                    <td class="no-wrap-cell">
                                                        @if($reply->status == 3)
                                                            {{  'Proceed' }}
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach  
                                        </table>      
                                        </td>
                                    </tr>
                                @endforeach                  
                            </tbody>
                        </table>
                        </div>
                        </div>
                        </div>
    <!-- Team List Section End -->



</section>
<!-- Main Content Ends -->


    @endsection