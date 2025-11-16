<ul class="list-unstyled ">
    @foreach ($users as $user)
        <li class="mb-3 d-flex justify-content-start">

            <!-- Profile Circle (link ke public profile) -->
            <a href="{{ route('user.profile', $user->username) }}" class="d-flex align-items-center">
                <div class="avatar-circle">
                    @if($user->profile_picture_url)
                        <img src="{{ $user->profile_picture_url }}"
                             alt="{{ $user->username }}"
                             class="avatar-img2">
                    @else
                        <span class="avatar-initials-text2">
                            {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                        </span>
                    @endif
                </div>
            </a>

            <!-- Username + Followers -->
            <div class="ms-3">
                <a href="{{ route('user.profile', $user->username) }}"
                   class="text-primary-500 d-flex justify-content-start align-items-center">
                    <span class="fw-semibold">{{ $user->username }}</span>
                </a>

                <div class="small text-primary-300 d-flex justify-content-start">
                    {{ $user->followers_count ?? 0 }} Followers
                </div>
            </div>

        </li>
    @endforeach
</ul>
