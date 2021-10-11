<?php

namespace MElaraby\Emerald\Controllers;

use MElaraby\Emerald\Responses\Response;

interface CrudControllerContract
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() : Response;

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() : Response;

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() : Response;

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id) : Response;

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit(int $id) : Response;

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function update(int $id) : Response;


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id) : Response;

    /**
     * Change status of the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function status(int $id): Response;
}
