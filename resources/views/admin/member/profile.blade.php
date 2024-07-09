@extends('admin.layouts.app')
@section('content')
    <!-- Header Starts -->
    @include('admin.layouts.header')
    <!-- Header Ends -->

    <section class="container-fluid pleft-77">
            <div class="row px-4 py-1 pt-4">
                <div class="col-md-12">
                    <div class="row p-4 dash-blocks">
                        <div class="col-md-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <h1>Team Member</h1>
                                <!--<a href="{{ route('admin.staff.edit',$staff->id) }}" class="team-edit"><img src="{{ asset('assets/images/team-edit-ico.svg') }}"></a>-->
                            </div>

                            <div class="d-flex">
                                <div class="tumb-img d-flex align-items-center justify-content-center"><img src="{{ asset('assets/images/profile-pic-1.png') }}"></div>
                                <div class="sale-prof-name">
                                    <h2>{{ $staff->name ?? '' }}</h2>
                                    <span>{{ $staff->designation ?? '' }}</span><br> 
                                    @if($staff->password != '')
                                    <input type="checkbox" name="" id="aa1" class="add-page-check enableDisable" data-id="{{ $staff->id }}" {{ $staff->status == 1 ? 'checked' : ' ' }}>
                                    <label for="aa1" class="d-flex"><span></span></label>
                                    @else
                                    <span class="text-danger">Activation Pending!</span>
                                    @endif
                                </div>
                                
                            </div>

                                <div class="sale-prof-detail">
                                    <label>Email</label>
                                    {{ $staff->email ?? '' }}
                                </div>

                                <div class="sale-prof-detail">
                                    <label>Phone</label>
                                    {{ $staff->mobile ?? '' }}
                                </div>

                                <div class="sale-prof-detail d-flex align-items-center justify-content-between">
                                    <div><label>Landline</label>
                                    {{ $staff->landline ?? '' }}</div>

                                    <div>
                                        <label>Extension</label>
                                        {{ $staff->extension ?? '' }}
                                    </div>
                                </div>
                            
                        </div>
                        <div class="col-md-9">
                            <div class="row bg-grey pt-4 pb-4 px-2">

                                <div class="col-md-12 d-flex justify-content-end pb-4">
                                       <input class="date-print-sec d-flex align-items-center " type="text" name="daterangeprocurement" value="08/01/2023 - 12/15/2023" />
                                   
                                    <div class="form-group">
                                        <input type="submit" value="Print Report" class="btn btn-secondary-small" onclick="window.location.href = '';">
                                    </div>

                                </div>

                                <div class="col-md-4 text-center position-relative  d-flex flex-column align-items-center justify-content-center ">
                                     <div id="procurement_chart"></div>   
                                <!-- <div class="piechart d-flex align-items-center justify-content-center">
                                        <div class="piechart-middle d-flex align-items-center justify-content-center">31</div>
                                    </div> -->
                                    Total Enquiries Send
                                </div>
                                <div class="col-md-8">
                                    <div class="counts-white">
                                        <div class="row">
                                            <div class="col-md-4 counts-main">
                                                <span id="procurement-open" class="green-txt">{{ $openPro }}</span>
                                                <label>No. of RFQ Sent</label>
                                            </div>
                                            <div class="col-md-4 counts-main">
                                                <span id="procurement-closed" class="blue-txt">{{ $closedPro }}</span>
                                                <label>No of RFQ in Progress</label>
                                            </div>
                                            <div class="col-md-4 counts-main">
                                                <span id="procurement-expired" class="orange-txt">{{ $expiredPro }}</span>
                                                <label>No of RFQ Completed</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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


    var chartProcurement = new google.visualization.PieChart(document.getElementById('procurement_chart'));
    chartProcurement.draw(dataPro, options);



      
       $('input[name="daterangeprocurement"]').daterangepicker({
            opens: 'left'
          }, function(start, end, label) {
              var start_date = start.format('YYYY-MM-DD');
              var end_date = end.format('YYYY-MM-DD');
              var action = "{{ route('admin.chart') }}";
              axios.post(action, {start_date:start_date,end_date:end_date,role:4})
                    .then((response) => {
                        
                        // Handle success response
                        var data = google.visualization.arrayToDataTable([
                          ['Label', 'Value'], // Add a column for custom colors
                          ['Open Enquiry', response.data.open], // Custom color for slice 1
                          ['Closed Enquiry', response.data.closed], // Custom color for slice 2
                          ['Expired', response.data.expired],// Custom color for slice 3
                          
                        ]);
                        
                        chartProcurement.draw(data, options);
                        
                        $('#procurement-open').text(response.data.open);
                        $('#procurement-closed').text(response.data.closed);
                        $('#procurement-expired').text(response.data.expired);
                    })
                    .catch((error) => {
                        // Handle error response
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