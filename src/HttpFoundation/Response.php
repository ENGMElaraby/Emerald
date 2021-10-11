<?php

namespace MElaraby\Emerald\HttpFoundation;

use Illuminate\{Contracts\Foundation\Application,
    Contracts\Support\Responsable,
    Contracts\View\Factory,
    Contracts\View\View,
    Http\JsonResponse,
    Http\RedirectResponse,
    Http\Request,
    Routing\Redirector,
    Validation\ValidationException
};
use JetBrains\PhpStorm\NoReturn;
use JetBrains\PhpStorm\Pure;
use MElaraby\Emerald\RestfulAPI\RestTrait;
use RuntimeException;

class Response implements Responsable
{
    use RestTrait, ResponseHTTPStatus;

    /**
     * Response constructor.
     *
     * @param int $status
     * @param mixed|array $data
     * @param string $message message api for developer
     * @param string $userMessage User message
     * @param string|null $view view if web request and not api or ajax request
     * @param string|null $route redirect to where, which route use
     * @param string|null $redirect Redirect for if redirect out/in route
     * @param array $option option if you have another data or types
     * @param array $alert alert use for view request only as Alarm or Alert or Notify
     */
    public function __construct(private int     $status = 200,
                                private mixed   $data = [],
                                private string  $message = '',
                                private string  $userMessage = '',
                                private ?string $view = null,
                                private ?string $route = null,
                                private ?string $redirect = null,
                                private array   $option = [],
                                private array   $alert = [])
    {

    }

    /**
     * @param string $message
     * @param int $status
     * @return self
     */
    #[Pure] public static function error(string $message, int $status = 401): self
    {
        return new self(status: $status, option: ['error' => $message]);
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
    #[NoReturn] public static function response(array $properties): self
    {
        dd('need to test firstly');
        return new self($properties);
    }

    /**
     * @param Request $request
     * @return Application|Factory|View|JsonResponse|RedirectResponse|Redirector|\Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request): Factory|View|JsonResponse|Redirector|\Symfony\Component\HttpFoundation\Response|Application|RedirectResponse
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

        if (!is_null($this->data)) {
            $data['data'] = $this->data;
        }

        return array_merge($data, $this->option);
    }
}
