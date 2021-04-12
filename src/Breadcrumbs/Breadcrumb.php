<?php

namespace MElaraby\Emerald\Breadcrumbs;

class Breadcrumb
{
    private $name;

    /**
     * Breadcrumb constructor.
     * @param string $name
     */
    public function __construct(string $name = null)
    {
        $this->name = $name;
    }

//    public function __invoke()
//    {
//        // TODO: Implement __invoke() method.
//    }

}
