<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\UserRequest;
use App\Http\Resources\Api\Users\UserResource;
use App\Http\Resources\Api\Users\UsersCollection;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request): UsersCollection
    {
        $query = User::query()
            ->when($request->input('q'), fn (Builder $builder) => $builder->whereAny(['first_name', 'last_name', 'email', 'phone'], 'like', '%'.$request->input('q').'%'))
            ->when($request->input('sortBy'), fn (Builder $builder) => $builder->orderBy($request->input('sortBy'), $request->input('sortOrder', 'asc')));

        return new UsersCollection($query->paginate());
    }

    public function store(UserRequest $request)
    {
        $validated = $request->validated();

        return UserResource::make(User::create($validated));
    }

    public function show($id) {}

    public function update(UserRequest $request, $id)
    {
        $data = $request->validated();

        if (! $data['password']) {
            unset($data['password']);
        }

        $user = User::findOrFail($id);
        $user->update($data);

        return UserResource::make($user);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        abort_if($user->id === Auth::id(), Response::HTTP_FORBIDDEN, __('cms.users.suicide-warning'));

        $user->delete();

        return response()->json(['data' => null]);
    }

    public function check(Request $request): UserResource
    {
        return UserResource::make($request->user());
    }
}
