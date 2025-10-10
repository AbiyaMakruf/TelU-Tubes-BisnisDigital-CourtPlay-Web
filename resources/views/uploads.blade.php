@extends('layouts.app-auth')

@section('title', 'Uploads')

@section('content')
<div class="container py-5 text-center text-white">
    <h2 class="fw-bold text-primary-500 mb-4">Upload Your Video</h2>

    {{-- Form upload --}}
    <form id="uploadForm" method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Drop area --}}
        <div id="drop-area"
             class="border border-primary-500 rounded-3 p-5 mb-3 bg-black-200 d-flex flex-column justify-content-center align-items-center"
             style="cursor: pointer;">
            <i class="bi bi-cloud-arrow-up fs-1 text-primary-500 mb-2"></i>
            <p class="mb-0">Drag & Drop your video here<br>
               <small class="text-white-300">or click to select a file</small>
            </p>
            <input type="file" name="video" id="video" accept="video/*" hidden>
        </div>

        {{-- File name preview --}}
        <p id="file-name" class="text-primary-500 fw-semibold mb-3" style="display:none;"></p>

        {{-- Tombol upload --}}
        <button type="submit" class="btn btn-custom2 px-4 mt-3">Upload</button>
    </form>
</div>

{{-- Script sederhana --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('video');
    const fileName = document.getElementById('file-name');

    // Klik area => buka file dialog
    dropArea.addEventListener('click', () => fileInput.click());

    // Drag & Drop file
    dropArea.addEventListener('dragover', e => {
        e.preventDefault();
        dropArea.classList.add('border-3');
    });
    dropArea.addEventListener('dragleave', e => {
        dropArea.classList.remove('border-3');
    });
    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        dropArea.classList.remove('border-3');
        fileInput.files = e.dataTransfer.files;
        showFileName(fileInput.files[0]);
    });

    // Saat file dipilih manual
    fileInput.addEventListener('change', e => {
        if (e.target.files.length > 0) {
            showFileName(e.target.files[0]);
        }
    });

    // Tampilkan nama file
    function showFileName(file) {
        fileName.textContent = `Selected: ${file.name}`;
        fileName.style.display = 'block';
    }
});
</script>
@endsection
