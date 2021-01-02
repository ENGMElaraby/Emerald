<?php

namespace MElaraby\Emerald\Repositories;

use Illuminate\{Database\Eloquent\Builder, Database\Eloquent\Collection, Database\Eloquent\Model};

trait HelperContractRepository
{
    protected
        /**
         * The repository model.
         *
         * @var Model
         */
        $model,

        /**
         * The query builder.
         *
         * @var Builder
         */
        $query,

        /**
         * Array of related models to eager load.
         *
         * @var array
         */
        $with = [],

        /**
         * Array of scope methods to call on the model.
         *
         * @var array
         */
        $scopes = [],

        /**
         * Alias for the query limit.
         *
         * @var int
         */
        $take,

        /**
         * Array of related models to eager load.
         *
         * @var array
         */
        $whereHas = [],

        /**
         * Array of one or more where clause parameters.
         *
         * @var array
         */
        $wheres = [],

        /**
         * Array of one or more where in clause parameters.
         *
         * @var array
         */
        $whereIns = [],

        /**
         * Array of one or more ORDER BY column/value pairs.
         *
         * @var array
         */
        $orderBys = [],

        /**
         * Array of one or more ORDER BY column/value pairs.
         *
         * @var array
         */
        $orderByColumn = 'id',
        $orderBy = 'desc';

    /**
     * Create a new instance of the model's query builder.
     *
     * @return $this
     */
    protected function newQuery()
    {
        $this->query = $this->model->newQuery();

        return $this;
    }

    /**
     * Add relationships to the query builder to eager load.
     *
     * @return $this
     */
    protected function eagerLoad()
    {
        foreach ($this->with as $relation) {
            $this->query->with($relation);
        }

        foreach ($this->whereHas as $relation) {
            $this->query->whereHas($relation);
        }

        return $this;
    }

    /**
     * Set query scopes.
     *
     * @return $this
     */
    protected function setScopes()
    {
        foreach ($this->scopes as $method => $args) {
            $this->query->$method(implode(', ', $args));
        }

        return $this;
    }

    /**
     * Reset the query clause parameter arrays.
     *
     * @return $this
     */
    protected function unsetClauses()
    {
        $this->wheres = [];
        $this->whereIns = [];
        $this->scopes = [];
        $this->take = null;

        return $this;
    }

    /**
     * Set clauses on the query builder.
     *
     * @return $this
     */
    protected function setClauses()
    {
        foreach ($this->wheres as $where) {
            $this->query->where($where['column'], $where['operator'], $where['value']);
        }

        foreach ($this->whereIns as $whereIn) {
            $this->query->whereIn($whereIn['column'], $whereIn['values']);
        }

        foreach ($this->orderBys as $orders) {
            $this->query->orderBy($orders['column'], $orders['direction']);
        }

        if (!is_null($this->take)) {
            $this->query->take($this->take);
        }

        return $this;
    }

    /**
     * @param array $with
     * @return Builder[]|Collection
     */
    public function get($with = [])
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->with($with)->get();

        $this->unsetClauses();

        return $models;
    }
}
