<?php

use Shared\App\Validator\ConstraintErrorModel;

function mapErrors(array $errors): array
{
    $errorModel = function (ConstraintErrorModel $error) use (&$errorModel) {
        $newError = new stdClass();
        $newError->property = $error->property;

        if (!empty((array)$error->constraints)) {
            $newError->constraints = $error->constraints;
        }

        if (!empty($error->children)) {
            $newError->children = [];

            foreach ($error->children as $children) {
                $newError->children[] = $errorModel($children);
            }
        }

        return $newError;
    };

    return array_map($errorModel, $errors);
}


function getPathParams(string $basePath, string $routePath, string $path): object
{
    $originalPath = '^(' . $basePath . ')' . $routePath . '$';
    preg_match_all('/:(\w+)/', $originalPath, $matches);
    $paramsNames = $matches[1];

    $pattern = preg_replace_callback('/:(\w+)/', function ($match) {
        return '(?P<' . $match[1] . '>[^/]+)';
    }, $originalPath);

    $pattern = '~' . $pattern . '~';

    $pathParams = [];
    if (preg_match($pattern, $path, $matches)) {

        foreach ($paramsNames as $name) {
            $pathParams[$name] = $matches[$name];

            foreach ($matches as $key => $value) {
                if ($value === $matches[$name] && $key !== $name) {
                    unset($matches[$key]);
                }
            }
            unset($matches[$name]);
        }

        $anonymousValues = array_slice($matches, +2);
        foreach ($anonymousValues as $index => $value) {
            $pathParams[(string)$index] = $value;
        }
    }

    return json_decode(json_encode($pathParams, JSON_FORCE_OBJECT));
}
