<?php

namespace MElaraby\Emerald\Repositories;

use MElaraby\Emerald\Log\Log;
use MElaraby\Emerald\Repositories\Traits\RepositoryEloquent;
use BadMethodCallException;
use Error;
use Exception;
use Illuminate\{Database\Eloquent\Model, Support\Collection};
use ReflectionClass;

abstract class Repository
{
    use RepositoryEloquent;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var array
     */
    private $wheres = [];

    /**
     * @var array|mixed
     */
    private $with = [];

    /**
     * @var array
     */
    private $visible = [];

    /**
     * @var array
     */
    private $orderBys = [];

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
     * @param array $array
     */
    public static function insert(array $array): void
    {
        (self::getInstance())->model->insert($array);
    }

    /**
     * TODO Need to add try an catch
     *
     * @return Repository
     */
    public static function getInstance(): Repository
    {
        $class = get_called_class();
        $parameter = (new \ReflectionClass($class))->getConstructor()->getParameters()[0]->getClass()->getName();
        return (new $class(new $parameter));
    }

    /**
     * @return Collection
     */
    public static function all(): Collection
    {
        return (self::getInstance())->model->all();
    }

    /**
     * @param $method
     * @param $parameters
     * @return mixed
     * @throws Exception
     */
    public static function __callStatic($method, $parameters)
    {
        try {
//            call_user_func_array($method, $parameters);
            return (self::getInstance())->{$method}($parameters);
        } catch (Error | BadMethodCallException $e) {
            $pattern = '~^Call to undefined method (?P<class>[^:]+)::(?P<method>[^\(]+)\(\)$~';

            if (!preg_match($pattern, $e->getMessage(), $matches)) {
                Log::Logging($e);
                throw $e;
            }

            if ($matches['class'] != get_class(self::class) || $matches['method'] != $method) {
                Log::Logging($e);
                throw $e;
            }

            Log::Logging($exception = sprintf(
                'Call to undefined method %s::%s()', static::class, $method
            ));

            throw new BadMethodCallException($exception);
        }
    }

    /**
     * Add a simple where clause to the query.
     *
     * @param string $column
     * @param string $value
     * @param string $operator
     *
     * @return $this
     */
    public function where(string $column, string $value, $operator = '='): self
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    /**
     * @param array $columns
     * @return mixed
     */
    public function get(array $columns = ['*'])
    {
        $this->newQuery()->eagerLoad()->setClauses();

        return $this->query->get($columns);
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations): self
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * make column visible to eager load.
     *
     * @param array $columns
     * @return $this
     */
    public function makeVisible(array $columns): self
    {
        $this->visible = $columns;

        return $this;
    }

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery(): self
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses(): self
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }
//
//        foreach ($this->whereIns as $whereIn) {
//            $this->query->whereIn($whereIn['column'], $whereIn['values']);
//        }
//
        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }
//
//        if (isset($this->take) and !is_null($this->take)) {
//            $this->query->take($this->take);
//        }

        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad(): self
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

//        if (count($this->visible)) {
//            $this->query->makeVisible($this->visible);
//        }
//        foreach ($this->whereHas as $relation) {
//            $this->query->whereHas($relation);
//        }

        return $this;
    }

}
