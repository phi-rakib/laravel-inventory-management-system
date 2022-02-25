<?php

namespace App\Repositories;

interface IResourceRepository
{
    public function getAll();

    public function show($model);

    public function create($data);

    public function update($model, $data);

    public function delete($model);
}
