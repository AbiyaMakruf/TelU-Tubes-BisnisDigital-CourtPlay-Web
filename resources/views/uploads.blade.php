@extends('layouts.app')

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

    <div class="card bg-black-200 rounded-lg shadow-xl p-4 p-md-5">
        <form id="uploadForm" method="POST" action="{{ route('videos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="row align-items-stretch">
                <div class="col-lg-6 mb-4 mb-lg-0 d-flex flex-column">
                    <div id="drop-area"
                        class="bg-black-300 rounded-xl p-5 d-flex flex-column justify-content-center align-items-center flex-grow-1 @if ($hasReachedLimit) disabled-area @endif"
                        style="min-height: 400px; cursor: pointer; transition: all 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">

                        <svg class="text-primary-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                             xmlns="http://www.w3.org/2000/svg" style="width: 80px; height: 80px;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>

                        <p class="mb-1 text-primary-500 fs-5 fw-medium">
                            @if ($hasReachedLimit)
                                LIMIT REACHED
                            @else
                                Drag & Drop your video here
                            @endif
                        </p>

                        @php
                            $allowedExt = implode(', ', explode(',', env('UPLOAD_ALLOWED_MIMES', 'mp4,mov,avi')));
                        @endphp
                        <p class="mb-4 text-primary-500 small">
                            or click to select a file (Max {{ $maxUploadMb }} MB, types: {{ $allowedExt }})
                        </p>

                        <input
                            type="file"
                            name="video"
                            id="video"
                            accept="video/mp4,video/mov,video/avi,video/quicktime,video/x-msvideo,video/*"
                            hidden
                            @if ($hasReachedLimit) disabled @endif
                            required
                        >

                        <p id="file-name" class="text-primary-500 fw-semibold mt-3 text-break text-center" style="display:none;"></p>

                        @error('video')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-6 d-flex flex-column">
                    <div class="flex-grow-1">
                        <h4 class="text-primary-500 fw-bold mb-4">Project Details</h4>

                        <div class="mb-3 text-start">
                            <label for="project_name" class="form-label text-white-400 small fw-semibold">Project Name</label>
                            <input
                                type="text"
                                name="project_name"
                                id="project_name"
                                class="form-control input-custom bg-black-300 border-0 text-black-300 rounded-lg p-3"
                                placeholder="e.g., Training - 2"
                                value="{{ old('project_name') }}"
                                @if ($hasReachedLimit) disabled @endif
                                required
                            >
                            @error('project_name')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4 text-start">
                            <label for="description" class="form-label text-white-400 small fw-semibold">Description (Optional)</label>
                            <textarea
                                name="description"
                                id="description"
                                rows="5"
                                class="form-control input-custom bg-black-300 border-0 text-black-300  rounded-lg p-3"
                                placeholder="Describe the session or player..."
                                @if ($hasReachedLimit) disabled @endif
                            >{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger mt-1">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="text-start small mb-4">
                            @if ($hasReachedLimit)
                                <div class="text-danger small fw-semibold">
                                    <i class="bi bi-exclamation-triangle-fill me-1"></i> VIDEO UPLOAD HAS REACHED ITS LIMIT
                                </div>
                            @else
                                <span class="text-primary-500">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Video Limit: {{ $projectCount }} / {{ $maxLimit }} • Max file: {{ $maxUploadMb }} MB
                                </span>
                            @endif
                        </div>
                    </div>

                    <button
                        type="submit"
                        id="submitBtn"
                        class="btn btn-custom2 w-100 py-3 mt-auto"
                        @if ($hasReachedLimit) disabled @endif
                        disabled
                    >
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
    const hasReachedLimit = {{ $hasReachedLimit ? 'true' : 'false' }};
    const maxUploadMb = {{ (int) $maxUploadMb }};
    const maxFileSize = maxUploadMb * 1024 * 1024;

    if (hasReachedLimit) {
        submitBtn.textContent = 'Limit Reached';
        submitBtn.disabled = true;
        return;
    }

    function updateFileNameDisplay(file) {
        fileNameDisplay.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
        fileNameDisplay.style.display = 'block';
    }

    function validateForm(file) {
        const isFileSelected = !!file && file.size > 0;
        const isNameFilled = projectInput.value.trim().length > 0;
        let isValid = isFileSelected && isNameFilled;

        if (isFileSelected && file.size > maxFileSize) {
            fileNameDisplay.textContent = 'File too large: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB (Max ' + maxUploadMb + ' MB)';
            fileNameDisplay.classList.remove('text-primary-500');
            fileNameDisplay.classList.add('text-danger');
            if (typeof toastr !== 'undefined') {
                toastr.error('The video may not be greater than ' + maxUploadMb + ' MB.');
            }
            isValid = false;
        } else if (isFileSelected) {
            fileNameDisplay.classList.remove('text-danger');
            fileNameDisplay.classList.add('text-primary-500');
            updateFileNameDisplay(file);
        } else {
            fileNameDisplay.style.display = 'none';
        }

        submitBtn.disabled = !isValid;
    }

    dropArea.addEventListener('mouseenter', () => {
        dropArea.style.boxShadow = '0 0 0 2px #a3ce14';
    });
    dropArea.addEventListener('mouseleave', () => {
        dropArea.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
    });
    dropArea.addEventListener('click', () => fileInput.click());

    dropArea.addEventListener('dragover', e => {
        e.preventDefault();
        dropArea.style.boxShadow = '0 0 0 3px #f4fdca';
        dropArea.style.backgroundColor = '#1c1c1c';
    });
    dropArea.addEventListener('dragleave', () => {
        dropArea.style.boxShadow = '0 4px 6px rgba(0,0,0,0.1)';
        dropArea.style.backgroundColor = '#292929';
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

    fileInput.addEventListener('change', e => {
        if (e.target.files.length > 0) {
            validateForm(e.target.files[0]);
        } else {
            fileNameDisplay.style.display = 'none';
            validateForm(null);
        }
    });

    projectInput.addEventListener('input', () => {
        validateForm(fileInput.files[0] || null);
    });

    document.getElementById('uploadForm').addEventListener('submit', function() {
        if (!submitBtn.disabled) {
            submitBtn.disabled = true;
            btnText.textContent = 'Uploading... Please wait';
            loadingSpinner.classList.remove('d-none');
        }
    });

    validateForm(fileInput.files[0] || null);

    @if(session('toastr'))
        (function () {
            var n = @json(session('toastr'));
            if (Array.isArray(n)) {
                n.forEach(function(item){
                    if (item && item.type && item.message && typeof toastr[item.type] === 'function') {
                        toastr[item.type](item.message);
                    }
                });
            } else if (n && n.type && n.message && typeof toastr[n.type] === 'function') {
                toastr[n.type](n.message);
            }
        })();
    @endif

    @if(session('success'))
        toastr.success(@json(session('success')));
    @endif

    @if($errors->any())
        toastr.error(@json($errors->first()));
    @endif
});
</script>
@endsection
