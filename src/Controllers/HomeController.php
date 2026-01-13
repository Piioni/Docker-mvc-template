<?php

namespace App\Controllers;

class HomeController
{
    public function index(): void
    {
        render_view('home.php', [
            'title' => 'Home'
        ]);
    }
}
