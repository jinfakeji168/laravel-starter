<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OperationLogResource;
use App\Models\OperationLog;
use Illuminate\Http\Request;

class OperationLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $resource = OperationLog::query()
            ->paginate($request->get('per_page'));

        return OperationLogResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resource = OperationLog::query()->findOrFail($id);

        $resource->delete();

        return response()->noContent();
    }
}
