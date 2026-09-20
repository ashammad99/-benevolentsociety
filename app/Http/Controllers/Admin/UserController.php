<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // ── Index ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = User::withCount('posts')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $users = $query->paginate(20)->withQueryString();

        $stats = [
            'total'   => User::count(),
            'admins'  => User::where('role', 'admin')->count(),
            'editors' => User::where('role', 'editor')->count(),
            'viewers' => User::where('role', 'viewer')->count(),
            'active'  => User::where('is_active', true)->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    // ── Create form ────────────────────────────────────────────────────────
    public function create()
    {
        return view('admin.users.create');
    }

    // ── Store ──────────────────────────────────────────────────────────────
    public function store(UserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('User created successfully.'));
    }

    // ── Edit form ──────────────────────────────────────────────────────────
    public function edit(User $user)
    {
        // Prevent non-super-admins from editing other admins
        $this->authorize('update', $user);

        return view('admin.users.edit', compact('user'));
    }

    // ── Update ─────────────────────────────────────────────────────────────
    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $data = $request->validated();

        // Only update password if a new one was provided
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Prevent the only admin from demoting themselves
        if ($user->id === Auth::id() && ($data['role'] ?? $user->role) !== 'admin') {
            return back()->withErrors(['role' => __('You cannot change your own role.')]);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('User updated successfully.'));
    }

    // ── Destroy ────────────────────────────────────────────────────────────
    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        // Prevent self-deletion
        if ($user->id === Auth::id()) {
            return back()->withErrors(['user' => __('You cannot delete your own account.')]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', __('User deleted.'));
    }

    // ── Toggle active flag ─────────────────────────────────────────────────
    public function toggleActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['user' => __('You cannot deactivate your own account.')]);
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', __('User status updated.'));
    }
}
