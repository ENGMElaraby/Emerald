<?php

namespace MElaraby\Emerald\Repositories;

use MElaraby\Emerald\Repositories\Interfaces\RepositoryContractHelper;
use Exception;
use Illuminate\{Database\Eloquent\Collection, Database\Eloquent\Model};
use ReflectionClass;
use ReflectionException;

abstract class Repository implements RepositoryContractHelper
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
            throw new Exception('Failed to make instance');
        }
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
     * @param int $id
     * @return Model
     */
    public function find(int $id): Model
    {
        return $this->model::find($id);
    }
}
