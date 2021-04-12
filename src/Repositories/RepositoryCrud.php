<?php

namespace MElaraby\Emerald\Repositories;

use Exception;
use Illuminate\{Database\Eloquent\Collection, Database\Eloquent\Model};

abstract class RepositoryCrud implements RepositoryContractCrud, RepositoryContractHelper
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param string[] $columns
     * @return Collection|Model[]
     */
    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->all(['*']);
    }

    /**
     * @param array $data
     * @return array
     */
    public function create(array $data = [])
    {
        return [];
    }

    /**
     * @param array $data
     */
    public function store(array $data)
    {
        $this->model::create($data);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function show(int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function edit(int $id)
    {
        return $this->find($id);
    }

    /**
     * @param array $data
     * @param int|Model $id
     */
    public function update(array $data, $id): void
    {
        if ($id instanceof Model) {
            $model = $id;
        } else {
            $model = $this->find($id);
        }

        $model->update($data);
    }

    /**
     * @param int $id
     * @throws Exception
     */
    public function destroy(int $id): void
    {
        $model = $this->find($id);
        $model->delete();
    }

    /**
     * @param int $id
     */
    public function status(int $id): void
    {
        $model = $this->find($id);
        $this->update([
            'status' => ((bool)$model->status ? false : true)
        ], $model);
    }

    /**
     * @param int $id
     * @return Model
     */
    public function find(int $id): Model
    {
        return $this->model::find($id);
    }
}
