<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Для обычных пользователей - возможно профиль или список пользователей
        $users = User::where('role', User::ROLE_USER)
                    ->latest()
                    ->paginate(12);
        
        return view('users.index', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->loadCount(['recipes', 'reviews', 'favorites']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('users.edit', compact('user'));
    }

    /**
     * Update the user's profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:2048',
        ]);

        // Обработка загрузки аватара
        if ($request->hasFile('avatar')) {
            // Удаляем старый аватар
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Обработка загрузки обложки
        if ($request->hasFile('cover_image')) {
            // Удаляем старую обложку
            if ($user->cover_image) {
                Storage::disk('public')->delete($user->cover_image);
            }
            $validated['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.edit')
            ->with('success', 'Профиль успешно обновлен.');
    }

    /**
     * Update user's password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => 'required|min:8|confirmed',
        ]);

        $user->update([
            'password' => bcrypt($validated['password'])
        ]);

        return redirect()->route('profile.edit')
            ->with('success', 'Пароль успешно изменен.');
    }

    /**
     * Show user's recipes
     */
    public function recipes(User $user)
    {
        $recipes = $user->recipes()->with(['category', 'user'])
                    ->latest()
                    ->paginate(12);
        
        return view('users.recipes', compact('user', 'recipes'));
    }

    /**
     * Show user's favorites
     */
    public function favorites()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with(['category', 'user'])
                        ->latest()
                        ->paginate(12);
        
        return view('users.favorites', compact('favorites'));
    }

    /**
     * Show user's reviews
     */
    public function reviews(User $user)
    {
        $reviews = $user->reviews()->with(['recipe', 'user'])
                    ->latest()
                    ->paginate(12);
        
        return view('users.reviews', compact('user', 'reviews'));
    }
}