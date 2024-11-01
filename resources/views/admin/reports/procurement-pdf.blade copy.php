<html>
<head>
    <title>Procurement</title>
</head>
<body>
<style>

.no-wrap-cell{
    white-space: nowrap !important;
    padding: 10px;
}
table {
    border-collapse: collapse; 
}

.all-border {
    border: 1px solid black; 
    
}

.border-bottom{
    border-top:0px;
    border-left : 0px;
    border-left:0px;
    border-bottom:1px solid #000;
    margin:0;
}

</style>
<div style="margin:5px;border:1px solid #000">
<table>
<thead>
<tr>
    <th class="no-wrap-cell all-border">Date</th>
    <th class="no-wrap-cell all-border">Time</th>
    <th class="no-wrap-cell all-border">Owner</th>
    <th class="no-wrap-cell all-border">Enquiry Reference No.</th>
    <th class="no-wrap-cell all-border">Enquiry Categorized</th>
    <th class="no-wrap-cell all-border">Type Of Enquiry</th>
    <th class="no-wrap-cell all-border">List Of Participants</th>
    <th class="no-wrap-cell all-border">Date And Time</th>
    <th class="no-wrap-cell all-border">Screening Status</th>
    <th class="no-wrap-cell all-border">Screening Ratio</th>
    <th class="no-wrap-cell all-border">Enquiry Status</th>
    <th class="no-wrap-cell all-border">Shared To</th>
    <th class="no-wrap-cell all-border">Bid Rating</th>
    <th class="no-wrap-cell all-border">Conclusion</th>
    <th class="no-wrap-cell all-border">Status</th>
</tr>   
</thead>
<tbody>
@foreach($enquiries as $key => $enquiry)
    <tr>
        <td class="no-wrap-cell all-border">{{ \Carbon\Carbon::parse($enquiry['created_at'])->format('d-m-Y') }}</td>
        <td class="no-wrap-cell all-border">{{ \Carbon\Carbon::parse($enquiry['created_at'])->format('h:i:s A') }}</td>
        <td class="no-wrap-cell all-border">{{ $enquiry['sender']['email'] ?? '' }}</td>
        <td class="no-wrap-cell all-border">{{ $enquiry['reference_no'] }}</td>
        <td class="no-wrap-cell all-border">{{ $enquiry['sub_category']['name'] ?? '' }}</td>
        <td class="no-wrap-cell all-border">{{ $enquiry['is_limited'] == 0 ? 'Normal' : 'Limited Enquiry' }}</td>
        <td class="no-wrap-cell all-border">  
            <table>
                @foreach($enquiry['all_replies'] as $reply)
                    <tr>
                        <td class="no-wrap-cell">{{ $reply['sender']['company']['name'] ?? '' }}</td>
                    </tr>   
                @endforeach
            </table>
        </td>
        <td class="no-wrap-cell all-border">
            <table>
                @foreach($enquiry['all_replies'] as $reply)
                <tr>
                    <td class="no-wrap-cell">{{ \Carbon\Carbon::parse($reply['created_at'])->format('d-m-Y h:i:s A')}}</td>
                </tr>   
                @endforeach
            </table>
        </td>
        <td class="no-wrap-cell all-border">
            <table>
                @foreach($enquiry['all_replies'] as $reply)
                    <tr>
                        <td class="no-wrap-cell">
                            @if($reply['status'] == 0 && $reply['is_read'] == 0)
                                @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                                    {{ 'Recommanded' }}
                                @else
                                    {{ 'Unread' }}
                                @endif
                            @elseif($reply['status'] == 0 && $reply['is_read'] == 1)
                                @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                                    {{ 'Recommanded' }}
                                @else
                                    {{ 'TBD' }} 
                                @endif
                            @elseif($reply['status'] == 1 )         
                                @if($reply['is_selected'] == 1 && $enquiry['shared_to'] !== null)
                                    {{ 'Selected' }}
                                @else
                                    {{  'Shortlisted' }}
                                @endif
                            @elseif($reply['status'] == 2)
                                @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                                    {{  'Recommanded' }}
                                @else
                                    {{ 'On Hold' }}
                            @endif
                            @elseif($reply['status'] == 3)
                                    {{  'Proceed' }}
                            @endif
                        </td>
                    </tr>   
                @endforeach
            </table>
        </td>
        <td class="no-wrap-cell all-border">{{ count($enquiry['action_replies']) }} : {{ count($enquiry['all_replies']) }}</td>
        <td class="no-wrap-cell all-border">
            
        </td>
        <td class="no-wrap-cell all-border">
            @if($enquiry['shared_to'])
            Shared to {{ $enquiry['shared']['name'] ?? '' }} at {{ $enquiry['shared']['email'] ?? '' }} on {{ $enquiry['shared_at'] }}
            @endif
        </td>
        <td class="no-wrap-cell all-border">
        <table>
            @foreach($enquiry['all_replies'] as $reply)
            <tr>
                <td class="no-wrap-cell">
                    @if($reply['status'] == 0 && $reply['is_read'] == 0)
                        @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                                {{ 'Recommanded' }} on {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y h:i:s A') }}
                        @endif
                    @elseif($reply['status'] == 0 && $reply['is_read'] == 1)
                        @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                            {{ 'Recommanded' }} on {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y h:i:s A') }}
                        @endif
                    @elseif($reply['status'] == 1 )         
                        @if($reply['is_selected'] == 1 && $enquiry['shared_to'] !== null)
                            {{ 'Selected' }} on {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y h:i:s A') }}
                        @else
                            {{  'Shortlisted' }} on {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y h:i:s A') }}
                        @endif
                    @elseif($reply['status'] == 2)
                        @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                            {{  'Recommanded' }} on {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y h:i:s A') }}
                        @else
                            {{ 'On Hold' }} on {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y h:i:s A') }}
                        @endif
                    @elseif($reply['status'] == 3)
                        {{  'Proceed' }} on {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y h:i:s A') }}
                @endif
                </tr>
            @endforeach  
            </table>  
        </td>
        <td class="no-wrap-cell all-border">
            <table>
            @foreach($enquiry['all_replies'] as $reply)
                <tr>
                    <td class="no-wrap-cell">
                        @if($reply['status'] == 3)
                            {{  'Proceed' }}
                        @endif
                    </td>
                </tr>
            @endforeach  
        </table>      
        </td>
        <td class="no-wrap-cell all-border"></td>
    </tr>
@endforeach                  
</tbody>
</table>
    </div>
</html>