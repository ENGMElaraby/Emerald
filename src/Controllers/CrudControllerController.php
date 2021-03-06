<?php

namespace MElaraby\Emerald\Controllers;

use App\{Http\Controllers\Controller,
    Packages\Emerald\Repositories\Interfaces\RepositoryContractCrud,
    Packages\Emerald\Responses\GeneralResponse};
use BadMethodCallException;
use Error;

/**
 * @TODO add permission (class, pattern, trait)
 *
 * Class CrudControllerController
 * @package MElaraby\Emerald\Controllers
 */
class CrudControllerController extends Controller implements CrudControllerContract
{
    use CrudControllerHelper;

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
         * @var RepositoryContractCrud
         */
        $repository,

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
     * @param RepositoryContractCrud $repository
     */
    public function __construct(RepositoryContractCrud $repository)
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
        return new GeneralResponse([
            'data' => $this->repository->index($this->pagination, $this->perPage),
            'view' => $this->indexView(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return GeneralResponse
     */
    public function create(): GeneralResponse
    {
        return new GeneralResponse([
            'data' => $this->repository->create([]),
            'view' => $this->storeView(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return GeneralResponse
     */
    public function store(): GeneralResponse
    {
        $request = app($this->storeRequest);
        $this->repository->store($request->validated());
        return new GeneralResponse([
            'redirect' => $this->storeRedirect(),
            'alert' => $this->storeAlert('success', 'Added new')
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
        return new GeneralResponse([
            'data' => $this->repository->show($id),
            'view' => $this->showView(),
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
        return new GeneralResponse([
            'data' => $this->repository->edit($id),
            'view' => $this->editView(),
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
        $request = app($this->updateRequest);
        return new GeneralResponse([
            'data' => $this->repository->update($request->validated(), $id),
            'redirect' => $this->updateRedirect($id),
            'alert' => $this->updateAlert('success', 'Updated one')
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
        $this->repository->destroy($id);
        return new GeneralResponse([
            'redirect' => $this->deleteRedirect(),
            'alert' => $this->destroyAlert('success', 'Deleted')
        ]);
    }

    /**
     * Change status of the specified resource from storage.
     *
     * @param int $id
     * @return GeneralResponse
     */
    public function status(int $id): GeneralResponse
    {
        $this->repository->status($id);
        return new GeneralResponse([
            'route' => $this->statusRedirect(),
            'alert' => $this->statusAlert('success', 'updated')
        ]);
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed|string
     */
    public function __call($method, $parameters)
    {
        if (in_array($method, ['storeRedirect', 'updateRedirect', 'deleteRedirect', 'statusRedirect'])) {
            return $this->homeRedirect();
        }

        if (in_array($method, ['storeAlert', 'updateAlert', 'destroyAlert', 'statusAlert'])) {
            return $this->alert($parameters[0], $parameters[1]);
        }

        if (in_array($method, ['indexView', 'storeView', 'showView', 'editView'])) {
            $viewName = explode("View", $method, 2)[0];
            return $this->view($viewName);
        }

        try {
            return $this->{$method}();
        } catch (Error | BadMethodCallException $e) {
            $pattern = '~^Call to undefined method (?P<class>[^:]+)::(?P<method>[^\(]+)\(\)$~';

            if (!preg_match($pattern, $e->getMessage(), $matches)) {
                throw $e;
            }

            if ($matches['class'] != get_class($this) || $matches['method'] != $method) {
                throw $e;
            }

            throw new BadMethodCallException(sprintf(
                'Call to undefined method %s::%s()', static::class, $method
            ));
        }
    }
}
