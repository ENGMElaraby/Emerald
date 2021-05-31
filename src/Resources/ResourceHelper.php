<?php
namespace MElaraby\Emerald\Resources;

trait ResourceHelper
{
    private
    /**
     * haystack of this resource to check
     *
     * @var array
     */
    $haystack;

    /**
     * @param string $needle
     * @return bool
     */
    private function ifInHaystack(string $needle) : bool
    {
        return in_array($needle, $this->haystack) || in_array('all', $this->haystack);
    }
}
