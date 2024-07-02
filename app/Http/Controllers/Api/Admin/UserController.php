<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Filtering the resources.
     *
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function filters(Request $request)
    {
        $query = User::query();

        $query->when($request->get('id'), function (Builder $query, $id) {
            $query->where('id', $id);
        });

        $query->when($request->get('name'), function (Builder $query, $name) {
            $query->where('name', 'like', $name);
        });

        $query->when($request->get('email'), function (Builder $query, $email) {
            $query->where('email', 'like', $email);
        });

        $query->unless($request->get('sort'), function (Builder $query) {
            $query->orderBy('id', 'desc');
        })->when($request->get('sort'), function (Builder $query, $sort) use ($request) {
            $query->orderBy($sort, $request->get('direction', 'desc'));
        });

        return $query;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'sort' => 'nullable',
            'direction' => [
                'nullable',
                Rule::in(['asc', 'desc']),
            ],
            'per_page' => 'nullable|integer|min:1|max|100',
        ]);

        $resource = $this->filters($request)
            ->paginate($request->get('per_page'));

        return UserResource::collection($resource);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $resource = User::query()->create($request->validated());

        return new UserResource($resource);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $resource = User::query()->findOrFail($id);

        return new UserResource($resource);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $resource = User::query()->findOrFail($id);

        $resource->update($request->validated());

        return new UserResource($resource);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resource = User::query()->findOrFail($id);

        $resource->delete();

        return response()->noContent();
    }
}
