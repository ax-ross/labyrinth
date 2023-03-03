<?php

use App\Classes\Labyrinth;
use App\Exceptions\ValidationException;


require __DIR__ . "/vendor/autoload.php";

$height = intval(readline('Введите высоту лабиринта' . PHP_EOL));
$width = intval(readline('Введите ширину лабиринта' . PHP_EOL));

if ($height < 1) {
    print 'Высота лабиринта должна быть натуральным числом > 1' . PHP_EOL;
    die();
}
if ($width < 1) {
    print 'Ширина лабиринта должна быть натуральным числом > 1' . PHP_EOL;
    die();
}


$labyrinth = [];
$row = [];

for ($i = 0; $i < $height; $i++) {
    $row = str_split(readline("Введите строку из целых чисел от 0 до 9 длинной ${width} без пробелов: \n"), 1);
    if (count($row) > $width) {
        print 'Введённая строка больше указанной ширины лабиринта' . PHP_EOL;
        die();
    }
    $row = array_map(function ($value) {
        if (is_numeric($value)) {
            $value = intval($value);
            if ($value < 0 || $value > 9) {
                print 'Значение клетки должно быть от 0 до 9' . PHP_EOL;
                die();
            } else
                return $value;
        } else {
            print 'Значение клетки должно быть число' . PHP_EOL;
            die();
        }
    }, $row);
    $labyrinth[] = $row;
}


$start = str_split(readline('Введите координаты стартовой точки без пробела' . PHP_EOL));


$start = array_map(function ($value) {
    if (is_numeric($value)) {
        return intval($value);
    } else {
        print 'Координаты стартовой точки должны быть числом' . PHP_EOL;
        die();
    }
}, $start);

$stop = str_split(readline('Введите координаты финишной точки без пробела' . PHP_EOL));
$stop = array_map(function ($value) {
    if (is_numeric($value)) {
        return intval($value);
    } else {
        print 'Координаты финишной точки должны быть числом' . PHP_EOL;
        die();
    }
}, $stop);


try {
    $labyrinthInstance = new Labyrinth($labyrinth, $start, $stop);
    $result = $labyrinthInstance->getShortestPath();

} catch (ValidationException $e) {
    print "{$e->getMessage()}" . PHP_EOL;
    die();
}

print "Неименьшее кол-во ходов для указанного пути: {$result['distance']}" . PHP_EOL;
print 'Координаты точек пути:' . PHP_EOL;
foreach ($result['path'] as $value) {
    print "({$value[0]}, {$value[1]}) ";
}
print PHP_EOL;
