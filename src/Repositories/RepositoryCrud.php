<?php

namespace MElaraby\Emerald\Repositories;

use Exception;
use Illuminate\{Database\Eloquent\Collection, Database\Eloquent\Model};
use ReflectionClass;
use ReflectionException;

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
     * @return mixed
     * @throws Exception
     */
    public static function getInstance()
    {
        try {
            $class = get_called_class();
            $parameter = (new ReflectionClass($class))->getConstructor()->getParameters()[0]->getClass()->getName();
            return (new $class(new $parameter));
        } catch (ReflectionException $e) {
            throw new Exception('Failed to make instance, ' . $e->getMessage());
        }
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->all(['*']);
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
    public function find(int $id): Model
    {
        return $this->model::find($id);
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
            'status' => ((bool)$model->status)
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
