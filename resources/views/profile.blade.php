@extends('layouts.app')

@section('title', 'Profile')
@section('fullbleed', true)

@section('content')
<div class="container py-5 text-white">
  <div class="row justify-content-center">
    <div class="col-12 text-center mb-5">
      <h2 class="fw-bold text-white mb-2">Profile</h2>
      <p class="text-white-50 text-lg">Manage your account details and public profile.</p>
    </div>

    <div class="col-lg-10">
      <div class="glass-card p-4 p-md-5">
        <div class="row g-5 align-items-start">

          {{-- ========= LEFT: Avatar + Public Link ========= --}}
          <div class="col-md-5 d-flex flex-column align-items-center">

            {{-- AVATAR: kotak. Jika foto ada => img, jika tidak => kotak dengan inisial tengah --}}
            <template id="tmpl-avatar-fallback">
            <div class="avatar-fallback d-flex align-items-center justify-content-center bg-white-5 border border-white-10 rounded-3">
                <span class="avatar-initials-text text-primary-500 fw-bold" style="font-size: 4rem;">{{ $initials }}</span>
            </div>
            </template>

            <div class="avatar-square position-relative overflow-hidden mb-4 rounded-3 shadow-lg" style="width: 100%; aspect-ratio: 1/1; max-width: 320px;">
            @if($photoUrl)
                {{-- Ada foto → tampil IMG. Jika gagal load, otomatis ganti ke fallback inisial --}}
                <img
                src="{{ $photoUrl }}"
                alt="Profile Picture"
                class="w-100 h-100 object-fit-cover"
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
                <div class="avatar-fallback w-100 h-100 d-flex align-items-center justify-content-center bg-white-5 border border-white-10">
                <span class="avatar-initials-text text-primary-500 fw-bold" style="font-size: 4rem;">{{ $initials }}</span>
                </div>
            @endif
            </div>

           <div class="d-flex justify-content-center align-items-center gap-2 w-100">
    {{-- === Form Ubah Gambar === --}}
    <form id="avatarForm"
          action="{{ route('profile.picture') }}"
          method="POST"
          enctype="multipart/form-data"
          class="m-0 p-0 flex-grow-1">
        @csrf
        <input type="file" id="avatarInput" name="avatar" class="d-none" accept="image/*">

        <button type="button" class="btn btn-custom2 w-100" id="changeAvatarBtn">
            Change Picture
        </button>
    </form>

    {{-- === Form Hapus Gambar (terpisah total) === --}}
    @if(!empty($photoUrl))
        <form action="{{ route('profile.picture.delete') }}"
              method="POST"
              onsubmit="return confirm('Remove current profile picture?');"
              class="m-0 p-0">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="btn btn-outline-danger d-flex align-items-center justify-content-center"
                    style="width:42px;height:42px;">
                <i class="bi bi-trash"></i>
            </button>
        </form>
    @endif
</div>

@error('avatar')
    <div class="text-danger small mt-2 text-center">{{ $message }}</div>
@enderror

            {{-- Public link + copy --}}
            <div class="w-100 mt-4 text-start p-3 rounded-3 bg-white-5 border border-white-10">
              <label class="form-label text-white-50 small fw-semibold mb-2">Public Profile Link</label>
              <div class="input-group">
                <input type="text" id="publicLink" class="form-control glass-form-control border-end-0 text-white-50"
                        value="{{ $publicUrl }}" readonly style="background: rgba(0,0,0,0.2);">
                <button
                    class="btn btn-outline-light border-start-0"
                    type="button"
                    id="copyLinkBtn"
                    data-copy-target="#publicLink"
                    aria-label="Copy public profile link"
                    title="Copy"
                    style="background: rgba(0,0,0,0.2); border-color: rgba(255,255,255,0.1);">
                    <i class="bi bi-clipboard" id="copyIcon"></i>
                </button>
                </div>
              <small class="text-white-50 mt-2 d-block">Share this link to showcase your analytics.</small>
            </div>
          </div>

          {{-- ========= RIGHT: Form ========= --}}
          <div class="col-md-7">
            <h4 class="text-white fw-bold mb-4 border-bottom border-white-10 pb-3">Account Details</h4>
            <form method="POST" action="{{ route('profile.update') }}">
              @csrf

              <div class="row">
                  <div class="col-md-6 mb-4">
                    <label class="form-label text-white-50 small fw-semibold">First Name</label>
                    <input type="text" name="first_name" class="form-control glass-form-control text-white"
                           value="{{ old('first_name',$user->first_name) }}" required
                           style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                    @error('first_name') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>

                  <div class="col-md-6 mb-4">
                    <label class="form-label text-white-50 small fw-semibold">Last Name</label>
                    <input type="text" name="last_name" class="form-control glass-form-control text-white"
                           value="{{ old('last_name',$user->last_name) }}" required
                           style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                    @error('last_name') <small class="text-danger">{{ $message }}</small> @enderror
                  </div>
              </div>

              <div class="mb-4">
                <label class="form-label text-white-50 small fw-semibold">Username</label>
                <div class="input-group">
                    <span class="input-group-text bg-white-5 border-white-10 text-white-50" style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">@</span>
                    <input type="text" name="username" class="form-control glass-form-control text-white"
                           value="{{ old('username',$user->username) }}" required
                           style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                </div>
                @error('username') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="mb-5">
                <label class="form-label text-white-50 small fw-semibold">Email</label>
                <input type="email" name="email" class="form-control glass-form-control text-white"
                       value="{{ old('email',$user->email) }}" required
                       style="background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
              </div>

              <div class="d-flex justify-content-end pt-3 border-top border-white-10">
                <button type="submit" class="btn btn-custom2 px-5 py-2 fw-bold">Save Changes</button>
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
