@extends('layouts.appKaryawan')
<title>Dashboard Utama</title>

@section('content')
 <!-- ========== section start ========== -->
        <div class="container-fluid">
          <!-- ========== title-wrapper start ========== -->
          <div class="title-wrapper pt-30">
            <div class="row align-items-center">
              <div class="col-md-6">
                <div class="title">
                  <h2>Dashboard Utama</h2>
                </div>
              </div>
              <!-- end col -->
              <div class="col-md-6">
                <div class="breadcrumb-wrapper">
                  <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                      <li class="breadcrumb-item">
                        <a href="#0">Admin</a>
                      </li>
                      <li class="breadcrumb-item active" aria-current="page">
                        Home
                      </li>
                    </ol>
                  </nav>
                </div>
              </div>
              <!-- end col -->
            </div>
            <!-- end row -->
          </div>
          <!-- end row -->
          
          
            
          
            <div class="col-lg-12">
              <div class="card-style mb-30">
                <div class="title d-flex flex-wrap justify-content-between">
                  <div class="left">
                    <!-- <h6 class="text-medium mb-10">Jumlah Pendaftar 30 Hari Terakhir</h6>
                    <h3 class="text-bold"></h3> -->
                  </div>
                  <div class="right">
                    <div class="select-style-1">
                      <!-- <div class="select-position select-sm">
                        <select class="light-bg">
                          <option value="">30 Hari</option>
                        </select>
                      </div> -->
                    </div>
                    <!-- end select -->
                  </div>
                </div>
                <!-- End Title -->
                  <div class="chart">
                      <canvas id="linechart" style="width: 100%; height: 400px"></canvas>
                  </div>
                <!-- End Chart -->
              </div>
            </div>
            <!-- End Col -->
          </div>
          <!-- End Row -->


          <script>

            

              // Create the line chart
              var ctx = document.getElementById('linechart').getContext('2d');
              var myChart = new Chart(ctx, {
                  type: 'line',
                  data: {
                      labels: labels,
                      datasets: [{
                          label: 'Jumlah Peserta',
                          data: counts,
                          fill: false,
                          borderColor: '#d1393A',
                          tension: 0.1
                      }]
                  },
                  options: {
                      scales: {
                          y: {
                              beginAtZero: true,
                              precision: 0,
                              callback: function (value) {
                                  if (Number.isInteger(value)) {
                                      return value;
                                  }
                              }
                          }
                      }
                  } 
              });

              //  END of linechart //
              
          </script>



@endsection