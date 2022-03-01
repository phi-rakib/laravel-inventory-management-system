<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use App\Repositories\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getAll($filters = null)
    {
        $perPage = $filters['perPage'] ?? Config::get('constants.pagination.max_item');
        $columns = ['*'];
        $pageNumber = $filters['pageNumber'] ?? 1;

        return $this->model::paginate($perPage, $columns, 'page', $pageNumber);
    }

    public function show($id)
    {
        return $this->getById($id);
    }

    public function findWhere($column, $value)
    {
        return $this->model::where($column, $value)->firstOrFail();
    }

    public function create($data)
    {
        return $this->model::create($data);
    }

    public function update($id, $data)
    {
        $this->getById($id)->update($data);
    }

    public function delete($id)
    {
        $this->getById($id)->delete();
    }

    private function getById($id)
    {
        return $this->model::findOrFail($id);
    }
}
