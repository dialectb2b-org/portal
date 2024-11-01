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

.report-company-name{
  font-weight: 600;
  padding-left: 17px;
  border-left: 1px solid #707070;
  margin-left: 20px;
  padding-top: 5px;
}
.report-main-tbl{
  width: 100%;
  white-space: nowrap;
}
.report-main-tbl td{
  color: #071D49;
  font-weight: 500;
  line-height: 20px;
}
.report-main-tbl th{
  color: #5F6F87;
  background: #DFE0E6;
  font-size: 15px;
  padding: 15px;
}

.report-main-tbl td{
  padding: 15px;
}
.report-main-tbl h2{
  font-size: 20px;
  padding: 0px 0px 5px 0;
}

.report-main-tbl h3{
  font-size: 16px;
  margin-bottom: 3px;
}

.report-main-tbl td span{
  margin-bottom: 10px;
  display: block;
}
.report-main-tbl td{
  font-size: 15px;
}
.table-responsive{
  padding: 0;
}

</style>
<div style="margin:5px;border:1px solid #000" class="table-responsive">
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
    <th scope="col">Enquiry Status</th>
    <th scope="col">Conclusion</th>
    <th scope="col">Shared To</th>
</tr>   
</thead>
<tbody>
    @foreach($enquiries as $key => $enquiry)
    <tr>
        <td scop="row">
            <h2>{{ $enquiry['reference_no'] }}</h2>
            <h3>{{ $enquiry['sub_category']['name'] ?? '' }}</h3>
            {{ $enquiry['sender']['email'] ?? '' }}<br>
            {{ \Carbon\Carbon::parse($enquiry['created_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($enquiry['created_at'])->format('h:i:s A') }}
        </td>
        <td scope="row"><h3>{{ $enquiry['is_limited'] == 0 ? 'Normal' : 'Limited Enquiry' }}</h3></td>
        <td scope="row">  
            @foreach($enquiry['all_replies'] as $reply)  
            <span>
                <h3>{{ $reply['sender']['company']['name'] ?? '' }}</h3>
                {{ \Carbon\Carbon::parse($reply['created_at'])->format('d-m-Y')}} | {{ \Carbon\Carbon::parse($reply['created_at'])->format('h:i:s A')}}
                </span>
            @endforeach
        </td>
        <td class="no-wrap-cell">{{ count($enquiry['action_replies']) }} : {{ count($enquiry['all_replies']) }}</td>
        <td>
            @foreach($enquiry['all_replies'] as $reply)
            <span>
                <h3>
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
                </h3>
                -
            </span>
            @endforeach
        </td>
        <td class="no-wrap-cell">
        @foreach($enquiry['all_replies'] as $reply)
            <span>
                @if($reply['status'] == 0 && $reply['is_read'] == 0)
                    @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                    <h3>{{ 'Recommanded' }} on</h3> 
                    {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply['updated_at'])->format('h:i:s A') }}
                    @endif
                @elseif($reply['status'] == 0 && $reply['is_read'] == 1)
                    @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                    <h3>{{ 'Recommanded' }} on</h3>
                    {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply['updated_at'])->format('h:i:s A') }}
                    @endif
                @elseif($reply['status'] == 1 )         
                    @if($reply['is_selected'] == 1 && $enquiry['shared_to'] !== null)
                    <h3>{{ 'Selected' }} on</h3>
                    {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply['updated_at'])->format('h:i:s A') }}
                    @else
                    <h3>{{  'Shortlisted' }} on</h3>
                    {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply['updated_at'])->format('h:i:s A') }}
                    @endif
                @elseif($reply['status'] == 2)
                    @if($reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null)
                    <h3>{{  'Recommanded' }} on</h3>
                    {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply['updated_at'])->format('h:i:s A') }}
                    @else
                    <h3>{{ 'On Hold' }} on</h3>
                    {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply['updated_at'])->format('h:i:s A') }}
                    @endif
                @elseif($reply['status'] == 3)
                    <h3>{{  'Proceed' }} on</h3>
                    {{ \Carbon\Carbon::parse($reply['updated_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply['updated_at'])->format('h:i:s A') }}
                @else
                    <h3>-</h3>-
                @endif
            </span>
        @endforeach  
        </td>
        <td>
        @foreach($enquiry['all_replies'] as $reply)
            <span>
                @if($reply['status'] == 3)
                    <h3>{{  'Proceed' }}</h3>
                @endif
            </span>
        @endforeach
        </td>
        <td class="no-wrap-cell">
            <span>
                @if($enquiry['shared_to'])
                <h3> {{ $enquiry['shared']['name'] ?? '' }} at {{ $enquiry['shared']['email'] ?? '' }} </h3>
                {{ \Carbon\Carbon::parse($enquiry['shared_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($enquiry['shared_at'])->format('h:i:s A') }}
                @endif
            </span>
        </td>
    </tr>
    @endforeach                  
</tbody>
</table>
    </div>
</html>