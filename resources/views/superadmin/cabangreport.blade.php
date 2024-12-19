@extends('layouts.appAdmin')

<title>Laravel 10 Uploading Image</title>

@section('content')


  <title>Laravel 10 Uploading Image</title>

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">



<div class="container mt-5">

  @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
  @endif

  <div class="container mt-5">

  @if(session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
  @endif

  <div class="card">

    <div class="card-header text-center font-weight-bold">
      <h2>Laravel 10 Upload Image Tutorial</h2>
    </div>

    <div class="card-body">
    <form action="{{ route('upload.save') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="text" id="users_id" name="users_id" value="{{ Auth::user()->id }}" hidden>
        <input type="file" name="images[]" multiple>
        <input type="file" name="images[]" multiple>
        <button type="submit">Upload</button>
    </form>

    </div>

  </div>  

</div>  


@endsection