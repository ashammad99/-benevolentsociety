{{-- Status message (e.g. "reset link sent") and the first validation error. --}}
@if (session('status') && session('status') !== 'verification-link-sent')
    <div class="alert alert-success" role="status">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-error" role="alert">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span>{{ $errors->first() }}</span>
    </div>
@endif
