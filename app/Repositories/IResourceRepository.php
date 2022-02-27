<?php

namespace App\Repositories;

interface IResourceRepository
{
    public function getAll($filters);

    public function show($id);

    public function findWhere($column, $value);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
