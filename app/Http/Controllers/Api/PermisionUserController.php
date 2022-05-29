<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\User;
use Illuminate\Http\Request;

class PermisionUserController extends Controller
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    public function permissionsUser($identify)
    {
        $user = $this->user
            ->where('uuid', $identify)
            ->with('permissions')
            ->firstOrFail();

        return PermissionResource::collection($user->permissions);
    }

    public function addPermissionUser(Request $request)
    {
        $user = $this->user->where('uuid', $request->user)->firstOrFail();

        $user->permissions()->attach($request->permissions);

        return response()->json([
            'message' => 'Permission added successfully',
        ], 200);
    }
}