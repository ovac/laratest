<?php

namespace Yab\LaraTest\Services;

use Exception;
use Yab\LaraTest\Services\FileService;

class UnitTestService
{
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function getNamespace($contents)
    {
        $namespace = $this->fileService->getLine($contents, 'namespace');
        return str_replace('namespace ', '', str_replace(';', '', $namespace));
    }

    public function getTestClass($contents)
    {
        $class = $this->getClass($contents);
        return $class.'Test';
    }

    public function getClass($contents)
    {
        $class = $this->fileService->getLine($contents, 'class');
        $class = str_replace('class ', '', $class);
        $class = explode(' ', $class);

        return $class[0];
    }

    public function getFunctions($contents)
    {
        $functions = [];
        $methods = '';

        $functionLines = $this->fileService->getLine($contents, 'function', true);

        foreach ($functionLines as $function) {
            $function = str_replace('public function', '', trim($function));
            $function = str_replace('function', '', trim($function));

            list($name, $params) = explode('(', $function);

            $params = str_replace(')', '', str_replace('{', '', str_replace('}', '', $params)));

            $functions[] = [
                'name' => $name,
                'params' => explode(',', trim($params))
            ];
        }

        foreach ($functions as $function) {
            $methods .= $this->createMethodFromStub($function);
        }

        return $methods;
    }

    public function createFromStub($config, $file)
    {
        $stub = file_get_contents(__DIR__.'/../Stubs/'.$file);

        foreach ($config as $key => $value) {
            $stub = str_replace($key, $value, $stub);
        }

        return [
            'name' => ucfirst($config['_class_'].'Test'),
            'content' => $stub
        ];
    }

    public function createMethodFromStub($function)
    {
        $params = '';
        $paramOneLine = '';

        $stub = file_get_contents(__DIR__.'/../Stubs/method.stub');

        if ($function['name'] !== '__construct') {
            if (is_array($function['params']) && ! empty($function['params'][0])) {
                foreach ($function['params'] as $param) {
                    $param = trim($param);
                    $params .= "\t\t$param = null;\n";
                }
                $paramOneLine = implode(',', $function['params']);
            }

            $config = [
                'uc_function_' => ucfirst($function['name']),
                '_function_' => $function['name'],
                '_params_' => $params."\t\t\t\t",
                '_param_one_line' => $paramOneLine,
            ];

            foreach ($config as $key => $value) {
                $stub = str_replace($key, $value, $stub);
            }

            return $stub;
        }
    }
}
