<?php


namespace App\Command;


use App\Core\Command\MakeController;
use App\Core\Command\MakeMiddleware;
use App\Core\Command\MakeModel;

class Console
{
    public function commands(): array
    {
        return [
            MakeController::class,
            MakeMiddleware::class,
            MakeModel::class
        ];
    }
}