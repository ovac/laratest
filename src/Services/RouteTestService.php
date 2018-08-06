<?php

namespace Yab\LaraTest\Services;

use Exception;

class RouteTestService
{
    public function createFromStub($config)
    {
        $stub = file_get_contents(__DIR__.'/../Stubs/integration.stub');

        $config['_methods_'] = $this->createMethodsFromStub($config['_methods_']);

        foreach ($config as $key => $value) {
            $stub = str_replace($key, $value, $stub);
        }

        return [
            'filename' => $config['_class_'].'.php',
            'content' => $stub
        ];
    }

    public function findAction($route)
    {
        $routeActions = [
            'get(' => 'get',
            'post(' => 'post',
            'put(' => 'put',
            'patch(' => 'patch',
            'delete(' => 'delete',
            'options(' => 'options',
            'any(' => 'get',
            'resource(' => 'resource',
        ];

        foreach ($routeActions as $actionKey => $actionValue) {
            if (stristr($route, $actionKey)) {
                return $actionValue;
            }
        }

        return null;
    }

    public function createMethodsFromStub($routes)
    {
        $methods = '';

        foreach ($routes as $route) {
            $action = $this->findAction($route);
            $uri = $this->getUri($route, $action);

            if ($action == 'resource') {
                $actionUris = [
                    'get' => $uri,
                    'post' => $uri,
                    'patch' => $uri.'/1',
                    'delete' => $uri.'/1',
                    'get' => $uri.'/create',
                    'get' => $uri.'/1/edit',
                    'get' => $uri.'/1',
                ];

                foreach ($actionUris as $method => $url) {
                    $methods .= $this->createMethod($method, $url);
                }
            } else {
                $methods .= $this->createMethod($action, $uri);
            }
        }

        return $methods;
    }

    public function createMethod($action, $uri)
    {
        $template = 'get';

        if (in_array($action, [ 'post', 'put', 'patch' ])) {
            $template = 'post';
        }

        $stub = file_get_contents(__DIR__.'/../Stubs/'.$template.'Route.stub');

        $uriParts = explode('/', $uri);
        if (is_numeric($uriParts[count($uriParts) - 1]) ||
            stristr($uriParts[count($uriParts) - 1], '{')
        ) {
            unset($uriParts[count($uriParts) - 1]);
        }

        $config = [
            '_action_' => $action,
            '_route_' => $uri,
            '_ucAction_' => ucfirst($action),
            '_ucRoute_' => ucfirst(camel_case(implode('_', $uriParts))),
        ];

        foreach ($config as $key => $value) {
            $stub = str_replace($key, $value, $stub);
        }

        return $stub;
    }

    public function getUri($route, $action)
    {
        $uri = explode(',', $route);
        $uri = substr($uri[0], strpos($uri[0], $action));
        $uri = str_replace($action.'(', '', $uri);
        $uri = str_replace('"', '', $uri);

        return str_replace("'", '', $uri);
    }
}
