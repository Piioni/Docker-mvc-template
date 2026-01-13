<?php

namespace Core\Middleware;

use Core\Session;

class Guest
{
    public function handle()
    {
        if (Session::get_value('user')) {
            header('location: /');
            exit();
        }
    }
}