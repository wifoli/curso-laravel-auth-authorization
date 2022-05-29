<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuResource;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    protected $model;

    public function __construct(Resource $user)
    {
        $this->model = $user;
    }

    public function index()
    {
        $resources = $this->model->with(['permissions'])->get();

        return MenuResource::collection($resources);
    }
}
