@extends('layouts.appAdmin')
<title>Dashboard Utama</title>

@section('content')
<div class="container-fluid">
    <div class="title-wrapper pt-30">
        <div class="row align-items-center">
            <div class="col-md-6">
                <div class="title">
                    <h2>Dashboard Utama</h2>
                </div>
            </div>
            <div class="col-md-6">
                <div class="breadcrumb-wrapper">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="#0">Admin</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">Home</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="card-style mb-30">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center">No</th>
                            <th rowspan="2" class="text-center">Nama</th>
                            @foreach (range(1, 12) as $month)
                                <th class="text-center">{{ DateTime::createFromFormat('!m', $month)->format('F') }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cabangs as $cabang)
                            <tr>
                                <td colspan="14" class="text-center bg-secondary text-white">{{ $cabang->nama_cabang }}</td>
                            </tr>
                            @foreach ($cabang->users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    @foreach (range(1, 12) as $month)
                                        <td class="text-center">
                                            <!-- Here you can add a status icon or text, such as a check mark or cross based on your logic -->
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection