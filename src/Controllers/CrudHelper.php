<?php
namespace MElaraby\Emerald\Controllers;

trait CrudHelper
{
    private function checkMethodExists()
    {

    }

    /**
     * @return string
     */
    protected function homeRedirect(): string
    {
        return $this->route . 'index';
    }
}
