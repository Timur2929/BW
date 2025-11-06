<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['recipes', 'reviews', 'favorites'])
                    ->latest()
                    ->paginate(10);
        
        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $validated['password'] = bcrypt($validated['password']);

        // Обработка загрузки аватара
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('users/avatars', 'public');
        }

        // Обработка загрузки обложки
        if ($request->hasFile('cover_image')) {
            $validated['cover_image'] = $request->file('cover_image')->store('users/covers', 'public');
        }

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно создан.');
    }

    public function show(User $user)
    {
        $user->loadCount(['recipes', 'reviews', 'favorites']);
        
        return Inertia::render('Admin/Users/Show', [
            'user' => $user
        ]);
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:user,admin',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Обработка загрузки аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('users/avatars', 'public');
        } else {
            // Если файл не загружен, оставляем старый аватар
            $validated['avatar'] = $user->avatar;
        }

        // Обработка загрузки обложки
        if ($request->hasFile('cover_image')) {
            // Удаляем старую обложку
            if ($user->cover_image) {
                Storage::disk('public')->delete($user->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('users/covers', 'public');
        } else {
            // Если файл не загружен, оставляем старую обложку
            $validated['cover_image'] = $user->cover_image;
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно обновлен.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Вы не можете удалить свой собственный аккаунт.');
        }

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }
        if ($user->cover_image) {
            Storage::disk('public')->delete($user->cover_image);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Пользователь успешно удален.');
    }
}