<?php

namespace MElaraby\Emerald\Repositories;

use MElaraby\Emerald\Repositories\Interfaces\RepositoryContractCrud;
use Exception;
use Illuminate\{Database\Eloquent\Collection, Database\Eloquent\Model};
use http\Exception\RuntimeException;

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
     * @param bool $pagination
     * @param int $perPage
     * @return Collection|Model[]|mixed
     */
    public function index(bool $pagination = false, int $perPage = 6)
    {
        $this->newQuery()->eagerLoad()->setClauses();

        $model = $this->query;

        if ($pagination) {
            return $model->paginate($perPage);
        }

        return $model->get(['*']);
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
     * @return void
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
        if ($model) {
            $model->delete();
            return;
        }
        throw new \RuntimeException('Not found resource');
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
}
