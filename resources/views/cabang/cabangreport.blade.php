@extends('layouts.appAdmin')

@section('content')
<div class="container-fluid">
    <div class="container rounded bg-white mt-4 mb-4">
        <div class="row">
            <div class="container">
                <h1>{{ $judul->judul_laporan }}</h1>
                <p>{{ $judul->deskripsi_laporan }}</p>
                <form action="{{ route('subjudul.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="judul_laporan_id" value="{{ $judul->id }}">

                    <div id="input-container">
                        <div class="file-input-group mt-2">
                            <div class="form-group">
                                <label for="subjudul_laporan">Sub Judul</label>
                                <input type="text" class="form-control text-input" name="subjudul_laporan[]" placeholder="Enter sub judul" required/>
                            </div>
                            <div class="form-group">
                                <label for="deskripsi">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi[]" placeholder="Enter deskripsi" rows="2"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="file-input">File</label>
                                <input type="file" accept="image/*,video/*,*/*" class="form-control file-input" name="files[]"/>
                            </div>
                            <button type="button" class="btn btn-danger btn-sm ml-2 remove-input-group-btn">Remove</button>
                        </div>
                    </div>

                    <button type="button" class="btn mt-2" id="add-input-group-btn" style="background-color: #D1393A; color: white;">+</button>

                    @error('files')
                    <span class="text-danger"> {{ $message }}</span>
                    @enderror

                    <button type="submit" class="btn btn-primary mt-3">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('add-input-group-btn').addEventListener('click', function () {
        var container = document.getElementById('input-container');
        var newInputGroup = document.createElement('div');
        newInputGroup.className = 'file-input-group mt-2';

        newInputGroup.innerHTML = `
            <div class="form-group">
                <label for="subjudul_laporan">Sub Judul</label>
                <input type="text" class="form-control text-input" name="subjudul_laporan[]" placeholder="Enter sub judul" required/>
            </div>
            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea class="form-control" name="deskripsi[]" placeholder="Enter deskripsi" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label for="file-input">File</label>
                <input type="file" accept="image/*,video/*,*/*" class="form-control file-input" name="files[]"/>
            </div>
            <button type="button" class="btn btn-danger btn-sm ml-2 remove-input-group-btn">Remove</button>
        `;

        container.appendChild(newInputGroup);
        attachRemoveEvent(newInputGroup);
    });

    function attachRemoveEvent(element) {
        element.querySelector('.remove-input-group-btn').addEventListener('click', function () {
            element.remove();
        });
    }
});
</script>
@endsection
