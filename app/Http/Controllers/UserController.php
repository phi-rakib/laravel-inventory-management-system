<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\IRepository;
use Illuminate\Support\Facades\Config;

class UserController extends Controller
{
    private $userRepository;

    public function __construct(IRepository $userRepository)
    {
        $this->middleware('auth:sanctum');
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $filters = [
            'perPage' => Config::get('constants.pagination.max_item'),
            'pageNumber' => request()->query('page'),
            'q' => request()->query('q'),
        ];

        return $this->userRepository->getAll($filters);
    }

    public function show($id)
    {
        return $this->userRepository->show($id);
    }

    public function store(StoreUserRequest $request)
    {
        $this->userRepository->create($request->validated());
    }

    public function update(UpdateUserRequest $request, $id)
    {
        return $this->userRepository->update($id, $request->validated());
    }

    public function destroy($id)
    {
        $this->userRepository->delete($id);
    }
}
