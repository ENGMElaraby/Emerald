<?php

namespace MElaraby\Emerald\Repositories\Interfaces;

interface RepositoryContractCrud
{
    /**
     * @return mixed
     */
    public function index();

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data);

    /**
     * @param int $id
     * @return mixed
     */
    public function show(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function edit(int $id);

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function update(array $data, $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id);

    /**
     * @param int $id
     * @return mixed
     */
    public function status(int $id);
}
