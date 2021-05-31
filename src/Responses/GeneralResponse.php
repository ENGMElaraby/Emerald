<?php

namespace MElaraby\Emerald\Responses;

use MElaraby\Emerald\RestfulAPI\RestTrait;
use Illuminate\{Contracts\Foundation\Application,
    Contracts\Support\Responsable,
    Contracts\View\Factory,
    Contracts\View\View,
    Http\JsonResponse,
    Http\RedirectResponse,
    Http\Request,
    Validation\ValidationException};
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class GeneralResponse implements Responsable
{
    use RestTrait, ResponseHTTPStatus;

    public
        /**
         * status of request
         *
         * @var int
         */
        $status = 200,

        /**
         * message include request
         *
         * @var string|null
         */
        $message, $userMessage,

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
        $option = [],

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
         * Redirect for if redirect out/in route
         *
         * @var string|null
         */
        $redirect,

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
        'userMessage' => [
            'default' => '',
            'casting' => 'string'
        ],
        'status' => [
            'default' => 200,
            'casting' => 'integer'
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
        'redirect' => [
            'default' => '/',
            'casting' => 'string'
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
            $this->checkPropertyHasValueAndUpdatePropertyValue($property, $value);
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
     * @param string $type
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
     * @param string $property
     * @param $value
     * @return void
     */
    private function checkPropertyHasValueAndUpdatePropertyValue(string $property, $value): void
    {
        if (empty($value)) {
            $this->$property = $this->properties[$property]['default'];
        }

        $this->$property = $value;
    }

    /**
     * @param string $message
     * @param int $status
     * @return self
     */
    public static function error(string $message, int $status = 401): self
    {
        return new self([
            'option' => [
                'error' => $message,
            ],
            'status' => $status
        ]);
    }

    /**
     * @param string $message
     * @param int $code
     * @throws RuntimeException
     */
    public static function exceptionError(string $message, int $code = 400)
    {
        throw new RuntimeException($message, $code);
    }

    /**
     * @param string $key
     * @param string $message
     * @throws ValidationException
     */
    public static function exceptionValidationError(string $key, string $message)
    {
        throw ValidationException::withMessages([
            $key => [$message],
        ]);
    }

    /**
     * @param array $properties
     * @return self
     */
    public static function response(array $properties): self
    {
        return new self($properties);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse|RedirectResponse|Response
     */
    public function toResponse($request)
    {
        if (!is_null($this->redirect)) {
            return redirect($this->redirect)
                ->with('data', $this->data())
                ->with('alert', $this->alert);
        }

        if ($this->isApiCall($request) || $request->ajax() || $request->wantsJson()) {
            return response()->json($this->data(), $this->status);
        }

        if ($this->route !== null) {
            return redirect()->route($this->route)->with('alert', $this->alert)->with('data', $this->data())->send();
        }

        return view($this->view)
            ->with('data', $this->data)
            ->with('alert', $this->alert);
    }

    /**
     * merge data and return array
     *
     * @return array
     */
    private function data(): array
    {
        $data = ['status' => $this->status];

        if (!is_null($this->message)) {
            $data['message'] = $this->message;
        }

        if (!is_null($this->userMessage)) {
            $data['user_message'] = $this->userMessage;
        }

        $data['hash'] = null; // TODO make Cache Class

        if (!is_null($this->data)) {
            $data['data'] = $this->data;
        }

        return array_merge($data, $this->option);
    }
}
