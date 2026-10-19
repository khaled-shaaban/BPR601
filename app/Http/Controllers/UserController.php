<?php

namespace App\Http\Controllers;

use App\Enums\AppRoles;
use App\Http\Requests\Users\IndexRequest;
use App\Http\Requests\Users\RoleRequest;
use App\Http\Requests\Users\StoreRequest;
use App\Models\User;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    public function __construct(protected UserRepository $repository)
    {
    }

    public function index(IndexRequest $request)
    {
        $users = $this->repository->index($request->validated('filters'), $request->validated('per_page'));
        return $this->respondWithPagination($users);
    }

    /**
     * Create a user with a chosen role (super admins only) — used to add staff
     * and representatives, since public registration only creates citizens.
     */
    public function store(StoreRequest $request)
    {
        $data = $request->validated();
        $isRep = $data['role'] === AppRoles::REPRESENTATIVE->value;

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'authority_id' => $isRep ? ($data['authority_id'] ?? null) : null,
        ]);
        $user->assignRole($data['role']);

        return $this->respondWithData($user->fresh()->load(['roles:id,name', 'authority:id,name']));
    }

    /**
     * Slim list of administrators for the report-assignment picker.
     */
    public function assignees()
    {
        $admins = User::role([AppRoles::ADMIN->value, AppRoles::SUPER_ADMIN->value])
            ->orderBy('name')
            ->get(['id', 'name']);

        return $this->respondWithData($admins);
    }

    /**
     * Change a user's role (super admins only). Representatives are tied to an authority.
     */
    public function updateRole(RoleRequest $request, int $id)
    {
        if ($id === $request->user()->id) {
            return $this->respondWithFailure('You cannot change your own role.', 422);
        }

        $user = User::findOrFail($id);
        $role = $request->validated('role');

        $user->syncRoles([$role]);
        $user->update([
            'authority_id' => $role === AppRoles::REPRESENTATIVE->value
                ? $request->validated('authority_id')
                : null,
        ]);

        return $this->respondWithData($user->fresh()->load(['roles:id,name', 'authority:id,name']));
    }
}
