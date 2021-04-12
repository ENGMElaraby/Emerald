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
    private function homeRedirect(): string
    {
        return $this->route . 'index';
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
}
