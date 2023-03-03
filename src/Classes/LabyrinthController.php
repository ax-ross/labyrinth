<?php

namespace App\Classes;

use App\Exceptions\ValidationException;

class LabyrinthController
{
    public function __invoke(array $request): bool|string
    {
        try {
            $labyrinthInstance = new Labyrinth(...$this->handleFormRequest($request));
            $response = $labyrinthInstance->getShortestPath();
        } catch (ValidationException $e) {
            http_response_code($e->getCode());
            return json_encode(['message' => $e->getMessage()]);
        }
        return json_encode($response);

    }

    /**
     * @throws ValidationException
     */
    protected function handleFormRequest($request): array
    {
        $labyrinth = $request['labyrinth'];
        array_walk_recursive($labyrinth, function (&$item) {
            if (is_numeric($item)) {
                $item = intval($item);
            } else {
                throw new ValidationException('Для прохода через клетку указано недопустимое кол-во ходов', 422);
            }
            if ($item < 0 or $item > 9) {
                throw new ValidationException('Для прохода через клетку указано недопустимое кол-во ходов', 422);
            }
        });

        $start =  array_map(function ($value) {
            if (is_numeric($value)) {
                return intval($value);
            } else {
                throw new ValidationException('Неверное значение для стартовой точки', 422);
            }
        }, $request['start']);
        $stop = array_map(function ($value) {
            if (is_numeric($value)) {
                return intval($value);
            } else {
                throw new ValidationException('Неверное значение для финишной точки', 422);
            }
        }, $request['stop']);
        return [$labyrinth, $start, $stop];

    }
}