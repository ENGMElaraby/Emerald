<?php

namespace MElaraby\Emerald\Resources;

use Illuminate\{Http\JsonResponse, Http\Request, Http\Resources\Json\ResourceCollection, Support\Collection};

/**
 * @method total()
 * @method perPage()
 * @method currentPage()
 * @method lastPage()
 */
class ResourceCollections extends ResourceCollection
{
    public $pagination;

    public $theResource;

    public function __construct($resource, $theResource, $pagination = false)
    {
        parent::__construct($resource);

        $this->pagination = $pagination;

        $this->theResource = $theResource;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return Collection
     */
    public function toArray($request): Collection
    {
        $collection = $this->collection;
        if (!is_null($this->theResource)) {
            $collection = ($this->pagination) ? ['rows' => $this->theResource::collection($collection)] : $this->theResource::collection($collection);
        }
        return $this->merges(new Collection($collection));

    }

    /**
     * Merge all Function as Array then return
     *
     * @param Collection $data
     * @return Collection
     */
    private function merges(Collection $data): Collection
    {
        if ($this->pagination) {
            $data = $data->merge($this->pagination());
        }
        return $data;
    }

    /**
     * @return Collection
     */
    private function pagination(): Collection
    {
        return new Collection([
            'pagination' => [
                'total' => $this->total(),
                'count' => $this->count(),
                'per_page' => $this->perPage(),
                'current_page' => $this->currentPage(),
                'last_page' => $this->lastPage(),
                'has_more_pages' => $this->hasMorePages(),
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param JsonResponse $response
     */
    public function withResponse($request, $response)
    {
        $jsonResponse = json_decode($response->getContent(), true);
        unset($jsonResponse['links'], $jsonResponse['meta']);
        $response->setContent(json_encode($jsonResponse));
    }
}
