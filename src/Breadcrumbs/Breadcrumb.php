<?php
namespace MElaraby\Emerald\Breadcrumbs;

class Breadcrumb
{
    private $name;

    /**
     * Breadcrumb constructor.
     * @param $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

}
