<?php

namespace MElaraby\Emerald\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryContractHelper
{
    /**
     * @return mixed
     */
    public function all();

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model;
}
