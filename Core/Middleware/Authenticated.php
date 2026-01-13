<?php

namespace Core\Middleware;

use Core\Session;

class Authenticated
{
    public function handle()
    {
        if (! Session::get_value('user')) {
            header('location: /');
            exit();
        }
    }
}