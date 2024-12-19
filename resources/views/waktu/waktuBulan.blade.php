@extends('layouts.appAdmin')
<title>Input Data Diri</title>

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
            <h1>Daftar Waktu Laporan Bulanan untuk Tahun {{ \Carbon\Carbon::parse($waktu_tahun->waktu_tahun_laporan)->format('Y') }}</h1>
            <a href="{{ route('tambahBulan', $waktu_tahun->id) }}" class="btn btn-secondary mb-3">Tambah Waktu Bulan</a>
            <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Waktu Bulan Laporan</th>
                            <th>Start</th>
                            <th>End</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($waktu_bulan_laporan as $laporan)
                        <tr>
                            <td>{{ $laporan->id }}</td>
                            <td>{{ $laporan->waktu_bulan_laporan }}</td>
                            <td>{{ $laporan->start }}</td>
                            <td>{{ $laporan->end }}</td>
                            <td>
                                <a href="{{ route('editBulan', ['tahunId' => $waktu_tahun->id, 'bulanId' => $laporan->id]) }}" class="btn btn-warning">Edit</a>
                                <form action="{{ route('destroyBulan', ['tahunId' => $waktu_tahun->id, 'bulanId' => $laporan->id]) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $waktu_bulan_laporan->links() }}
            </div>
        </div>
    </div>
</div>
<a href="{{ route('waktuTahunan') }}" class="btn btn-secondary">Kembali</a>

<!-- ========== footer start =========== -->
<footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 order-last order-md-first">
                    <div class="copyright text-md-start">
                        <p class="text-sm">
                            Developed by
                            <a
                                    href="https://www.wahanaritelindo.com/"
                                    rel="nofollow"
                                    target="_blank"
                                    class="text-red"
                            >
                                Wahana Ritelindo
                            </a>
                        </p>
                    </div>
                </div>
                <div class="col-md-6 order-last order-md-first">
                    <div class="copyright text-md-end">
                        
                    </div>
                </div>
                <!-- end col-->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </footer>
    <!-- ========== footer end =========== -->
</main>
<!-- ======== main-wrapper end =========== -->

<script>
    const form = document.getElementById('yourForm');
    const recruitmentSourceSelect = document.getElementById('recruitment_source');
    const otherSourceInput = document.getElementById('otherSourceInput');

    recruitmentSourceSelect.addEventListener('change', function() {
        if (recruitmentSourceSelect.value === 'Other') {
            otherSourceInput.style.display = 'block';
            document.getElementById('other_source').setAttribute('required', 'required');
        } else {
            otherSourceInput.style.display = 'none';
            document.getElementById('other_source').removeAttribute('required');
        }
    });
</script>

<script>
    var birthdateInput = document.getElementById("birthdateInput");
    var today = new Date();

    // Set the max attribute of the input element to the date exactly 17 years ago from today
    var maxDate = new Date(today);
    maxDate.setFullYear(maxDate.getFullYear() - 17);
    birthdateInput.setAttribute("max", maxDate.toISOString().split("T")[0]);

    // Set the min attribute of the input element to the date exactly 55 years ago from today
    var minDate = new Date(today);
    minDate.setFullYear(minDate.getFullYear() - 55);
    birthdateInput.setAttribute("min", minDate.toISOString().split("T")[0]);

    // Function to convert the input date to "dd-mm-yyyy" format
    function formatDateToDDMMYYYY(date) {
        var day = ("0" + date.getDate()).slice(-2);
        var month = ("0" + (date.getMonth() + 1)).slice(-2);
        var year = date.getFullYear();
        return day + "-" + month + "-" + year;
    }

    // Add event listener to validate and format the input date
    // birthdateInput.addEventListener("change", function() {
    //     var selectedDate = new Date(birthdateInput.value);

    //     // Calculate the difference in years
    //     var ageDiff = today.getFullYear() - selectedDate.getFullYear();

    //     // Check if the user is at least 17 years old and not more than 55 years old
    //     if (ageDiff < 17 || ageDiff > 55) {
    //         alert("You must be between 17 and 55 years old.");
    //         birthdateInput.value = ""; // Clear the input value if invalid date
    //     }
    // });
</script>

<script>
    // Set the minimum date (today)
    var minDate = new Date();
    var minDateString = minDate.toISOString().split("T")[0];
    var ableToWorkInput = document.getElementById("able_to_work");
    ableToWorkInput.setAttribute("min", minDateString);

    // Set the maximum date (3 months later)
    var maxDate = new Date();
    maxDate.setMonth(maxDate.getMonth() + 3);
    var maxDateString = maxDate.toISOString().split("T")[0];
    ableToWorkInput.setAttribute("max", maxDateString);

    // Add event listener to validate the input date
    ableToWorkInput.addEventListener("change", function() {
        var selectedDate = new Date(ableToWorkInput.value);
        var today = new Date();

        // Calculate the difference in days
        var timeDiff = selectedDate.getTime() - today.getTime();
        var daysDiff = timeDiff / (1000 * 3600 * 24);

        // Check if the date is within the valid range
        if (daysDiff < -1 || daysDiff > 92) {
            alert("The date must be between today and up to 3 months later.");
            ableToWorkInput.value = ""; // Clear the input value if outside the valid range
        }
    });
</script>


<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview');
            output.src = reader.result;
            output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
    }

    const educationSelect = document.getElementById('education');
    const majorField = document.getElementById('majorField');

    educationSelect.addEventListener('change', function() {
        if (this.value === 'S3' || this.value === 'S2' || this.value === 'S1' || this.value === 'D4' || this.value === 'D3' || this.value === 'D2' || this.value === 'D1' || this.value === 'SMK' || this.value === 'SMA') {
            majorField.style.display = 'block';
            majorField.classList.remove('col-md-12');
            majorField.classList.add('col-md-6');
        } else {
            majorField.style.display = 'none';
            majorField.classList.remove('col-md-6');
            majorField.classList.add('col-md-12');
        }
    });


    // input branch location
    document.addEventListener('DOMContentLoaded', function() {
    const branchCitySelect = document.getElementById('branch_city');
    const branchSelect = document.getElementById('branch');

    // Function to update the branch select options
    function updateBranchOptions() {
        const selectedCity = branchCitySelect.value;
        const branchOptions = allBranches.filter(branch => branch.city === selectedCity);

        // Clear existing options
        branchSelect.innerHTML = '';

        // Add new options
        if (branchOptions.length > 0) {
            branchSelect.style.display = 'block';
            branchOptions.forEach(branch => {
                const option = document.createElement('option');
                option.value = branch.location;
                option.textContent = branch.location;
                branchSelect.appendChild(option);
            });
        } else {
            branchSelect.style.display = 'none';
        }
    }

    // Event listener for branch location change
    branchCitySelect.addEventListener('change', function() {
        const selectedCity = branchCitySelect.value;

        if (selectedCity === '') {
            branchSelect.style.display = 'none';
        } else {
            updateBranchOptions();
        }
    });
});

    function combineFields() {
    var education = document.getElementById('education').value;
    var major = document.getElementById('major').value;
    var edu = education + ', ' + education;
    document.getElementById('education').value = edu;
    }
</script>

@endsection
