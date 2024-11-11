<table>
    <thead>
        <tr>
            <th>Enquiry</th>
            <th>Type Of Enquiry</th>
            <th>List Of Participants</th>
            <th>Screening Ratio</th>
            <th>Screening Status</th>
            <th>Bid Rating</th>
            <th>Status</th>
            <th>Enquiry Status</th>
            <th>Conclusion</th>
            <th>Shared To</th>
        </tr>
    </thead>
    <tbody>
        @foreach($enquiries as $enquiry)
        <tr>
            <td>
                {{ $enquiry['reference_no'] }}<br>
                {{ $enquiry['sub_category']['name'] ?? '' }}<br>
                {{ $enquiry['sender']['email'] ?? '' }}<br>
                {{ \Carbon\Carbon::parse($enquiry['created_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($enquiry['created_at'])->format('h:i:s A') }}
            </td>
            <td>{{ $enquiry['is_limited'] == 0 ? 'Normal' : 'Limited Enquiry' }}</td>
            <td>
                @foreach($enquiry['all_replies'] as $reply)
                    {{ $reply['sender']['company']['name'] ?? '' }}<br>
                    {{ \Carbon\Carbon::parse($reply['created_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($reply['created_at'])->format('h:i:s A') }}<br>
                @endforeach
            </td>
            <td>{{ count($enquiry['action_replies']) }} : {{ count($enquiry['all_replies']) }}</td>
            <td>
                @foreach($enquiry['all_replies'] as $reply)
                    @if($reply['status'] == 0 && $reply['is_read'] == 0)
                        {{ $reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null ? 'Recommanded' : 'Unread' }}
                    @elseif($reply['status'] == 0 && $reply['is_read'] == 1)
                        {{ $reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null ? 'Recommanded' : 'TBD' }}
                    @elseif($reply['status'] == 1)
                        {{ $reply['is_selected'] == 1 && $enquiry['shared_to'] !== null ? 'Selected' : 'Shortlisted' }}
                    @elseif($reply['status'] == 2)
                        {{ $reply['is_recommanded'] == 1 && $enquiry['shared_to'] !== null ? 'Recommanded' : 'On Hold' }}
                    @elseif($reply['status'] == 3)
                        Proceed
                    @endif <br>
                @endforeach
            </td>
            <td>
                @foreach($enquiry['all_replies'] as $reply)
                    @if($reply['status'] == 3)
                        Proceed
                    @endif
                @endforeach
            </td>
            <td>
                @if($enquiry['shared_to'])
                    {{ $enquiry['shared']['name'] ?? '' }} at {{ $enquiry['shared']['email'] ?? '' }}<br>
                    {{ \Carbon\Carbon::parse($enquiry['shared_at'])->format('d-m-Y') }} | {{ \Carbon\Carbon::parse($enquiry['shared_at'])->format('h:i:s A') }}
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>