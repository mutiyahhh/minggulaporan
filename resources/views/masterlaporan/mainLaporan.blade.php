@extends('layouts.appAdmin')

@section('content')
<style>
    input.numInput.cur-year {
        pointer-events:none !important;
        color:#AAA !important;
        background:#F5F5F5 !important;
    }
    .hidden {
        display: none;
    }
</style>
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h3>{{ $judul->judul_laporan }}</h3>
                <p>{{ $judul->deskripsi_laporan }}</p>

                <form action="{{ route('detailLaporan.store') }}" method="POST">
                    @csrf
                    <!-- Hidden Fields -->
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="judul_laporan_id" value="{{ $judul->id }}">
                    <input type="hidden" id="waktu_id" name="waktu_id" value="">
                    <input type="hidden" name="year_date" id="year_date" />
                    <input type="hidden" name="end_date_time" id="end_date_time" />
                    <input type="hidden" name="start_date_time" id="start_date_time" />
                    <input type="hidden" name="weekly" id="weekly" />

                    <!-- Dropdown Tahun -->
                    <div class="form-group">
                        <label for="waktu_tahun_laporan_id">Waktu Tahun Laporan</label>
                        <select id="waktu_tahun_laporan_id" name="waktu_tahun_laporan_id" class="form-control">
                            <option value="" class="hidden">Pilih Tahun</option>
                            @foreach($waktuTahunLaporanOptions as $tahun)

                                <option value="{{ $tahun->waktu_tahun_laporan }}:{{ $tahun->id }}">{{ $tahun->waktu_tahun_laporan }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jenis_laporan">Jenis Laporan</label>
                        <select id="jenis_laporan" name="jenis_laporan" class="form-control">
                            <option value="" class="hidden">Pilih Jenis Laporan</option>
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                        </select>
                    </div>

                    {{-- <!-- Start Time Picker -->
                    <div class="form-group">
                        <label for="start_time">Start (YY-MM-DD HH:MM)</label>
                        <input type="text" name="start_time" id="start_time" class="form-control" placeholder="Pilih Start Time">
                    </div>

                    <!-- End Time Picker -->
                    <div class="form-group">
                        <label for="end_time">End (YY-MM-DD HH:MM)</label>
                        <input type="text" name="end_time" id="end_time" class="form-control" placeholder="Pilih End Time">
                    </div> --}}
                    <!-- Start Time Picker -->
                    <div class="form-group">
                        <label for="start_time">Start (Tanggal)</label>
                        <select id="start_time" class="form-control select2" name="start_time" placeholder="Pilih Start Tanggal"></select>
                    </div>

                    <!-- End Time Picker -->
                    <div class="form-group">
                        <label for="end_time">End (Tanggal)</label>
                        <select id="end_time" class="form-control select2" name="end_time" placeholder="Pilih End Tanggal"></select>
                    </div>
                    <!-- Dynamic Subjudul Laporan Fields -->
                    @foreach($subjudulLaporanOptions as $index => $subjudul)
                        <div class="form-group">
                            <label>{{ $subjudul->subjudul_laporan }}</label>

                            <!-- Input Hidden untuk subjudul_laporan_id -->
                            <input type="hidden" name="subjudul_laporan_id[]" value="{{ $subjudul->id }}">

                            <!-- Textbox untuk Catatan Laporan -->
                            <div class="form-group">
                                <label for="catatan_laporan_{{ $subjudul->id }}">Catatan Laporan</label>
                                <textarea class="form-control" name="catatan_laporan[]" rows="3"></textarea>
                            </div>
                        </div>
                    @endforeach

                    <!-- Status Laporan (Hidden) -->
                    <input type="hidden" name="status_laporan" value="waiting">

                    <!-- Button untuk Menyimpan -->
                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Flatpickr -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    $(document).ready(function() {
        // Data untuk dropdown tanggal 1-31
        const data = Array.from({ length: 31 }, (_, i) => ({
            id: i + 1,
            text: String(i + 1),
        }));

        // Initialize select2 for Start Date
        $('#start_time').select2({
            data: data,
            placeholder: "Pilih Start Tanggal",
            allowClear: true
        });

        // Initialize select2 for End Date
        $('#end_time').select2({
            data: data,
            placeholder: "Pilih End Tanggal",
            allowClear: true
        });

    function formatDate(inputString) {
        try {
            // Parse the date string.  Using new Date() is more flexible.
            const date = new Date(inputString);
            // Check if parsing was successful. Dates that are invalid parse as NaN.
            if (isNaN(date.getTime())) {
                throw new Error("Invalid date string.");
            }
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0'); // Month is 0-indexed.
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        } catch (error) {
            console.error("Error formatting date:", error.message); // Crucial for debugging
            return null; // Or throw the error, depending on your needs
        }
    }

    function getWeekStartDateAndEndDate() {
        const end_date_day = $('#end_time').val();
        const start_date_day = $('#start_time').val();

        const startDateWeek = moment().isoWeek() - moment().subtract('days', start_date_day - 1).isoWeek() + 1;
        const endDateWeek =  moment().isoWeek() - moment().subtract('days', end_date_day - 1).isoWeek() + 1;

        return {
            startDateWeek: startDateWeek,
            endDateWeek: endDateWeek,
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        flatpickr("#start_time", {
            dateFormat: "d",             // Hanya menampilkan tanggal
            disableMobile: true,         // Nonaktifkan tampilan mobile
            allowInput: false,           // Nonaktifkan input manual (hanya bisa pilih dari kalender)
            static: true,                // Kalender statis, tidak pop-up
            onReady: function(selectedDates, dateStr, instance) {
                // Menyembunyikan dropdown bulan dan tahun
                instance.calendarContainer.querySelector(".flatpickr-monthDropdown-months").style.display = "none";
                instance.calendarContainer.querySelector(".numInputWrapper").style.display = "none"; // Sembunyikan tahun
            },
            onChange: function(selectedDates, dateStr, instance) {
                dateComponent = formatDate(selectedDates)
                $('#start_date_time').val(dateComponent)
                // Pastikan input hanya tanggal yang ditampilkan
                instance.input.value = dateStr;
            }
        });

        flatpickr("#end_time", {
            dateFormat: "d",
            disableMobile: true,
            allowInput: false,
            static: true,
            onReady: function(selectedDates, dateStr, instance) {
                // Menyembunyikan dropdown bulan dan tahun
                instance.calendarContainer.querySelector(".flatpickr-monthDropdown-months").style.display = "none";
                instance.calendarContainer.querySelector(".numInputWrapper").style.display = "none"; // Sembunyikan tahun
            },
            onChange: function(selectedDates, dateStr, instance) {
                dateComponent = formatDate(selectedDates)
                $('#end_date_time').val(dateComponent)
                instance.input.value = dateStr;
            }
        });
    });

    $("#waktu_tahun_laporan_id").on('change', function (){
        const year_raw = $(this).val();
        const year = year_raw.substring(0, 4);
        const year_id = year_raw.split(":");
        $('#waktu_id').val(year_id[1]);
        $('#year_date').val(year_id[0]);
    });

    $('#end_time').on('change', function (){
        const { startDateWeek, endDateWeek } = getWeekStartDateAndEndDate();
        const listWeekOfMont = `${startDateWeek},${endDateWeek}`;

        $('#weekly').val(listWeekOfMont);
    });

    $('#start_time').on('change', function (){
        const { startDateWeek, endDateWeek } = getWeekStartDateAndEndDate();
        const listWeekOfMont = `${startDateWeek},${endDateWeek}`;

        $('#weekly').val(listWeekOfMont);
    });

    //     document.addEventListener('DOMContentLoaded', function () {
//         $('.numInputWrapper').prop('disabled', true);
//         // Initialize Flatpickr for start time
//         flatpickr("#start_time", {
//             enableTime: true,     // Enable time selection
//             noCalendar: false,     // Enable the calendar view
//             dateFormat: "Y-m-d H:i", // Format month-day hour:minute
//             time_24hr: true,       // Use 24-hour format
//             // Menonaktifkan tahun dari tampilan input
//             onClose: function(selectedDates, dateStr, instance) {
//                 // Ambil bulan dan hari dari dateStr
//                 let [year, month, day, hour, minute] = dateStr.split(/[- :]/);
//                 // Set input menjadi format m-d H:i
//                 instance.input.value = `${year}-${month}-${day} ${hour}:${minute}`;
//             }
//         });
//         // Initialize Flatpickr for end time
//         flatpickr("#end_time", {
//             enableTime: true,
//             noCalendar: false,
//             dateFormat: "Y-m-d H:i",
//             time_24hr: true,
//             onOpen: function (selectedDates, dateStr, instance) {
//                 let startTime = document.getElementById('start_time').value;

//                 if (!startTime) {
//                     instance.close();
//                     alert('Please select a start time first.');
//                 }
//             },
//             onClose: function(selectedDates, dateStr, instance) {
//                 let [year, month, day, hour, minute] = dateStr.split(/[- :]/);
//                 // Set input menjadi format m-d H:i
//                 instance.input.value = `${year}-${month}-${day} ${hour}:${minute}`;
//             }
//         });
//         // Prevent end time earlier than start time
//         document.getElementById('start_time').addEventListener('change', function() {
//             let startTime = document.getElementById('start_time').value;
//             let endTimeInput = document.getElementById('end_time');

//             if (endTimeInput._flatpickr) {
//                 endTimeInput._flatpickr.set('minDate', startTime);
//             }
//         });
    });

</script>
@endsection
