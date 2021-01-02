<?php

namespace MElaraby\Emerald\Repositories;

interface RepositoryContract
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @return int
     */
    public function count() : int;

    /**
     * @return mixed
     */
    public function first();

    /**
     * @return mixed
     */
    public function get();

    /**
     * @param int $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * @param $item
     * @param $column
     * @param array $columns
     * @return mixed
     */
    public function getByColumn($item, $column, array $columns = ['*']);

    /**
     * @param int $limit
     * @return mixed
     */
    public function limit(int $limit);

    /**
     * @param string $column
     * @param string $direction
     * @return mixed
     */
    public function orderBy(string $column, string $direction);

    /**
     * @param int $limit
     * @param array|string[] $columns
     * @param string $pageName
     * @param null $page
     * @return mixed
     */
    public function paginate(int $limit = 25, array $columns = ['*'], string $pageName = 'page', $page = null);

    /**
     * @param $column
     * @param $value
     * @param string $operator
     * @return mixed
     */
    public function where($column, $value, $operator = '=');

    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function whereIn($column, $value);

    /**
     * @param $relations
     * @return mixed
     */
    public function with($relations);

    /**
     * @param int $id
     * @return mixed
     */
    public function deleteById(int $id);
}
