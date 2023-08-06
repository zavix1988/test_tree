<?php

namespace Core\Base;

use Exception;

abstract class AbstractController
{
    public array $route = [];

    public $view;

    public $layout;

    public array $data = [];

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = $route['action'];
    }

    public function getView()
    {
        $vObj = new View($this->route, $this->layout, $this->view);
        $vObj->render($this->data);
    }

    /**
     * Setting data to view
     *
     * @param $data
     */
    public function set($data)
    {
        $this->data = $data;
    }

    /**
     * Checking is request was ajax
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * Transform response to json
     */
    protected function jsonResponse($response)
    {
        header('Content-Type: application/json');

        echo json_encode($response);
    }

    /**
     * Getting request
     */
    protected function getRequest()
    {
        $json_data = file_get_contents('php://input');
        return json_decode($json_data, true);
    }

    /**
     * Loading view
     */
    public function loadView($view, $vars = [])
    {
        extract($vars);
        require APP . "/Views/{$this->route['controller']}/{$view}.php";
    }

    /**
     * Checking request method
     * @throws Exception
     */
    protected function checkMethod(string $method): bool
    {
        if ($method !== $_SERVER['REQUEST_METHOD']) {
            throw new Exception('Invalid request method');
        }
        return true;
    }
}