<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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
        if (Gate::denies('add_permission_users')) {
            return response()->json([
                'error' => 'Você não tem permissão para adicionar permissões.',
            ], 403);
        }

        $user = $this->user->where('uuid', $request->user)->firstOrFail();

        $user->permissions()->attach($request->permissions);

        return response()->json([
            'message' => 'Permission added successfully',
        ], 200);
    }

    public function removePermissionUser(Request $request)
    {
        if (Gate::denies('add_permission_users')) {
            return response()->json([
                'error' => 'Você não tem permissão para adicionar permissões.',
            ], 403);
        }

        $user = $this->user->where('uuid', $request->user)->firstOrFail();

        if ($request->permissions)
            $user->permissions()->detach($request->permissions);

        return response()->json([
            'message' => 'Permission removed successfully',
        ], 200);
    }

    public function userHasPermission(Request $request, $permission)
    {
        $user = $request->user();

        if (!$user->isSuperAdmin() && !$user->hasPermission($permission))
            return response()->json([
                'message' => 'User does not have permission',
            ], 403);

        return response()->json([
            'message' => 'User has permission',
        ], 200);
    }
}
