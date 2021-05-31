<?php

namespace MElaraby\Emerald\Controllers;

use MElaraby\Emerald\Responses\GeneralResponse;

interface CrudControllerContract
{
    /**
     * Display a listing of the resource.
     *
     * @return GeneralResponse
     */
    public function index() : GeneralResponse;

    /**
     * Show the form for creating a new resource.
     *
     * @return GeneralResponse
     */
    public function create() : GeneralResponse;

    /**
     * Store a newly created resource in storage.
     *
     * @return GeneralResponse
     */
    public function store() : GeneralResponse;

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function show(int $id) : GeneralResponse;

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function edit(int $id) : GeneralResponse;

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function update(int $id) : GeneralResponse;


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function destroy(int $id) : GeneralResponse;

    /**
     * Change status of the specified resource from storage.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function status(int $id): GeneralResponse;
}
