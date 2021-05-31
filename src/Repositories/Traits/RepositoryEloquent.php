<?php

namespace MElaraby\Emerald\Repositories\Traits;

use Illuminate\{Database\Eloquent\Collection, Database\Eloquent\Model};

trait RepositoryEloquent
{
    protected $query;

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
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->model::find($id);
    }
}
