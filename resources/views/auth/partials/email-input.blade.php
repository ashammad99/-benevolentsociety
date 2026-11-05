{{-- @include('auth.partials.email-input', ['value' => ..., 'autofocus' => bool]) --}}
<div class="form-group">
    <label class="form-label" for="email">
        <span class="en">Email address</span>
        <span class="ar">البريد الإلكتروني</span>
    </label>
    <div class="input-wrap">
        <input
            class="form-input"
            type="email"
            id="email"
            name="email"
            autocomplete="email"
            value="{{ $value ?? '' }}"
            required
            @if (! empty($autofocus)) autofocus @endif
            placeholder="you@example.com"
        >
        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round">
            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
            <polyline points="22,6 12,13 2,6"/>
        </svg>
    </div>
</div>
