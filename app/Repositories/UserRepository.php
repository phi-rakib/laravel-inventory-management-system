<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\IRepository;
use Illuminate\Support\Facades\Config;

class UserRepository implements IRepository
{
    public function getAll($filters)
    {
        $perPage = $filters['perPage'] ?? Config::get('constants.pagination.max_item');
        $columns = ['*'];
        $pageNumber = $filters['pageNumber'] ?? 1;

        return User::paginate($perPage, $columns, 'page', $pageNumber);
    }

    public function show($id)
    {
        return $this->getUserById($id);
    }

    public function findWhere($column, $value)
    {
        return User::where($column, $value)->firstOrFail();
    }

    public function create($data)
    {
        return User::create($data);
    }

    public function update($id, $data)
    {
        $user = $this->getUserById($id);

        $user->update($data);
    }

    public function delete($id)
    {
        $user = $this->getUserById($id);

        $user->delete();
    }

    private function getUserById($id)
    {
        return User::findOrFail($id);
    }
}
