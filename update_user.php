<?php
declare(strict_types=1);

require __DIR__ .'/vendor/autoload.php';

use App\Controller\UserController;
$controller = new UserController();
$controller->updateUser($_POST);

