<?php
namespace MElaraby\Emerald\Controllers;

trait CrudHelper
{
    /**
     * @param int $perPage
     * @return mixed
     */
    private function getPaginationOrAll(int $perPage)
    {
        return ($this->pagination) ? $this->repository->paginate($perPage) : $this->repository->all();
    }


}
