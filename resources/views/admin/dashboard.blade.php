@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->

    <section class="container-fluid pleft-77">
            <div class="row px-4 py-1 pt-4">
                @if($company->is_verified === 0)
                    <div class="get-verify-badge d-flex  align-items-center justify-content-between">
                        <p>A verified badge on Dialectb2b.com enhances credibility and trust, fostering stronger connections and boosting industry reputation.</p>
                        <div>
                            <a href="{{ route('admin.paymentVerification.info') }}" class="px-4">Get Verified Now</a>
                            <button type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                        </div>
                    </div>
                    @elseif($company->is_verified === 2)
                    <div class="get-verify-badge d-flex  align-items-center justify-content-between">
                        <p>Account Verification Under Review!</p>
                        <div>
                            <button type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                        </div>
                    </div>
                @endif
                <div class="col-md-9">
                    <div class="row p-4 dash-blocks">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h1>Procurement</h1>
                                <a href="{{ route('admin.staff.edit',$procurement->id) }}" class="team-edit"><img src="{{ asset('assets/images/team-edit-ico.svg') }}"></a>
                            </div>

                            <div class="d-flex">
                                <div class="tumb-img d-flex align-items-center justify-content-center"><img src="{{ $procurement->profile_image != '' ? asset($procurement->profile_image) : ''  }}"></div>
                                <div class="sale-prof-name">
                                    <h2>{{ $procurement->name ?? '' }}</h2>
                                    <span>{{ $procurement->designation ?? '' }}</span><br> 
                                    @if($procurement->password != '')
                                    <input type="checkbox" name="" id="aa1" class="add-page-check enableDisable" data-id="{{ $procurement->id }}" {{ $procurement->status == 1 ? 'checked' : ' ' }}>
                                    <label for="aa1" class="d-flex"><span></span></label>
                                    @else
                                    <span class="text-danger">Activation Pending!</span>
                                    @endif
                                </div>
                                
                            </div>

                                <div class="sale-prof-detail">
                                    <label>Email</label>
                                    {{ $procurement->email ?? '' }}
                                </div>

                                <div class="sale-prof-detail">
                                    <label>Phone</label>
                                    {{ $procurement->mobile ?? '' }}
                                </div>

                                <div class="sale-prof-detail d-flex align-items-center justify-content-between">
                                    <div><label>Landline</label>
                                    {{ $procurement->landline ?? '' }}</div>

                                    <div>
                                        <label>Extension</label>
                                        {{ $procurement->extension ?? '' }}
                                    </div>
                                </div>
                            
                        </div>
                        <div class="col-md-9">
                            <div class="row bg-grey pt-4 pb-4 px-2">
                              
                             <form action="{{ route('admin.procurement.report') }}" method="post">
                                    @csrf
                                <div class="col-md-12 d-flex justify-content-end pb-4">
                                        {{-- <input class="date-print-sec d-flex align-items-center " type="text" name="daterangeprocurement" value="" /> --}}
                                        <input class="date-print-sec d-flex align-items-center" type="text" id="start_date" name="start_date" placeholder="Start Date" />
                                        <input class="date-print-sec d-flex align-items-center ml-1" type="text" id="end_date" name="end_date" placeholder="End Date" />
                                       
                                    
                                   
                                    <div class="form-group">
                                        <input type="submit" value="Print Report" class="btn btn-secondary-small" onclick="window.location.href = '';">
                                    </div>
                                    
                                </div>
</form>
                                <div class="col-md-4 text-center position-relative  d-flex flex-column align-items-center justify-content-center ">
                                     <div id="procurement_chart"></div>   
                                <!-- <div class="piechart d-flex align-items-center justify-content-center">
                                        <div class="piechart-middle d-flex align-items-center justify-content-center">31</div>
                                    </div> -->
                                   Request For Quotation
                                </div>
                                <div class="col-md-8">
                                    <div class="counts-white">
                                        <div class="row">
                                            <div class="col-md-4 counts-main">
                                                <span id="procurement-open" class="green-txt">{{ str_pad($openPro, 2, '0', STR_PAD_LEFT) }}</span>
                                                <label>No. of RFQ Sent</label>
                                            </div>
                                            <div class="col-md-4 counts-main">
                                                <span id="procurement-closed" class="blue-txt">{{ str_pad($closedPro, 2, '0', STR_PAD_LEFT) }}</span>
                                                <label>No of RFQ in Progress</label>
                                            </div>
                                            <div class="col-md-4 counts-main">
                                                <span id="procurement-expired" class="orange-txt">{{ str_pad($expiredPro, 2, '0', STR_PAD_LEFT) }}</span>
                                                <label>No of RFQ Completed</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                     @if($sales)
                    <div class="row p-4 dash-blocks">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h1>Sales</h1>
                                <a href="{{ route('admin.staff.edit',$sales->id) }}" class="team-edit"><img src="{{ asset('assets/images/team-edit-ico.svg') }}"></a>
                            </div>

                            <div class="d-flex">
                                <div class="tumb-img d-flex align-items-center justify-content-center"><img src="{{ $sales->profile_image != '' ? asset($sales->profile_image) : ''  }}"></div>
                                <div class="sale-prof-name">
                                    <h2>{{ $sales->name ?? '' }}</h2>
                                    <span>{{ $sales->designation ?? '' }}</span>
                                    <br>
                                    @if($sales->password != '')
                                    <input type="checkbox" name="" id="aa2" class="add-page-check enableDisable" data-id="{{ $sales->id }}" {{ $sales->status == 1 ? 'checked' : ' ' }}>
                                    <label for="aa2" class="d-flex"><span></span></label>
                                    @else
                                    <span class="text-danger">Activation Pending!</span>
                                    @endif
                                </div>
                            </div>

                                <div class="sale-prof-detail">
                                    <label>Email</label>
                                    {{ $sales->email ?? '' }}
                                </div>

                                <div class="sale-prof-detail">
                                    <label>Phone</label>
                                    {{ $sales->mobile ?? '' }}
                                </div>

                                <div class="sale-prof-detail d-flex align-items-center justify-content-between">
                                    <div><label>Landline</label>
                                    {{ $sales->landline ?? '' }}</div>

                                    <div>
                                        <label>Extension</label>
                                        {{ $sales->extension ?? '' }}
                                    </div>
                                </div>
                            
                        </div>
                        <div class="col-md-9">
                            <div class="row bg-grey pt-4 pb-4 px-2">
                                <form action="{{ route('admin.sales.report') }}" method="post">
                                    @csrf
                                    <div class="col-md-12 d-flex justify-content-end pb-4">
                                        <div class="d-flex">
                                            {{-- <input class="date-print-sec d-flex align-items-center " type="text" name="daterangesales" value="" /> --}}
                                            <input class="date-print-sec d-flex align-items-center" type="text" id="start_date_sales" name="start_date_sales" placeholder="Start Date" />
                                            <input class="date-print-sec d-flex align-items-center ml-2" type="text" id="end_date_sales" name="end_date_sales" placeholder="End Date" />
                                        </div>
                                       
                                        <div class="form-group">
                                            <input type="submit" value="Print Report" class="btn btn-secondary-small">
                                        </div>
    
                                    </div>
                                </form>

                                <div class="col-md-4 text-center position-relative  d-flex flex-column align-items-center justify-content-center ">
                                    <div id="sales_chart"></div>
                                    <!-- <div class="piechart d-flex align-items-center justify-content-center">
                                        <div class="piechart-middle d-flex align-items-center justify-content-center">14</div>
                                    </div> -->
                                     Enquiries
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="counts-white">
                                        <div class="row">
                                            <div class="col-md-4 counts-main">
                                                <span id="sales-open" class="green-txt">{{ str_pad($openSale, 2, '0', STR_PAD_LEFT) }}</span>
                                                <label>No. of Enquiries Received</label>
                                            </div>
                                            <div class="col-md-4 counts-main">
                                                <span id="sales-closed" class="blue-txt">{{ str_pad($closedSale, 2, '0', STR_PAD_LEFT) }}</span>
                                                <label>No. of Enquiries Responded</label>
                                            </div>
                                            <div class="col-md-4 counts-main">
                                                <span id="sales-expired" class="orange-txt">{{ str_pad($expiredSale, 2, '0', STR_PAD_LEFT) }}</span>
                                                <label>No. of Enquiries Expired</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="row p-4 dash-blocks">  
                        <div class="d-flex align-items-center justify-content-between">
                            <h1>Add Sales Account</h1>
                            <div class="justify-content-end">
                                <a href="{{ route('admin.staff.addSales') }}"><img src="{{ asset('assets/images/plus-ico.svg') }}"></a>
                            </div>
                        </div>  
                    </div>
                    @endif


                    
                </div>

                <div class="col-md-3 pr-0">
                    <div class="team-memb-sec p-4">
                        <h1>Team Members</h1>

                        <div class="row">
                            <div class="col-md-6 counts-main">
                                <span class="green-txt">{{ $members->where('status',1)->count() }}</span>
                                <label>Active <br>Members</label>
                            </div>

                            <div class="col-md-6 counts-main">
                                <span class="orange-txt">{{ $members->where('status',0)->count() }}</span>
                                <label>Inactive <br> Members</label>
                            </div>
                        </div>


                        <div class="team-memb-secnd-sec">
                            <div class="row bid-list-head d-flex align-items-center justify-content-center">
                                <div class="col-md-8"> Member Name </div>
                                <div class="col-md-4 d-flex align-items-center justify-content-center">Status 
                                </div>
                            </div>
                            <ul class="team-memb-ul">
                                @forelse($members as $key => $member)
                                <li>
                                    <div class="row all-bid-list d-flex align-items-center justify-content-center">
                                        <div class="col-md-8">
                                            <a href="{{ route('admin.member.profile',$member->id) }}"><label class="name">{{ $member->name }}</label></a>
                                            <label>{{ $member->designation }}</label>
                                        </div>
                                        <div class="col-md-4 d-flex align-items-center justify-content-center"><span class="status-{{ $member->status == 1 ? 'green' : 'orange' }}">{{ $member->status == 1 ? 'Active' : 'Inactive' }}</span></div>
                                    </div>
                                </li>
                                @empty
                                <li>
                                    <div class="row all-bid-list d-flex align-items-center justify-content-center">
                                        <div class="col-md-8">
                                            <label class="name">No Team Members!</label>
                                        </div>
                                    </div>
                                </li>
                                @endforelse
                            </ul>


                            <div class="form-group mt-3 d-flex align-items-center justify-content-between">
                                <a href="#">View All</a>
                            </div>


                        </div>

                    </div>
                </div>

            </div>

        

    </section>
@push('scripts')
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.4.0/axios.min.js" integrity="sha512-uMtXmF28A2Ab/JJO2t/vYhlaa/3ahUOgj1Zf27M5rOo8/+fcTUVH0/E0ll68njmjrLqOBjXM3V9NiPFL5ywWPQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script>
    @if (Session::has('profile_updated'))
        Swal.fire({
            icon: 'success',
            title: 'Profile has been successfully <br><br> updated.',
            html: "<p style='margin-top: 10px; font-size: 16px;'>{{ Session::get('profile_updated') }}</p>",
            confirmButtonText: 'OK',
        });
    @endif
</script>
<script>
  google.charts.load('current', { packages: ['corechart'] });
  google.charts.setOnLoadCallback(drawChart);

  function drawChart() {
    // Initial data for the chart with custom colors for slices
    var dataPro = google.visualization.arrayToDataTable([
      ['Label', 'Value'], // Add a column for custom colors
      ['Open Enquiry', {{ $openPro }}], // Custom color for slice 1
      ['Closed Enquiry', {{ $closedPro }}], // Custom color for slice 2
      ['Expired', {{ $expiredPro }}], // Custom color for slice 3
      
    ]);

    var dataSale = google.visualization.arrayToDataTable([
      ['Label', 'Value'], // Add a column for custom colors
      ['Open Enquiry', {{ $openSale }}], // Custom color for slice 1
      ['Closed Enquiry', {{ $closedSale }}], // Custom color for slice 2
      ['Expired', {{ $expiredSale }}],// Custom color for slice 3
      
    ]);

    // Options for the chart
    var options = {
      pieHole: 0.6,
      legend: 'none',     // Hide the legend
      pieSliceText: 'none', // Hide the percentage values
      backgroundColor: 'transparent',
      slices: {
            0: { color: '#00AA60' },
            1: { color: '#0087C4' },
            2: { color: '#D88405' }
      }
    };

    // Create and draw the chart
    var chartSales = new google.visualization.PieChart(document.getElementById('sales_chart'));
    chartSales.draw(dataSale, options);

    var chartProcurement = new google.visualization.PieChart(document.getElementById('procurement_chart'));
    chartProcurement.draw(dataPro, options);


    //   $('input[name="daterangesales"]').daterangepicker({
    //         opens: 'left',
    //          locale: {
    //             format: 'DD-MM-YYYY' 
    //         },
    //         startDate: moment().subtract(1, 'months'),
    //         endDate: moment()
    //       }, function(start, end, label) {
    //           var start_date = start.format('YYYY-MM-DD');
    //           var end_date = end.format('YYYY-MM-DD');
    //           var action = "{{ route('admin.chart') }}";
    //           axios.post(action, {start_date:start_date,end_date:end_date,role:3})
    //                 .then((response) => {
    //                     // Handle success response
                        
    //                     var data = google.visualization.arrayToDataTable([
    //                       ['Label', 'Value'], // Add a column for custom colors
    //                       ['Open Enquiry', response.data.open], // Custom color for slice 1
    //                       ['Closed Enquiry', response.data.closed], // Custom color for slice 2
    //                       ['Expired', response.data.expired],// Custom color for slice 3
                          
    //                     ]);
                        
    //                     chartSales.draw(data, options);
                        
    //                     $('#sales-open').text(response.data.open);
    //                     $('#sales-closed').text(response.data.closed);
    //                     $('#sales-expired').text(response.data.expired);
    //                 })
    //                 .catch((error) => {
    //                     // Handle error response
    //                     console.log(error);
    //                 });
             
    //       });

    var createdAt = moment("{{ $company->created_at }}", "YYYY-MM-DD HH:mm:ss");

    $('#start_date_sales').daterangepicker({
        singleDatePicker: true,
        opens: 'left',
        locale: { format: 'DD-MM-YYYY' },
        startDate: moment().subtract(1, 'months'),
        minDate: createdAt
    });

    // Initialize the end date field (today's date)
    $('#end_date_sales').daterangepicker({
        singleDatePicker: true,
        opens: 'left',
        locale: { format: 'DD-MM-YYYY' },
        startDate: moment(),
        maxDate: moment() 
    });

    // Capture changes to trigger data update
    $('#start_date_sales, #end_date_sales').on('apply.daterangepicker', function() {
        var start_date = $('#start_date_sales').data('daterangepicker').startDate.format('YYYY-MM-DD');
        var end_date = $('#end_date_sales').data('daterangepicker').startDate.format('YYYY-MM-DD');
        
        // Define the action URL for your request
        var action = "{{ route('admin.chart') }}";
        
        axios.post(action, { start_date: start_date, end_date: end_date, role: 3 })
            .then((response) => {
                // Handle success response and update the chart and values
                var data = google.visualization.arrayToDataTable([
                    ['Label', 'Value'],
                    ['Open Enquiry', response.data.open],
                    ['Closed Enquiry', response.data.closed],
                    ['Expired', response.data.expired]
                ]);
                
                chartSales.draw(data, options);
                $('#sales-open').text(response.data.open);
                $('#sales-closed').text(response.data.closed);
                $('#sales-expired').text(response.data.expired);
            })
            .catch((error) => {
                console.log(error);
            });
    });
      
    //    $('input[name="daterangeprocurement"]').daterangepicker({
    //         opens: 'left',
    //         locale: {
    //             format: 'DD-MM-YYYY' 
    //         },
    //         startDate: moment().subtract(1, 'months'),
    //         endDate: moment()
    //       }, function(start, end, label) {
    //           var start_date = start.format('YYYY-MM-DD');
    //           var end_date = end.format('YYYY-MM-DD');
    //           var action = "{{ route('admin.chart') }}";
    //           axios.post(action, {start_date:start_date,end_date:end_date,role:2})
    //                 .then((response) => {
                        
    //                     // Handle success response
    //                     var data = google.visualization.arrayToDataTable([
    //                       ['Label', 'Value'], // Add a column for custom colors
    //                       ['Open Enquiry', response.data.open], // Custom color for slice 1
    //                       ['Closed Enquiry', response.data.closed], // Custom color for slice 2
    //                       ['Expired', response.data.expired],// Custom color for slice 3
                          
    //                     ]);
                        
    //                     chartProcurement.draw(data, options);
                        
    //                     $('#procurement-open').text(response.data.open);
    //                     $('#procurement-closed').text(response.data.closed);
    //                     $('#procurement-expired').text(response.data.expired);
    //                 })
    //                 .catch((error) => {
    //                     // Handle error response
    //                     console.log(error);
    //                 });
    //       });
      
            $('#start_date').daterangepicker({
                singleDatePicker: true,
                opens: 'left',
                locale: { format: 'DD-MM-YYYY' },
                startDate: moment().subtract(1, 'months'),
                minDate: createdAt
            });

            // Initialize the end date field (today's date)
            $('#end_date').daterangepicker({
                singleDatePicker: true,
                opens: 'left',
                locale: { format: 'DD-MM-YYYY' },
                startDate: moment(),
                maxDate: moment() 
            });

            // Capture changes to update chart data
            $('#start_date, #end_date').on('apply.daterangepicker', function() {
                var start_date = $('#start_date').data('daterangepicker').startDate.format('YYYY-MM-DD');
                var end_date = $('#end_date').data('daterangepicker').startDate.format('YYYY-MM-DD');
                
                // Define the action URL for your request
                var action = "{{ route('admin.chart') }}";
                
                axios.post(action, { start_date: start_date, end_date: end_date, role: 2 })
                    .then((response) => {
                        // Handle success response and update the chart and values
                        var data = google.visualization.arrayToDataTable([
                            ['Label', 'Value'],
                            ['Open Enquiry', response.data.open],
                            ['Closed Enquiry', response.data.closed],
                            ['Expired', response.data.expired]
                        ]);
                        
                        chartProcurement.draw(data, options);
                        $('#procurement-open').text(response.data.open);
                        $('#procurement-closed').text(response.data.closed);
                        $('#procurement-expired').text(response.data.expired);
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            });
      
      
  }

    $(function(){
        $('body').on('click','.enableDisable',function() {
             var id = $(this).data('id');
             var enableDisableAction = "{{ route('admin.staff.enableDisable') }}"
             Swal.fire({
                    title: "Are you sure?",
                    text: "Do you want to change!",
                    icon: 'warning',
                    showCancelButton: true,
                }).then(function (willUpdate) {
                     if (willUpdate.isConfirmed === true) {
                        axios.post(enableDisableAction, {id:id})
                            .then((response) => {
                               window.location.reload();
                            })
                            .catch((error) => { 
                                // Handle error response
                                window.location.reload();
                            });
                    }
                    else{
                        Swal.fire({
                            title: 'Cancelled',
                            icon: "error",
                        }); 
                    }
                });
        });
    });

</script>
@endpush
@endsection