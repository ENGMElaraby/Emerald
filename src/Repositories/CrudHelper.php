<?php

namespace MElaraby\Emerald\Repositories;

trait CrudHelper
{
    /**
     * store data into model
     *
     * @param array $data
     */
    public function store(array $data)
    {
        $this->model::create($data);
    }

    /**
     * edit data
     *
     * @param $row
     * @return mixed
     */
    public function editData($row)
    {
        return $row;
    }

}
