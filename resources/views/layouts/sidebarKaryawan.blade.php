<ul>
    <li class="nav-item nav-item-has-children">
        <a class="collapsed" href="#0" class="" data-bs-toggle="collapse" data-bs-target="#ddmenu_1"
           aria-controls="ddmenu_1" aria-expanded="false" aria-label="Toggle navigation">
           <span class="icon">
                <svg width="22" height="22" viewBox="0 0 22 22">
                  <path
                          d="M17.4167 4.58333V6.41667H13.75V4.58333H17.4167ZM8.25 4.58333V10.0833H4.58333V4.58333H8.25ZM17.4167 11.9167V17.4167H13.75V11.9167H17.4167ZM8.25 15.5833V17.4167H4.58333V15.5833H8.25ZM19.25 2.75H11.9167V8.25H19.25V2.75ZM10.0833 2.75H2.75V11.9167H10.0833V2.75ZM19.25 10.0833H11.9167V19.25H19.25V10.0833ZM10.0833 13.75H2.75V19.25H10.0833V13.75Z"
                  />
                </svg>
              </span>
            <span class="text">Dashboard Cabang</span>
        </a>
        @if(request()->routeIs('admin.home'))
            <ul id="ddmenu_1" class="dropdown-nav collapse show">
        @elseif(request()->routeIs('admin.branch'))
            <ul id="ddmenu_1" class="dropdown-nav collapse show">
        @else
            <ul id="ddmenu_1" class="dropdown-nav collapse">
        @endif
       
        </ul>
    </li>

    {{-- <li class="nav-item @if(request()->routeIs('cabangreport')) active @endif">
        <a href="{{ route('cabangreport') }}">
              <span class="icon">
                    <i class="fa-solid fa-users"></i>
              </span>
            <span class="text">{{ __('Reporting Bulanan') }}</span>
        </a>
    </li> --}}

    <!-- Masukan Laporan - Admin, Manager, User -->
    @if (in_array(auth()->user()->type, ['user']))
    <li class="nav-item @if(request()->routeIs('judulLaporan')) active @endif">
        <a class="nav-link" href="#" id="navbarMasukanLaporan" role="button" aria-expanded="false">
            <span class="icon">
                <i class="fa-solid fa-users"></i>
            </span>
            <span class="text">{{ __('Masukan Laporan') }}</span>
        </a>

        <ul class="nav flex-column ml-3" id="masukanLaporanSubmenu" style="display: none;">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('judulLaporan', ['is_monthly' => 1]) }}">
                    {{ __('Laporan Bulanan') }}
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('judulLaporan', ['is_monthly' => 0]) }}">
                    {{ __('Laporan Mingguan') }}
                </a>
            </li>
        </ul>
    </li>
    @endif

</ul>
<script>
    // Toggle for Masukan Laporan dropdown
    document.getElementById('navbarMasukanLaporan').addEventListener('click', function(event) {
        event.preventDefault();
        var submenu = document.getElementById('masukanLaporanSubmenu');
        if (submenu.style.display === 'none') {
            submenu.style.display = 'block';
        } else {
            submenu.style.display = 'none';
        }
    });
</script>