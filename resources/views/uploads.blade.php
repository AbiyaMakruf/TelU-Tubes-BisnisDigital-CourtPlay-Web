@extends('layouts.app-auth')

@section('title', 'Uploads')
@section('fullbleed', true)

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-12 text-center mb-5">
            <h2 class="fw-bold text-primary-500">Upload Your Video for Analysis</h2>
            <p class="text-primary-500">Start your journey to better performance with CourtPlay AI.</p>
        </div>
    </div>

    {{-- Kartu Utama (Kontainer Upload & Form) - Borderless --}}
    <div class="card bg-black-200 rounded-lg shadow-xl p-4 p-md-5">

        <form id="uploadForm" method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row align-items-stretch">

                {{-- Kiri: Drop Area (Borderless, Fokus pada Background) --}}
                <div class="col-lg-6 mb-4 mb-lg-0 d-flex flex-column">
                    <div id="drop-area"
                        class="bg-black-300 rounded-xl p-5 d-flex flex-column justify-content-center align-items-center flex-grow-1 @if ($hasReachedLimit) disabled-area @endif"
                        style="min-height: 400px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"
                        >

                        {{-- Ikon Besar --}}
                        <svg class="text-primary-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg" style="width: 80px; height: 80px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>

                        {{-- Text Status --}}
                        <p class="mb-1 text-primary-500 fs-5 fw-medium">
                            @if ($hasReachedLimit)
                                LIMIT REACHED
                            @else
                                Drag & Drop your video here
                            @endif
                        </p>
                        <p class="mb-4 text-primary-500 small">or click to select a file (Max 50MB)</p>

                        {{-- Input File Tersembunyi --}}
                        <input type="file" name="video" id="video" accept="video/mp4,video/mov,video/avi" hidden
                                @if ($hasReachedLimit) disabled @endif required>

                        {{-- File name preview --}}
                        <p id="file-name" class="text-primary-500 fw-semibold mt-3 text-break text-center" style="display:none;"></p>

                        @error('video')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Kanan: Form Metadata & Tombol --}}
                <div class="col-lg-6 d-flex flex-column">
                    <div class="flex-grow-1">
                        <h4 class="text-primary-500 fw-bold mb-4">Project Details</h4>

                        {{-- Project Name --}}
                        <div class="mb-3 text-start">
                            <label for="project_name" class="form-label text-white-400 small fw-semibold">Project Name</label>
                            {{-- Input tanpa border eksplisit --}}
                            <input type="text" name="project_name" id="project_name"
                                class="form-control input-custom bg-black-300 border-0 text-black-300 rounded-lg p-3"
                                placeholder="e.g., Training - 2" value="{{ old('project_name') }}"
                                @if ($hasReachedLimit) disabled @endif required>
                            @error('project_name')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4 text-start">
                            <label for="description" class="form-label text-white-400 small fw-semibold">Description (Optional)</label>
                            {{-- Input tanpa border eksplisit --}}
                            <textarea name="description" id="description" rows="5"
                                class="form-control input-custom bg-black-300 border-0 text-black-300  rounded-lg p-3"
                                placeholder="Describe the session or player..."
                                @if ($hasReachedLimit) disabled @endif>{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Info Limit --}}
                        <div class="text-start small mb-4">
                            @if ($hasReachedLimit)
                                <div class="text-danger small fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> VIDEO UPLOAD HAS REACHED ITS LIMIT!
                                </div>
                            @else
                                <span class="text-primary-500">
                                    <i class="bi bi-info-circle me-1"></i> Video Limit: {{ $projectCount }} / {{ $maxLimit }}
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Tombol upload --}}
                    <button type="submit" id="submitBtn" class="btn btn-custom2 w-100 py-3 mt-auto"
                            @if ($hasReachedLimit) disabled @else disabled @endif>
                        <span id="btnText">Start Analysis Upload</span>
                        <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    </button>

                </div>

            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropArea = document.getElementById('drop-area');
    const fileInput = document.getElementById('video');
    const fileNameDisplay = document.getElementById('file-name');
    const submitBtn = document.getElementById('submitBtn');
    const btnText = document.getElementById('btnText');
    const loadingSpinner = document.getElementById('loadingSpinner');
    const projectInput = document.getElementById('project_name');
    const maxFileSize = 50 * 1024 * 1024; // 50MB (Sesuai Controller)
    const hasReachedLimit = {{ $hasReachedLimit ? 'true' : 'false' }}; // Ambil status batas dari Blade

    // Jika batas sudah tercapai, tidak perlu menjalankan logika upload
    if (hasReachedLimit) {
        submitBtn.textContent = 'Limit Reached';
        submitBtn.disabled = true;
        return;
    }

    // --- Fungsi Helper ---
    function updateFileNameDisplay(file) {
        fileNameDisplay.textContent = `${file.name}`;
        fileNameDisplay.style.display = 'block';
    }

    function validateForm(file) {
        const isFileSelected = file && file.size > 0;
        const isNameFilled = projectInput.value.trim().length > 0;
        let isValid = isFileSelected && isNameFilled;

        // Cek batas ukuran file
        if (isFileSelected && file.size > maxFileSize) {
            fileNameDisplay.textContent = `File too large: ${(file.size / 1024 / 1024).toFixed(2)} MB (Max 50MB)`;
            fileNameDisplay.classList.remove('text-primary-500');
            fileNameDisplay.classList.add('text-danger');
            isValid = false;
        } else if (isFileSelected) {
            fileNameDisplay.classList.remove('text-danger');
            fileNameDisplay.classList.add('text-primary-500');
            updateFileNameDisplay(file);
        }

        submitBtn.disabled = !isValid;
    }

    // --- Event Listeners ---
    // Tambahkan visual feedback saat hover di drop area
    dropArea.addEventListener('mouseenter', () => {
        dropArea.style.boxShadow = '0 0 0 2px #a3ce14'; // Ring tipis saat hover
    });
    dropArea.addEventListener('mouseleave', () => {
        dropArea.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'; // Kembali ke shadow normal
    });
    dropArea.addEventListener('click', () => fileInput.click());

    // Drag & Drop visual feedback
    dropArea.addEventListener('dragover', e => {
        e.preventDefault();
        dropArea.style.boxShadow = '0 0 0 3px #f4fdca'; // Box shadow saat drag over
        dropArea.style.backgroundColor = '#1c1c1c'; // black-200 hover
    });
    dropArea.addEventListener('dragleave', () => {
        dropArea.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)'; // Kembali ke shadow normal
        dropArea.style.backgroundColor = '#292929'; // black-300 normal
    });
    dropArea.addEventListener('drop', e => {
        e.preventDefault();
        dropArea.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
        dropArea.style.backgroundColor = '#292929';
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            validateForm(fileInput.files[0]);
        }
    });

    // Perubahan Input File
    fileInput.addEventListener('change', e => {
        if (e.target.files.length > 0) {
            validateForm(e.target.files[0]);
        } else {
            fileNameDisplay.style.display = 'none';
            validateForm(null);
        }
    });

    // Perubahan Project Name
    projectInput.addEventListener('input', () => {
        validateForm(fileInput.files[0] || null);
    });

    // Form Submission: Tampilkan loading
    document.getElementById('uploadForm').addEventListener('submit', function() {
        if (!submitBtn.disabled) {
            submitBtn.disabled = true;
            btnText.textContent = 'Uploading... Please wait';
            loadingSpinner.classList.remove('d-none');
        }
    });

    // Initial check on load
    validateForm(fileInput.files[0] || null);
});
</script>
@endsection
