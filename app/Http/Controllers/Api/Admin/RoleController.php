<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $resource = Role::query()
            ->paginate($request->get('per_page'));

        return RoleResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $resource = Role::query()->create($request->validated());

        return new RoleResource($resource);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $resource = Role::query()->findOrFail($id);

        return new RoleResource($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, string $id)
    {
        $resource = Role::query()->findOrFail($id);

        $resource->update($request->validated());

        return new RoleResource($resource);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resource = Role::query()->findOrFail($id);

        $resource->delete();

        return response()->noContent();
    }
}
