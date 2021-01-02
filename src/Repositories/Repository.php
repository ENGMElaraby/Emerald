<?php

namespace MElaraby\Emerald\Repositories;

use Exception;
use Illuminate\{Contracts\Pagination\LengthAwarePaginator,
    Database\Eloquent\Builder,
    Database\Eloquent\Collection,
    Database\Eloquent\Model};

abstract class Repository implements RepositoryContract
{
    use HelperContractRepository, CrudHelper;

    /**
     * Get all the model records in the database.
     *
     * @return LengthAwarePaginator|mixed
     */
    public function all()
    {
        $this->newQuery()->eagerLoad();

        $models = $this->query->get();

        $this->unsetClauses();

        return $models;
    }

    /**
     * Check if record in database
     *
     * @return bool
     */
    public function exist(): bool
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->count() > 0;

        $this->unsetClauses();

        return $models;
    }

    /**
     * Count the number of specified model records in the database.
     *
     * @return int
     */
    public function count() : int
    {
        return $this->get()->count();
    }

    /**
     * Get the first specified model record from the database.
     *
     * @return Model
     */
    public function first()
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $model = $this->query->firstOrFail();

        $this->unsetClauses();

        return $model;
    }

    /**
     * Get the specified model record from the database.
     *
     * @param int $id
     *
     * @return Model
     */
    public function getById(int $id)
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->findOrFail($id);
    }

    /**
     * @param $item
     * @param $column
     * @param  array $columns
     *
     * @return Builder|Model|object|null
     */
    public function getByColumn($item, $column, array $columns = ['*'])
    {
        $this->unsetClauses();

        $this->newQuery()->eagerLoad();

        return $this->query->where($column, $item)->first($columns);
    }

    /**
     * Delete the specified model record from the database.
     *
     * @param int $id
     *
     * @return bool|null
     * @throws Exception
     */
    public function deleteById(int $id)
    {
        $this->unsetClauses();

        return $this->getById($id)->delete();
    }

    /**
     * Set the query limit.
     *
     * @param int $limit
     *
     * @return $this
     */
    public function limit(int $limit)
    {
        $this->take = $limit;

        return $this;
    }

    /**
     * Set an ORDER BY clause.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy(string $column, string $direction = 'asc')
    {
        $this->orderBys[] = compact('column', 'direction');

        return $this;
    }

    /**
     * @param int $limit
     * @param array $columns
     * @param string $pageName
     * @param null $page
     *
     * @return LengthAwarePaginator
     */
    public function paginate(int $limit = 6, array $columns = ['*'], string $pageName = 'page', $page = null)
    {
        $this->newQuery()->eagerLoad()->setClauses()->setScopes();

        $models = $this->query->orderBy($this->orderByColumn, $this->orderBy)->paginate($limit, $columns, $pageName, $page);

        $this->unsetClauses();

        return $models;
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
    public function where($column, $value, $operator = '=')
    {
        $this->wheres[] = compact('column', 'value', 'operator');

        return $this;
    }

    /**
     * Add a simple where in clause to the query.
     *
     * @param string $column
     * @param mixed $values
     *
     * @return $this
     */
    public function whereIn($column, $values)
    {
        $values = is_array($values) ? $values : [$values];

        $this->whereIns[] = compact('column', 'values');

        return $this;
    }

    /**
     * Set Eloquent relationships to eager load.
     *
     * @param $relations
     *
     * @return $this
     */
    public function with($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->with = $relations;

        return $this;
    }

    /**
     * @param $relations
     * @return $this
     */
    public function whereHas($relations)
    {
        if (is_string($relations)) {
            $relations = func_get_args();
        }

        $this->whereHas = $relations;

        return $this;
    }

    /**
     * Update the specified model record in the database.
     *
     * @param       $id
     * @param array $data
     * @param array $options
     *
     * @return Collection|Model
     */
    public function updateById($id, array $data, array $options = [])
    {
        $this->unsetClauses();

        $model = $this->getById($id);

        $model->update($data, $options);

        return $model;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function status(int $id) : bool
    {
        $row = $this->getById($id);
        $row->status = ($row->status) ? false : true;
        if ($row->save()) {
            return true;
        }
        return false;
    }
}
