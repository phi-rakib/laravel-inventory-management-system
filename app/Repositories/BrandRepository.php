<?php

namespace App\Repositories;

use App\Models\Brand;
use App\Repositories\BaseRepository;
use App\Repositories\BrandRepositoryInterface;

class BrandRepository extends BaseRepository implements BrandRepositoryInterface
{
    protected $model;

    public function __construct(Brand $model)
    {
        parent::__construct($model);
    }
}
