<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdRequest;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Ad::with('user')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $ads = $query->paginate(15)->withQueryString();

        $stats = [
            'active'  => Ad::where('status', 'active')->count(),
            'paused'  => Ad::where('status', 'paused')->count(),
            'expired' => Ad::where('status', 'expired')->count(),
            'banners' => Ad::where('type', 'banner')->count(),
            'popups'  => Ad::where('type', 'popup')->count(),
        ];

        return view('admin.ads.index', compact('ads', 'stats'));
    }

    // ── Create form ────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.ads.create');
    }

    // ── Store ──────────────────────────────────────────────────────────────
    public function store(AdRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        $ad = Ad::create($data);

        return redirect()
            ->route('admin.ads.index')
            ->with('success', __('Ad created successfully.'));
    }

    // ── Edit form ──────────────────────────────────────────────────────────
    public function edit(Ad $ad)
    {
        return view('admin.ads.edit', compact('ad'));
    }

    // ── Update ─────────────────────────────────────────────────────────────
    public function update(AdRequest $request, Ad $ad)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($ad->image_path) {
                Storage::disk('public')->delete($ad->image_path);
            }
            $data['image_path'] = $request->file('image')->store('ads', 'public');
        }

        if ($request->boolean('remove_image') && $ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
            $data['image_path'] = null;
        }

        $ad->update($data);

        return redirect()
            ->route('admin.ads.index')
            ->with('success', __('Ad updated successfully.'));
    }

    // ── Destroy ────────────────────────────────────────────────────────────
    public function destroy(Ad $ad)
    {
        if ($ad->image_path) {
            Storage::disk('public')->delete($ad->image_path);
        }

        $ad->delete();

        return redirect()
            ->route('admin.ads.index')
            ->with('success', __('Ad deleted.'));
    }

    // ── Toggle status ──────────────────────────────────────────────────────
    public function toggleStatus(Ad $ad)
    {
        $newStatus = $ad->status === 'active' ? 'paused' : 'active';
        $ad->update(['status' => $newStatus]);

        return back()->with('success', __("Ad status changed to {$newStatus}."));
    }
}
