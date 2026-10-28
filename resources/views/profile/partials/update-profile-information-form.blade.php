<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6">
        @csrf
        @method('patch')

        <div class="mb-3">
            <label for="user_name" class="form-label fw-bold">Nombre de Usuario</label>
            <input id="user_name" name="user_name" type="text" class="form-control @error('user_name') is-invalid @enderror" value="{{ old('user_name', $user->user_name) }}" required autofocus autocomplete="username">
            @error('user_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex align-items-center gap-3">
            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Guardar Cambios</button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small animated fade-out">Guardado.</span>
            @endif
        </div>
    </form>
</section>
