<?php

namespace App\Classes;

use App\Exceptions\ValidationException;
use SplQueue;

final class Labyrinth
{
    private array $shortestDistances;
    private array $labyrinthSize;
    private SplQueue $queue;
    private array $parents = [];


    /**
     * @throws ValidationException
     */
    public function __construct(private readonly array $labyrinth, private readonly array $start, protected readonly array $stop)
    {
        $this->validate();
        $this->labyrinthSize = [count($this->labyrinth), count($this->labyrinth[0])];
        $this->initParents();
        $this->initShortestDistances();
        $this->queue = new SplQueue();

    }

    private function initParents(): void
    {
        for ($i = 0; $i < $this->labyrinthSize[0]; $i++) {
            $this->parents[] = array_fill(0, $this->labyrinthSize[1], 0);
        }
        $this->parents[$this->start[0]][$this->start[1]] = [$this->start[0], $this->start[1]];
    }

    /**
     * @throws ValidationException
     */
    private function validate(): void
    {
        if (count($this->start) > 2) {
            throw new ValidationException('Точка старта передана в неверном формате', 422);
        }
        if (count($this->stop) > 2) {
            throw new ValidationException('Точка финиша перадана в неверном формате', 422);
        }
        if (!isset($this->labyrinth[$this->start[0]][$this->start[1]])) {
            throw new ValidationException('Указанная стартовая точка не существует', 422);
        }
        if (!isset($this->labyrinth[$this->stop[0]][$this->stop[1]])) {
            throw new ValidationException('Указанная финишная точка не существует', 422);
        }
        if ($this->labyrinth[$this->start[0]][$this->start[1]] === 0) {
            throw new ValidationException('Стена не может быть стартовой точкой', 422);
        }
        if ($this->labyrinth[$this->stop[0]][$this->stop[1]] === 0) {
            throw new ValidationException('Стена не может быть финишной точкой', 422);

        }
    }

    private function initShortestDistances(): void
    {
        for ($i = 0; $i < $this->labyrinthSize[0]; $i++) {
            $this->shortestDistances[] = $this->labyrinth[$i];
            for ($j = 0; $j < $this->labyrinthSize[1]; $j++) {
                if ($this->labyrinth[$i][$j] === 0) {
                    $this->shortestDistances[$i][$j] = 0;
                } else {
                    $this->shortestDistances[$i][$j] = PHP_INT_MAX;
                }
            }
        }
        $this->shortestDistances[$this->start[0]][$this->start[1]] = $this->labyrinth[$this->start[0]][$this->start[1]];
    }

    private function calculate_shortest_distances(): void
    {
        $this->queue->push($this->start);
        while (!$this->queue->isEmpty()) {
            $node = $this->queue->dequeue();

            if (isset($this->labyrinth[$node[0] - 1][$node[1]]) && $this->labyrinth[$node[0] - 1][$node[1]] != 0) {
                if ($this->labyrinth[$node[0] - 1][$node[1]] + $this->shortestDistances[$node[0]][$node[1]] < $this->shortestDistances[$node[0] - 1][$node[1]]) {
                    $this->shortestDistances[$node[0] - 1][$node[1]] = $this->labyrinth[$node[0] - 1][$node[1]] + $this->shortestDistances[$node[0]][$node[1]];
                    $this->queue->enqueue([$node[0] - 1, $node[1]]);
                    $this->parents[$node[0] - 1][$node[1]] = [$node[0], $node[1]];

                }
            }
            if (isset($this->labyrinth[$node[0] + 1][$node[1]]) && $this->labyrinth[$node[0] + 1][$node[1]] != 0) {
                if ($this->labyrinth[$node[0] + 1][$node[1]] + $this->shortestDistances[$node[0]][$node[1]] < $this->shortestDistances[$node[0] + 1][$node[1]]) {
                    $this->shortestDistances[$node[0] + 1][$node[1]] = $this->labyrinth[$node[0] + 1][$node[1]] + $this->shortestDistances[$node[0]][$node[1]];
                    $this->queue->enqueue([$node[0] + 1, $node[1]]);
                    $this->parents[$node[0] + 1][$node[1]] = [$node[0], $node[1]];
                }
            }
            if (isset($this->labyrinth[$node[0]][$node[1] - 1]) && $this->labyrinth[$node[0]][$node[1] - 1] != 0) {
                if ($this->labyrinth[$node[0]][$node[1] - 1] + $this->shortestDistances[$node[0]][$node[1]] < $this->shortestDistances[$node[0]][$node[1] - 1]) {
                    $this->shortestDistances[$node[0]][$node[1] - 1] = $this->labyrinth[$node[0]][$node[1] - 1] + $this->shortestDistances[$node[0]][$node[1]];
                    $this->queue->enqueue([$node[0], $node[1] - 1]);
                    $this->parents[$node[0]][$node[1] - 1] = [$node[0], $node[1]];
                }
            }
            if (isset($this->labyrinth[$node[0]][$node[1] + 1]) && $this->labyrinth[$node[0]][$node[1] + 1] != 0) {
                if ($this->labyrinth[$node[0]][$node[1] + 1] + $this->shortestDistances[$node[0]][$node[1]] < $this->shortestDistances[$node[0]][$node[1] + 1]) {
                    $this->shortestDistances[$node[0]][$node[1] + 1] = $this->labyrinth[$node[0]][$node[1] + 1] + $this->shortestDistances[$node[0]][$node[1]];
                    $this->queue->enqueue([$node[0], $node[1] + 1]);
                    $this->parents[$node[0]][$node[1] + 1] = [$node[0], $node[1]];
                }
            }
        }
    }

    /**
     * @throws ValidationException
     */
    private function restorePath(): array
    {
        $path = [];
        $i = null;
        $j = null;
        $path[] = $this->stop;
        while (!($i === $this->start[0] && $j === $this->start[1])) {
            if (is_null($i) && is_null($j)) {
                if ($this->parents[$this->stop[0]][$this->stop[1]] === 0) {
                    throw new ValidationException('Невозможно построить маршрут', 422);
                } else {
                    [$i, $j] = [$this->parents[$this->stop[0]][$this->stop[1]][0], $this->parents[$this->stop[0]][$this->stop[1]][1]];
                    $path[] = [$i, $j];
                }
            } else {
                [$i, $j] = [$this->parents[$i][$j][0], $this->parents[$i][$j][1]];
                $path[] = [$i, $j];
            }

        }
        return array_reverse($path);
    }

    /**
     * @throws ValidationException
     */
    public function getShortestPath(): array
    {
        $this->calculate_shortest_distances();
        return ['distance' => $this->shortestDistances[$this->stop[0]][$this->stop[1]], 'path' => $this->restorePath()];
    }

}





