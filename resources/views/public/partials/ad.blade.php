{{--
    One ad. @include('public.partials.ad', ['ad' => $ads['inline'] ?? null, 'variant' => 'card'])
    Variants: banner (strip under the menu), card (sidebar/inline), popup (dialog).
--}}
@if ($ad)
  @php($variant = $variant ?? 'card')
  @if ($variant === 'banner')
    <div class="ad-banner">
      @if ($ad->image_url)<img src="{{ $ad->image_url }}" alt="">@endif
      <div class="ad-text">
        <strong>{{ $ad->title }}</strong>
        @if ($ad->body)<span> · {{ $ad->body }}</span>@endif
      </div>
      @if ($ad->url)<a href="{{ $ad->url }}" class="btn-white" style="padding:6px 16px;font-size:13px;" target="_blank" rel="noopener">
        <span class="en">Learn More</span><span class="ar">اعرف أكثر</span>
      </a>@endif
    </div>
  @elseif ($variant === 'popup')
    <div class="ad-popup" id="ad-popup" data-key="{{ $ad->id }}-{{ optional($ad->updated_at)->timestamp }}" hidden>
      <div class="ad-popup-box" role="dialog" aria-modal="true" aria-labelledby="ad-popup-title">
        <button type="button" class="ad-popup-close" aria-label="Close">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
        </button>
        @if ($ad->image_url)<img class="ad-popup-img" src="{{ $ad->image_url }}" alt="">@endif
        <div class="ad-popup-body">
          <div class="ad-label"><span class="en">Announcement</span><span class="ar">إعلان</span></div>
          <h3 id="ad-popup-title">{{ $ad->title }}</h3>
          @if ($ad->body)<p>{{ $ad->body }}</p>@endif
          @if ($ad->url)<a href="{{ $ad->url }}" class="ad-card-link" target="_blank" rel="noopener"><span class="en">Learn More</span><span class="ar">اعرف أكثر</span></a>@endif
        </div>
      </div>
    </div>
  @else
    <aside class="ad-card">
      @if ($ad->image_url)<img src="{{ $ad->image_url }}" alt="">@endif
      <div class="ad-card-body">
        <div class="ad-label"><span class="en">Announcement</span><span class="ar">إعلان</span></div>
        <strong>{{ $ad->title }}</strong>
        @if ($ad->body)<p>{{ $ad->body }}</p>@endif
        @if ($ad->url)<a href="{{ $ad->url }}" class="ad-card-link" target="_blank" rel="noopener"><span class="en">Learn More</span><span class="ar">اعرف أكثر</span></a>@endif
      </div>
    </aside>
  @endif
@endif
