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
    <div class="table-responsive" style="margin:5px">
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
                @foreach($enquiries as $key => $enquiry)
                    <tr>
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
                              @else
                                  {{ $enquiry->expired_at > now() ? 'Expired' : 'Open' }}
                              @endif
                          </td>
                        </tr>
                    </tr>
                @endforeach                  
            </tbody>
        </table>
    </div>
</html>
