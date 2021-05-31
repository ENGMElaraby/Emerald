<?php

namespace MElaraby\Emerald\Controllers;

trait CrudControllerHelper
{
    /**
     * @return string
     */
    private function homeRedirect(): string
    {
        return route($this->route . 'index');
    }

    /**
     * @param string $viewName
     * @return string
     */
    private function view(string $viewName): string
    {
        return $this->view . $viewName;
    }

    /**
     * @param string $type
     * @param string $html
     * @return array
     */
    private function alert(string $type, string $html): array
    {
        return ['type' => $type, 'html' => $html];
    }

    private function getValidatedData()
    {

    }
}
