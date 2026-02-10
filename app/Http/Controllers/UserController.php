<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        if (!auth()->user()->isMaster() && !auth()->user()->isOwner()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        if (!auth()->user()->isMaster()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['master', 'owner', 'marketer', 'content_creator', 'admin'])],
            'permissions' => 'nullable|array',
            'work_start_time' => 'nullable|date_format:H:i',
            'work_end_time' => 'nullable|date_format:H:i',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'permissions' => $validated['permissions'] ?? [],
            'work_start_time' => $validated['work_start_time'] ?? '09:00',
            'work_end_time' => $validated['work_end_time'] ?? '17:00',
        ]);

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        if (!auth()->user()->isMaster()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|nullable|string|min:8',
            'role' => ['sometimes', 'required', Rule::in(['master', 'owner', 'marketer', 'content_creator', 'admin'])],
            'permissions' => 'sometimes|nullable|array',
            'work_start_time' => 'sometimes|nullable|date_format:H:i',
            'work_end_time' => 'sometimes|nullable|date_format:H:i',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        if (!auth()->user()->isMaster()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Cannot delete yourself'], 400);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    public function salespeople()
    {
        $salespeople = User::whereIn('role', ['master', 'owner', 'marketer'])
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        return response()->json($salespeople);
    }
}
