<?php

namespace MElaraby\Emerald\Responses;

use Illuminate\{Contracts\Foundation\Application,
    Contracts\Support\Responsable,
    Contracts\View\Factory,
    Contracts\View\View,
    Http\JsonResponse,
    Http\RedirectResponse,
    Http\Request};
use MElaraby\Emerald\RestfulAPI\RestTrait;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class GeneralResponse implements Responsable, ResponsibleContract
{
    use RestTrait;

    public
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
        $alert,

        /**
         * alert use for view request only as Alarm or Alert or Notify
         *
         * @var string|null
         */
        $properties = [
        'data' => [
            'default' => [],
            'casting' => 'all'
        ],
        'view' => [
            'default' => null,
            'casting' => 'string'
        ],
        'breadcrumbs' => [
            'default' => null,
            'casting' => 'object'
        ],
        'message' => [
            'default' => '',
            'casting' => 'string'
        ],
        'status' => [
            'default' => 200,
            'casting' => 'string'
        ],
        'alert' => [
            'default' => null,
            'casting' => 'array'
        ],
        'route' => [
            'default' => null,
            'casting' => 'string'
        ],
        'option' => [
            'default' => [],
            'casting' => 'array'
        ],
    ];


    /**
     * GeneralResponse constructor.
     *
     * @param array $properties
     */
    public function __construct(array $properties)
    {
        foreach ($properties as $property => $value) {
            $this->checkPropertyInProperties($property);
            $this->checkPropertyCasting($property, gettype($value));
            $this->$property = $value;
        }
    }

    /**
     * @param string $property
     * @return bool
     */
    private function checkPropertyInProperties(string $property): bool
    {
        if (array_key_exists($property, $this->properties)) {
            return true;
        }

        throw new RuntimeException('Property dosen\'t exists');
    }

    /**
     * @param string $property
     * @param $type
     * @return bool
     */
    private function checkPropertyCasting(string $property, string $type): bool
    {
        if ($this->properties[$property]['casting'] === 'all') {
            return true;
        }

        if ($type === $this->properties[$property]['casting']) {
            return true;
        }

        throw new RuntimeException('Wrong property type');
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse|RedirectResponse|Response
     */
    public function toResponse($request)
    {
        if ($this->isApiCall($request) || $request->ajax() || $request->wantsJson()) {
            return response()->json($this->Data(), $this->status);
        }

        if ($this->route !== null) {
            return redirect()->route($this->route)->with('alert', $this->alert)->with('data', $this->Data())->send();
        }

        return view($this->view)
            ->with('data', $this->data)
            ->with('breadcrumbs', $this->breadcrumbs)
            ->with('alert', $this->alert);
    }

    /**
     * merge data and return array
     *
     * @return array
     */
    public function Data(): array
    {
        return array_merge([
            'status' => $this->status,
            'message' => $this->message,
            'data' => $this->data,
        ], $this->option);
    }
}
