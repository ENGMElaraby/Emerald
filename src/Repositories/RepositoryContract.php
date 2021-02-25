<?php

namespace MElaraby\Emerald\Repositories;

use Illuminate\Database\Eloquent\Model;

interface RepositoryContract
{
    public function all();

    public function index();

    public function create(array $data);

    public function store(array $data): void;

    public function show(int $id);

    public function edit(int $id);

    public function update(array $data, $id): void;

    public function destroy(int $id);

    public function status(int $id);

    public function find(int $id): Model;
}
