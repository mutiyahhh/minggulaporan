import './bootstrap';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";

// Inisialisasi untuk Start Time
flatpickr("#start_time", {
    enableTime: true,
    noCalendar: false,
    dateFormat: "m-d H:i",
    time_24hr: true,
    defaultDate: "today",
    onReady: function(selectedDates, dateStr, instance) {
        instance.calendarContainer.querySelector(".numInputWrapper").style.display = 'none';  // Sembunyikan input tahun
    }
});

// Inisialisasi untuk End Time
flatpickr("#end_time", {
    enableTime: true,
    noCalendar: false,
    dateFormat: "m-d H:i",
    time_24hr: true,
    defaultDate: "today",
    onReady: function(selectedDates, dateStr, instance) {
        instance.calendarContainer.querySelector(".numInputWrapper").style.display = 'none';  // Sembunyikan input tahun
    }
});

