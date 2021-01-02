<?php

namespace MElaraby\Emerald\Controllers;

use App\Http\Controllers\Controller;
use MElaraby\{Emerald\Repositories\RepositoryContract,
    Emerald\Resources\ResourceCollections,
    Emerald\Responses\GeneralResponse,
    Emerald\RestfulAPI\RestTrait
    };

class CrudController extends Controller implements CrudContract
{
    use RestTrait, CrudHelper;

    protected
        /**
         * determine which view.
         *
         * @var string|null
         */
        $view,

        /**
         * determine which route using.
         *
         * @var string|null
         */
        $route,

        /**
         * determine which repository.
         *
         * @var RepositoryContract
         */
        $repository,

        /**
         * determine which Response interface we use to control response in index function (Illuminate\Contracts\Support\Responsible).
         *
         * @var GeneralResponse
         */
        $Response = GeneralResponse::class,

        /**
         * determine which FormRequest use in store.
         *
         * @var string|null
         */
        $storeRequest,

        /**
         * determine which FormRequest use in update.
         *
         * @var string|null
         */
        $updateRequest,

        /**
         * determine the resource use it.
         *
         * @var string|null
         */
        $theResource,

        /**
         * determine the Breadcrumbs use it.
         *
         * @var string|null
         */
        $breadcrumbs,

        /**
         * use pagination if needed default false.
         *
         * @var bool
         */
        $pagination = false,

        /**
         * pagination per page default 6.
         *
         * @var string|null
         */
        $perPage = 6;

    /**
     * Controller constructor.
     * @param RepositoryContract $repository
     */
    public function __construct(RepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return GeneralResponse
     */
    public function index(): GeneralResponse
    {
        return MElaraby($this->Response,[
            'breadcrumbs'   => $this->breadcrumbs . '.index',
            'data'          => new ResourceCollections($this->getPaginationOrAll($this->perPage), $this->theResource, $this->pagination),
            'message'       => 'Request success, get all',
            'view'          => $this->view . '.index']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return GeneralResponse
     */
    public function create(): GeneralResponse
    {
        return MElaraby($this->Response, [
            'breadcrumbs'   => $this->breadcrumbs . '.create',
            'data'          => (method_exists($this->repository, 'createData')) ? $this->repository->createData() : null,
            'view'          => $this->view . '.create'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return GeneralResponse
     */
    public function store(): GeneralResponse
    {
        $request = MElaraby($this->storeRequest);
        $this->repository->store($request->validated());
        return MElaraby($this->Response, [
            'message'   => 'Request success, Added new',
            'route'     => $this->route . '.index',
            'alert'     => ['type' => 'success', 'html' => __('admin/Modules.add_new')]
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function show(int $id): GeneralResponse
    {
        return MElaraby($this->Response, [
            'breadcrumbs'   => $this->breadcrumbs . '.show',
            'data'          => ((method_exists($this->repository, 'showData')) ? $this->repository->showData($this->repository->getById($id)) : null),
            'message'       => 'Request success, get specific one',
            'view'          => $this->view . '.show'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function edit(int $id): GeneralResponse
    {
        return MElaraby($this->Response, [
            'breadcrumbs'   => $this->breadcrumbs . '.edit',
            'data'          => (method_exists($this->repository, 'editData')) ? $this->repository->editData($this->repository->getById($id)) : null,
            'view'          => $this->view . '.edit'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function update(int $id): GeneralResponse
    {
        $request = MElaraby($this->updateRequest);

        return MElaraby($this->Response, [
            'data'      => new ResourceCollections([$this->repository->update($request->validated(), $this->repository->getById($id))], $this->theResource),
            'message'   => 'Request success, Updated',
            'route'     => $this->route . '.index',
            'alert'     => ['type' => 'success', 'html' => __('admin/Modules.updated')]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function destroy(int $id): GeneralResponse
    {
        $this->repository->deleteById($this->repository->getById($id)->id);
        return MElaraby($this->Response, [
            'message'   => 'Request success, Delete specified resource from storage',
            'route'     => $this->route . '.index',
            'alert'     => ['type' => 'success', 'html' => __('admin/Modules.delete')]
        ]);
    }

    /**
     * Change status of the specified resource from storage.
     *
     * @param int $rowId
     * @return GeneralResponse
     */
    public function status(int $rowId): GeneralResponse
    {
        $this->repository->status($rowId);
        return MElaraby($this->Response, [
            'message'   => 'Request success, status updated',
            'route'     => $this->route . '.index',
            'alert'     => ['type' => 'success', 'html' => __('admin/Events.done')]
        ]);
    }
}
