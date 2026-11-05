<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\SanitizesRichText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    use SanitizesRichText;

    // Bilingual labels for settings not seeded up front (social links, SEO
    // meta) — upsert() needs these because label_en/label_ar are NOT NULL
    // columns, so a first-time insert without them would fail.
    private const LABELS = [
        'facebook' => ['Facebook URL', 'رابط فيسبوك'],
        'instagram' => ['Instagram URL', 'رابط انستغرام'],
        'twitter' => ['Twitter / X URL', 'رابط تويتر'],
        'whatsapp' => ['WhatsApp Number', 'رقم واتساب'],
        'youtube' => ['YouTube Channel', 'قناة يوتيوب'],
        'linkedin' => ['LinkedIn URL', 'رابط لينكدإن'],
        'meta_title' => ['Meta Title', 'عنوان السيو'],
        'meta_description' => ['Meta Description', 'وصف السيو'],
        'about_message' => ['Vision & Aims', 'الرؤية والأهداف'],
    ];

    // ── Show all settings (tabbed view) ────────────────────────────────────
    public function index()
    {
        // Load all settings keyed by their `key` column for easy access in Blade:
        // $settings['email']->value, $settings['vision']->value_en, etc.
        $settings = Setting::all()->keyBy('key');

        return view('admin.settings.index', compact('settings'));
    }

    // ── Save contact tab ───────────────────────────────────────────────────
    public function saveContact(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:191'],
            'phone' => ['required', 'string', 'max:30'],
            'address_en' => ['nullable', 'string', 'max:500'],
            'address_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $this->upsert('email', 'email', ['value' => $data['email']]);
        $this->upsert('phone', 'phone', ['value' => $data['phone']]);
        $this->upsert('address', 'richtext', ['value_en' => $data['address_en'] ?? null, 'value_ar' => $data['address_ar'] ?? null]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', __('Contact settings saved.'));
    }

    // ── Save about / vision tab ────────────────────────────────────────────
    public function saveAbout(Request $request)
    {
        // The text shown on the public Vision & Aims page. It comes from a
        // contenteditable rich-text editor and is rendered with {!! !!} (also
        // as old() input after a failed save), so sanitize before validating,
        // the same way PostRequest does for post content. An editor left empty
        // still submits markup such as "<br>", which is stored as null.
        foreach (['about_en', 'about_ar'] as $field) {
            $value = $request->input($field);
            if (is_string($value)) {
                $clean = $this->sanitizeRichText($value);
                $request->merge([$field => trim(strip_tags($clean)) === '' ? null : $clean]);
            }
        }

        $data = $request->validate([
            'about_en' => ['nullable', 'string', 'max:20000'],
            'about_ar' => ['nullable', 'string', 'max:20000'],
        ]);

        $this->upsert('about_message', 'richtext', [
            'value_en' => $data['about_en'] ?? null,
            'value_ar' => $data['about_ar'] ?? null,
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', __('About settings saved.'));
    }

    // ── Save social media tab ──────────────────────────────────────────────
    public function saveSocial(Request $request)
    {
        $data = $request->validate([
            'facebook' => ['nullable', 'url', 'max:500'],
            'instagram' => ['nullable', 'url', 'max:500'],
            'twitter' => ['nullable', 'url', 'max:500'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'youtube' => ['nullable', 'url', 'max:500'],
            'linkedin' => ['nullable', 'url', 'max:500'],
        ]);

        foreach ($data as $key => $value) {
            $this->upsert($key, 'url', ['value' => $value]);
        }

        return redirect()
            ->route('admin.settings.index')
            ->with('success', __('Social media links saved.'));
    }

    // ── Save SEO tab ───────────────────────────────────────────────────────
    public function saveSeo(Request $request)
    {
        $data = $request->validate([
            'meta_title_en' => ['nullable', 'string', 'max:191'],
            'meta_title_ar' => ['nullable', 'string', 'max:191'],
            'meta_description_en' => ['nullable', 'string', 'max:500'],
            'meta_description_ar' => ['nullable', 'string', 'max:500'],
        ]);

        $this->upsert('meta_title', 'text', ['value_en' => $data['meta_title_en'] ?? null,       'value_ar' => $data['meta_title_ar'] ?? null]);
        $this->upsert('meta_description', 'text', ['value_en' => $data['meta_description_en'] ?? null, 'value_ar' => $data['meta_description_ar'] ?? null]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', __('SEO settings saved.'));
    }

    // ── Helper: create-or-update a single setting ──────────────────────────
    private function upsert(string $key, string $type, array $values): void
    {
        [$labelEn, $labelAr] = self::LABELS[$key] ?? [Str::headline($key), $key];

        // firstOrCreate (not updateOrCreate) so an existing row's label_en/
        // label_ar — e.g. the richer labels from SettingsSeeder — are left
        // alone; only a brand-new row gets the fallback label above.
        $setting = Setting::firstOrCreate(['key' => $key], [
            'label_en' => $labelEn,
            'label_ar' => $labelAr,
            'type' => $type,
        ]);

        $setting->update(array_merge(['type' => $type, 'updated_by' => Auth::id()], $values));
    }
}
