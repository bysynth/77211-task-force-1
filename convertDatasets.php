<?php

use App\DatasetsConverter\SqlCreator;

require 'vendor/autoload.php';

$destinationFolder = 'database/datasets';

$categoriesCsv = 'data/categories.csv';
$categories = new SqlCreator($categoriesCsv, $destinationFolder);
$categoriesCount = $categories->create();

$citiesCsv = 'data/cities.csv';
$cities = new SqlCreator($citiesCsv, $destinationFolder);
$citiesCount = $cities->create();

$usersCsv = 'data/users.csv';
$additionalColumnsData = ['city_id' => $citiesCount];
$users = new SqlCreator($usersCsv, $destinationFolder, $additionalColumnsData);
$usersCount = $users->create();

$tasksCsv = 'data/tasks.csv';
$additionalColumnsData = [
    'customer_id' => $usersCount,
    'executor_id' => $usersCount,
    'city_id' => $citiesCount
];
$tasks = new SqlCreator($tasksCsv, $destinationFolder, $additionalColumnsData);
$tasksCount = $tasks->create();

$responsesCsv = 'data/responses.csv';
$additionalColumnsData = [
    'task_id' => $tasksCount,
    'executor_id' => $usersCount
];
$responses = new SqlCreator($responsesCsv, $destinationFolder, $additionalColumnsData);
$responsesCount = $responses->create();

$reviewsCsv = 'data/reviews.csv';
$additionalColumnsData = [
    'task_id' => $tasksCount
];
$reviews = new SqlCreator($reviewsCsv, $destinationFolder, $additionalColumnsData);
$reviewsCount = $reviews->create();

print "Done\n";
