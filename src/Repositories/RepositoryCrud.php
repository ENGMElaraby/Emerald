<?php

namespace MElaraby\Emerald\Repositories;

use MElaraby\Emerald\Repositories\Interfaces\RepositoryContractCrud;
use Exception;
use Illuminate\{Database\Eloquent\Collection, Database\Eloquent\Model};

abstract class RepositoryCrud extends Repository implements RepositoryContractCrud
{
    /**
     * CrudRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        parent::__construct($model);
    }

    /**
     * @return Collection|Model[]|mixed
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
     * @return mixed
     */
    public function store(array $data)
    {
        $this->model::create($data);
    }

    /**
     * @param int $id
     * @return Model|mixed
     */
    public function show(int $id)
    {
        return $this->find($id);
    }

    /**
     * @param int $id
     * @return Model|mixed
     */
    public function edit(int $id)
    {
        return $this->find($id);
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
            'status' => (bool)$model->status
        ], $model);
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
}
