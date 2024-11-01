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
                <div class="">
                    <div class="table-responsive" style="max-height: 15cm;">
                    <table class="table report-main-tbl">
                        <thead>
                            <tr>
                                <th scope="col">Enquiry</th>
                                <th scope="col">Type Of Enquiry</th>
                                <th scope="col">List Of Participants</th>
                                <th scope="col">Screening Ratio</th>
                                <th scope="col">Screening Status</th>
                                <th scope="col">Bid Rating</th>
                                <th scope="col">Status</th>
                                <th scope="col">Shared To</th>
                            </tr>    
      </thead>
                            <tbody>
                                @foreach($enquiries as $key => $enquiry)
                                    <tr>
                                        <td scop="row">
                                            <h2>{{ $enquiry->reference_no }}</h2>
                                            <h3>{{ $enquiry->sub_category->name ?? '' }}</h3>
                                            {{ $enquiry->sender->email ?? '' }}<br>
                                            {{ \Carbon\Carbon::parse($enquiry->created_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($enquiry->created_at)->format('h:i:s A') }}
                                        </td>
                                        <td scope="row"><h3>{{ $enquiry->is_limited == 0 ? 'Normal' : 'Limited Enquiry' }}</h3></td>
                                        <td scope="row">  
                                            @foreach($enquiry->all_replies as $reply)  
                                            <span>
                                                <h3>{{ $reply->sender->company->name }}</h3>
                                                {{ \Carbon\Carbon::parse($reply->created_at)->format('d-m-Y')}} | {{ \Carbon\Carbon::parse($reply->created_at)->format('h:i:s A')}}
                                               </span>
                                            @endforeach
                                        </td>
                                        <td class="no-wrap-cell">{{ count($enquiry->action_replies) }} : {{ count($enquiry->all_replies) }}</td>
                                        <td>
                                            @foreach($enquiry->all_replies as $reply)
                                            <span>
                                                <h3>
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
                                                </h3>
                                                -
                                            </span>
                                            @endforeach
                                        </td>
                                        <td class="no-wrap-cell">
                                        @foreach($enquiry->all_replies as $reply)
                                            <span>
                                                @if($reply->status == 0 && $reply->is_read == 0)
                                                    @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                    <h3>{{ 'Recommanded' }} on</h3> 
                                                    {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply->updated_at)->format('h:i:s A') }}
                                                    @endif
                                                @elseif($reply->status == 0 && $reply->is_read == 1)
                                                    @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                    <h3>{{ 'Recommanded' }} on</h3>
                                                    {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply->updated_at)->format('h:i:s A') }}
                                                    @endif
                                                @elseif($reply->status == 1 )         
                                                    @if($reply->is_selected == 1 && $reply->shared_to !== null)
                                                    <h3>{{ 'Selected' }} on</h3>
                                                    {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply->updated_at)->format('h:i:s A') }}
                                                    @else
                                                    <h3>{{  'Shortlisted' }} on</h3>
                                                    {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply->updated_at)->format('h:i:s A') }}
                                                    @endif
                                                @elseif($reply->status == 2)
                                                    @if($reply->is_recommanded == 1 && $reply->shared_to !== null)
                                                    <h3>{{  'Recommanded' }} on</h3>
                                                    {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply->updated_at)->format('h:i:s A') }}
                                                    @else
                                                    <h3>{{ 'On Hold' }} on</h3>
                                                    {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply->updated_at)->format('h:i:s A') }}
                                                    @endif
                                                @elseif($reply->status == 3)
                                                    <h3>{{  'Proceed' }} on</h3>
                                                    {{ \Carbon\Carbon::parse($reply->updated_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply->updated_at)->format('h:i:s A') }}
                                                @else
                                                    <h3>-</h3>-
                                                @endif
                                            </span>
                                        @endforeach  
                                        </td>
                                        <td>
                                        @foreach($enquiry->all_replies as $reply)
                                            <span>
                                                @if($reply->status == 3)
                                                    <h3>{{  'Proceed' }}</h3>
                                                @endif
                                            </span>
                                        @endforeach
                                        </td>
                                        <td class="no-wrap-cell">
                                            <span>
                                                @if($enquiry->shared_to)
                                                <h3> {{ $enquiry->shared->name ?? '' }} at {{ $enquiry->shared->email ?? '' }} </h3>
                                                {{ \Carbon\Carbon::parse($enquiry->shared_at)->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($enquiry->shared_at)->format('h:i:s A') }}
                                                @endif
                                            </span>
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