<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePermissionRequest;
use App\Http\Requests\Admin\UpdatePermissionRequest;
use App\Http\Resources\Admin\PermissionResource;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $resource = Permission::query()
            ->paginate($request->get('per_page'));

        return PermissionResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        $resource = Permission::query()->create($request->validated());

        return new PermissionResource($resource);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $resource = Permission::query()->findOrFail($id);

        return new PermissionResource($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePermissionRequest $request, string $id)
    {
        $resource = Permission::query()->findOrFail($id);

        $resource->update($request->validated());

        return new PermissionResource($resource);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resource = Permission::query()->findOrFail($id);

        $resource->delete();

        return response()->noContent();
    }
}
