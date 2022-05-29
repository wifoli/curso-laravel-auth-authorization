<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreUser;
use App\Http\Resources\UserResource;
use App\Models\User;

class RegisterController extends Controller
{
    protected $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function store(StoreUser $request)
    {
        $data = $request->validated();

        // Encrypt password
        $data['password'] = bcrypt($data['password']);

        $user = $this->model->create($data);
        $token = $user->createToken($data['device_name'])->plainTextToken;

        return (new UserResource($user))->additional(['token' => $token]);
    }
}
