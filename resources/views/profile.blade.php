@extends('layouts.app-auth')

@section('title', 'Profile')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
  <div class="row justify-content-center">
    <div class="col-12 text-center mb-5">
      <h2 class="fw-bold text-primary-500">Profile</h2>
      <p class="text-primary-500">Manage your account details and public profile.</p>
    </div>

    <div class="col-lg-10">
      <div class="card bg-black-200 rounded-lg shadow-xl p-4 p-md-5">
        <div class="row g-4 align-items-start">

          {{-- ========= LEFT: Avatar + Public Link ========= --}}
          <div class="col-md-5 d-flex flex-column align-items-center">

            {{-- AVATAR: kotak. Jika foto ada => img, jika tidak => kotak dengan inisial tengah --}}
            <template id="tmpl-avatar-fallback">
            <div class="avatar-fallback d-flex align-items-center justify-content-center">
                <span class="avatar-initials-text">{{ $initials }}</span>
            </div>
            </template>

            <div class="avatar-square position-relative overflow-hidden mb-3" style="--avatar-size: 320px">
            @if($photoUrl)
                {{-- Ada foto → tampil IMG. Jika gagal load, otomatis ganti ke fallback inisial --}}
                <img
                src="{{ $photoUrl }}"
                alt="Profile Picture"
                class="avatar-img"
                loading="lazy"
                onerror="
                    (function(el){
                    try{
                        var tmpl = document.getElementById('tmpl-avatar-fallback');
                        if(tmpl && el && el.parentElement){
                        el.parentElement.appendChild(tmpl.content.cloneNode(true));
                        }
                    }catch(e){}
                    if(el) el.remove();
                    })(this);
                "
                >
            @else
                {{-- Tidak ada foto → langsung tampilkan inisial --}}
                <div class="avatar-fallback d-flex align-items-center justify-content-center">
                <span class="avatar-initials-text">{{ $initials }}</span>
                </div>
            @endif
            </div>

            {{-- Change picture --}}
            <form id="avatarForm" action="{{ route('profile.picture') }}" method="POST" enctype="multipart/form-data" class="w-100 text-center">
              @csrf
              <input type="file" id="avatarInput" name="avatar" class="d-none" accept="image/*">
              <button type="button" class="btn btn-custom2 px-4" id="changeAvatarBtn">Change Profile Picture</button>
              @error('avatar') <div class="text-danger small mt-2">{{ $message }}</div> @enderror
            </form>

            {{-- Public link + copy --}}
            <div class="w-100 mt-3 text-start">
              <label class="form-label text-white-400 small fw-semibold">Public Profile Link</label>
              <div class="input-group input-group-copy">
                <input type="text" id="publicLink" class="form-control input-custom cp-input"
                        value="{{ $publicUrl }}" readonly>
                <button
                    class="btn btn-copy-like-input"
                    type="button"
                    id="copyLinkBtn"
                    data-copy-target="#publicLink"
                    aria-label="Copy public profile link"
                    title="Copy">
                    <i class="bi bi-clipboard" id="copyIcon"></i>
                </button>
                </div>
              <small class="text-white-400">Share this link to showcase your analytics.</small>
            </div>
          </div>

          {{-- ========= RIGHT: Form ========= --}}
          <div class="col-md-7">
            <form method="POST" action="{{ route('profile.update') }}">
              @csrf

              <div class="mb-3">
                <label class="form-label text-white-400 small fw-semibold">First Name</label>
                <input type="text" name="first_name" class="form-control input-custom"
                       value="{{ old('first_name',$user->first_name) }}" required>
                @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="mb-3">
                <label class="form-label text-white-400 small fw-semibold">Last Name</label>
                <input type="text" name="last_name" class="form-control input-custom"
                       value="{{ old('last_name',$user->last_name) }}" required>
                @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="mb-3">
                <label class="form-label text-white-400 small fw-semibold">Username</label>
                <input type="text" name="username" class="form-control input-custom"
                       value="{{ old('username',$user->username) }}" required>
                @error('username') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="mb-4">
                <label class="form-label text-white-400 small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control input-custom"
                       value="{{ old('email',$user->email) }}" required>
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="d-flex justify-content-end">
                <button type="submit" class="btn btn-custom2 px-4  mt-4">Confirm Changes</button>
              </div>
            </form>
          </div>

        </div>{{-- row --}}
      </div>{{-- card --}}
    </div>
  </div>
</div>
@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const changeBtn  = document.getElementById('changeAvatarBtn');
  const input      = document.getElementById('avatarInput');
  const form       = document.getElementById('avatarForm');

  if (changeBtn && input && form) {
    changeBtn.addEventListener('click', () => input.click());
    input.addEventListener('change', () => { if (input.files && input.files[0]) form.submit(); });
  }

  // --- Floating text helper ---
  function showFloat(message, ms = 1600, anchorEl = null, offset = {x: 0, y: 8}) {
    const el = document.createElement('div');
    el.className = 'float-toast';
    el.textContent = message;
    el.style.visibility = 'hidden';  // biar bisa ukur dulu
    document.body.appendChild(el);

    // Hitung posisi relatif tombol (anchor)
    if (anchorEl) {
        const rect = anchorEl.getBoundingClientRect();
        const vwScrollY = window.scrollY || document.documentElement.scrollTop;
        const vwScrollX = window.scrollX || document.documentElement.scrollLeft;

        // Setelah masuk DOM kita bisa ukur width/height toast
        const toastW = el.offsetWidth;
        const toastH = el.offsetHeight;

        // Posisi: tepat DI BAWAH tombol, rata kanan dengan tombol
        const top  = rect.bottom + vwScrollY + offset.y;
        const left = rect.right  + vwScrollX - toastW + offset.x;

        el.style.top  = `${top}px`;
        el.style.left = `${Math.max(8, left)}px`;   // jaga jangan mentok kiri
    } else {
        // fallback kalau anchorEl null → pojok kanan atas
        el.style.top  = '16px';
        el.style.right = '16px';
    }

    // Tampilkan animasinya
    el.style.visibility = 'visible';
    requestAnimationFrame(() => el.classList.add('show'));

    setTimeout(() => {
        el.classList.remove('show');
        setTimeout(() => el.remove(), 250);
    }, ms);
    }

  // --- Copy robust with fallback ---
  function copyText(text){
    if (navigator.clipboard && navigator.clipboard.writeText) {
      return navigator.clipboard.writeText(text);
    }
    return new Promise(function(resolve, reject){
      try{
        const ta = document.createElement('textarea');
        ta.value = text;
        ta.setAttribute('readonly', '');
        ta.style.position = 'fixed';
        ta.style.top = '-9999px';
        document.body.appendChild(ta);
        ta.select();
        const ok = document.execCommand('copy');
        document.body.removeChild(ta);
        ok ? resolve() : reject(new Error('execCommand failed'));
      }catch(err){ reject(err); }
    });
  }

  const copyBtn  = document.getElementById('copyLinkBtn');
  const copyIcon = document.getElementById('copyIcon');

  if (copyBtn) {
    copyBtn.addEventListener('click', function(){
      const targetSel = copyBtn.getAttribute('data-copy-target') || '#publicLink';
      const target    = document.querySelector(targetSel);
      const value     = target ? target.value : '';

      copyText(value).then(() => {
        showFloat('link profile copied!', 1600, copyBtn, {x: 0, y: 8});
        if (copyIcon) {
            copyIcon.classList.remove('bi-clipboard');
            copyIcon.classList.add('bi-clipboard-check');
            setTimeout(() => {
            copyIcon.classList.remove('bi-clipboard-check');
            copyIcon.classList.add('bi-clipboard');
            }, 1400);
        }
        if (target && target.blur) target.blur(); // hilangkan fokus agar tidak ada highlight
        }).catch(() => {
        showFloat('copy failed. select & copy manually.', 2000, copyBtn, {x: 0, y: 8});
        // hanya seleksi saat gagal
        if (target && target.select) {
            target.select();
            target.setSelectionRange(0, 99999);
        }
        });
    });
  }

  // Toastr flashes (jika kamu pakai toastr untuk flash server-side)
  @if(session('success')) window.toastr && toastr.success(@json(session('success'))); @endif
  @if(session('error'))   window.toastr && toastr.error(@json(session('error')));   @endif
  @if($errors->any())     window.toastr && toastr.error(@json($errors->first()));   @endif
});
</script>
@endpush
