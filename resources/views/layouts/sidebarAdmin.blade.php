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
    @if (auth()->user()->type == 'admin')
    <li class="nav-item @if(request()->routeIs('cabangreport') || request()->routeIs('waktuTahunan')) active @endif">
    <a class="nav-link" href="#" id="navbarLaporan" role="button" aria-expanded="false">
        <span class="icon">
            <i class="fa-solid fa-file-alt"></i>
        </span>
        <span class="text">{{ __('Laporan') }}</span>
    </a>
    <ul class="nav flex-column ml-3" id="laporanSubmenu" style="display: none;">
        <li class="nav-item @if(request()->routeIs('melihatLaporan')) active @endif">
            <a class="nav-link" href="{{ route('melihatLaporan') }}">
                {{ __('Master Laporan') }}
            </a>
        </li>
    {{-- <li class="nav-item @if(request()->routeIs('melihatdaftarlaporan')) active @endif">
        <a class="nav-link" href="{{ route('melihatdaftarlaporan') }}">
            {{ __('Master Laporan') }}
        </a>
    </li> --}}
        <li class="nav-item @if(request()->routeIs('waktuTahunan')) active @endif">
            <a class="nav-link" href="{{ route('waktuTahunan') }}">
                {{ __('Perancangan Waktu Laporan') }}
            </a>
        </li>
        <li class="nav-item @if(request()->routeIs('judullaporan')) active @endif">
            <a class="nav-link" href="{{ route('judullaporan') }}">
                {{ __('Perancangan bagian Laporan') }}
            </a>
        </li>
    </ul>
    @endif

    <!-- Masukan Laporan - Admin, Manager, User -->
    @if (in_array(auth()->user()->type, ['admin', 'manager', 'user']))
        </li>
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

    <!-- Hasil Laporan - Admin, Manager, User -->
    @if (in_array(auth()->user()->type, ['admin', 'manager', 'user']))
    <li class="nav-item @if(request()->routeIs('hasiljudulLaporan')) active @endif">
            <a class="nav-link" href="#" id="navbarHasilLaporan" role="button" aria-expanded="false">
                <span class="icon">
                    <i class="fa-solid fa-users"></i>
                </span>
                <span class="text">{{ __('Hasil Laporan') }}</span>
            </a>
            <ul class="nav flex-column ml-3" id="hasilLaporanSubmenu" style="display: none;">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('hasiljudulLaporan', ['is_monthly' => 1]) }}">
                        {{ __('Laporan Bulanan') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('hasiljudulLaporan', ['is_monthly' => 0]) }}">
                        {{ __('Laporan Mingguan') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('listhasil', ['is_monthly' => 0]) }}">
                        {{ __('List Hasil Laporan') }}
                    </a>
                </li>
            </ul>
        </li>
        @endif

    <!-- Kelola Cabang dan Account - Admin, Manager -->
    @if (in_array(auth()->user()->type, ['admin', 'manager']))
    </li>
        <li class="nav-item @if(request()->routeIs('dataCabang')) active @endif">
            <a href="{{ route('dataCabang') }}">
                <span class="icon">
                        <i class="fa-solid fa-users"></i>
                </span>
                <span class="text">{{ __('Kelola Cabang dan account') }}</span>
            </a>
        </li>
     @endif

    <script>
    // Toggle for Laporan dropdown
    document.getElementById('navbarLaporan').addEventListener('click', function(event) {
        event.preventDefault();
        var submenu = document.getElementById('laporanSubmenu');
        if (submenu.style.display === 'none') {
            submenu.style.display = 'block';
        } else {
            submenu.style.display = 'none';
        }
    });

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

    // Toggle for Hasil Laporan dropdown
    document.getElementById('navbarHasilLaporan').addEventListener('click', function(event) {
        event.preventDefault();
        var submenu = document.getElementById('hasilLaporanSubmenu');
        if (submenu.style.display === 'none') {
            submenu.style.display = 'block';
        } else {
            submenu.style.display = 'none';
        }
    });
</script>

    

    

   
</ul>

<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<!-- jQuery and Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- SELECT 2 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> 