<?php


namespace MElaraby\Emerald\HttpFoundation;

use Illuminate\{Contracts\Foundation\Application,
    Contracts\Routing\ResponseFactory,
    Http\Resources\Json\JsonResource,
    Http\Response
};
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

trait ResponseHTTPStatus
{
    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function ok(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            SymfonyResponse::HTTP_OK,
            $headers
        );
    }

    /**
     * @param $content
     * @return string
     */
    private function processContent($content): string
    {
        if ($content instanceof JsonResource) {
            return $content->response()->content();
        }

        return $content;
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function created(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_CREATED,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function accepted(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_ACCEPTED,
            $headers
        );
    }

    /**
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function noContent(array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            null,
            Response::HTTP_NO_CONTENT,
            $headers
        );
    }

    /**
     * @param $newUrl
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function movedPermanently($newUrl, array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            null,
            Response::HTTP_MOVED_PERMANENTLY,
            $headers + ['Location' => $newUrl]
        );
    }

    /**
     * @param $url
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function found($url, array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            null,
            Response::HTTP_FOUND,
            $headers + ['Location' => $url]
        );
    }

    /**
     * @param $newUrl
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function seeOther($newUrl, array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            null,
            Response::HTTP_SEE_OTHER,
            $headers + ['Location' => $newUrl]
        );
    }

    /**
     * @param $tempUrl
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function temporaryRedirect($tempUrl, array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            null,
            Response::HTTP_TEMPORARY_REDIRECT,
            $headers + ['Location' => $tempUrl]
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function badRequest(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_BAD_REQUEST,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function unauthorized(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_UNAUTHORIZED,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function paymentRequired(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_PAYMENT_REQUIRED,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function forbidden(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_FORBIDDEN,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function notFound(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_NOT_FOUND,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function methodNotAllowed(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_METHOD_NOT_ALLOWED,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function notAcceptable(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_NOT_ACCEPTABLE,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function gone(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_GONE,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function payloadTooLarge(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_REQUEST_ENTITY_TOO_LARGE,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function unprocessableEntity(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_UNPROCESSABLE_ENTITY,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function upgradeRequired(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_UPGRADE_REQUIRED,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function tooManyRequests(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_TOO_MANY_REQUESTS,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function internalServerError(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function notImplemented(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_NOT_IMPLEMENTED,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function badGateway(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_BAD_GATEWAY,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function serviceUnavailable(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_SERVICE_UNAVAILABLE,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function gatewayTimeout(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_GATEWAY_TIMEOUT,
            $headers
        );
    }

    /**
     * @param string $content
     * @param array $headers
     * @return Application|ResponseFactory|Response
     */
    private function insufficientStorage(string $content = '', array $headers = []): Response|Application|ResponseFactory
    {
        return response(
            $this->processContent($content),
            Response::HTTP_INSUFFICIENT_STORAGE,
            $headers
        );
    }
}
