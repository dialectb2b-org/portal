<html>
<head>
    <title>Sales Activity Report</title>
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
    <div style="margin:5px">
        <table>
            <thead>
                <tr>
                    <th class="no-wrap-cell all-border">Date</th>
                    <th class="no-wrap-cell all-border">Time</th>
                    <th class="no-wrap-cell all-border">Enquiry Received</th>
                    <th class="no-wrap-cell all-border">Enquiry Categorized</th>
                    <th class="no-wrap-cell all-border">Type of Enquiry</th>
                    <th class="no-wrap-cell all-border">Enquiry Status</th>
                </tr>   
            </thead>
            <tbody>
                @foreach($enquiries as $key => $enquiry)
                    <tr>
                        <td class="no-wrap-cell all-border">{{ \Carbon\Carbon::parse($enquiry->created_at)->format('d-m-Y') }}</td>
                        <td class="no-wrap-cell all-border">{{ \Carbon\Carbon::parse($enquiry->created_at)->format('h-i-s A') }}</td>
                        <td class="no-wrap-cell all-border">{{ $enquiry->company_name ?? '' }}</td>
                        <td class="no-wrap-cell all-border">{{ $enquiry->category_name }}</td>
                        <td class="no-wrap-cell all-border">{{ $enquiry->is_limited == 0 ? 'Normal' : 'Limited Enquiry' }}</td>
                        <td class="no-wrap-cell all-border"></td>
                    </tr>
                @endforeach                  
            </tbody>
        </table>
    </div>
</html>
