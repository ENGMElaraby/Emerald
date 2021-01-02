<?php

namespace MElaraby\Emerald\Responses;

use MElaraby\Emerald\RestfulAPI\RestTrait;
use Illuminate\{Contracts\Foundation\Application,
    Contracts\Support\Responsable,
    Contracts\View\Factory,
    Http\JsonResponse,
    Http\RedirectResponse,
    Http\Request,
    View\View};
use Symfony\Component\HttpFoundation\Response;

class GeneralResponse implements Responsable
{
    use RestTrait;

    protected
    /**
     * status of request
     *
     * @var int
     */
    $status,

    /**
     * message include request
     *
     * @var string|null
     */
    $message,

    /**
     * data return into request
     *
     * @var array|null
     */
    $data,

    /**
     * option if have another return data or types
     *
     * @var array|null
     */
    $option,

    /**
     * return view if web request and not api or ajax request
     *
     * @var string|null
     */
    $view,

    /**
     * return redirect to where, which route use
     *
     * @var string|null
     */
    $route,
    /**
     * breadcrumbs use for web view request only
     *
     * @var string|null
     */
    $breadcrumbs,

    /**
     * alert use for view request only as Alarm or Alert or Notify
     *
     * @var string|null
     */
     $alert;


    /**
     * GeneralResponse constructor.
     *
     * @param array $data
     * @param int $status
     * @param string|null $message
     * @param string|null $view
     * @param string|null $breadcrumbs
     * @param array|null $alert
     * @param string|null $route
     * @param array|null $option
     */
    public function __construct(
        $data                   = [],
        int     $status         = 200,
        ?string $message        = '',
        ?string $view           = null,
        ?string $breadcrumbs    = null,
        ?array  $alert          = null,
        ?string $route          = null,
        ?array  $option         = []
    )
    {
        $this->data             = $data;
        $this->status           = $status;
        $this->message          = $message;
        $this->view             = $view;
        $this->breadcrumbs      = $breadcrumbs;
        $this->alert            = $alert;
        $this->route            = $route;

        $this->option           = $option;
    }

    /**
     * @param Request $request
     * @return Application|Factory|JsonResponse|RedirectResponse|View|Response
     */
    public function toResponse($request)
    {
        if ($this->isApiCall($request) || $request->ajax() || $request->wantsJson()) {
            return response()->json(array_merge([
                'status'    =>  $this->status,
                'message'   =>  $this->message,
                'data'      =>  $this->data,
            ], $this->option), $this->status);
        }

        if ($this->route !== null && !$this->isApiCall($request)) {
            return redirect()->route($this->route)->with('alert', $this->alert)->send();
        }

        return view($this->view)
            ->with('data', $this->data)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('alert', $this->alert);
    }
}
