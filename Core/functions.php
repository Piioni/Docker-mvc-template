<?php

use Core\Response;
use JetBrains\PhpStorm\NoReturn;

#[NoReturn]
function dd($value): void
{
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function is_current_url($value): bool
{
    return $_SERVER['REQUEST_URI'] === $value;
}



#[NoReturn]
function abort_with_status($code = 404): void
{
    http_response_code($code);

    // Primero buscar la vista en la carpeta específica de errores
    $viewPath = base_path("src/views/errors/{$code}.php");
    // Backward compatible: si existe la vista en la raíz de pages, usarla
    $fallbackView = base_path("src/views/{$code}.php");
    // Backward compatible: if controller is a file path, require it

    if (file_exists($viewPath)) {
        require $viewPath;
        die();
    }

    if (file_exists($fallbackView)) {
        require $fallbackView;
        die();
    }

    // Fallback simple response if view file is missing
    echo "<h1>" . htmlspecialchars($code) . "</h1>";
    echo "<p>Page not found or an error occurred.</p>";

    exit();
}


function ensure_authorized($condition, $status = Response::FORBIDDEN): true
{
    if (! $condition) {
        abort_with_status($status);
    }

    return true;
}

function base_path($path): string
{
    return BASE_PATH . $path;
}

function render_view($path, $attributes = []): void
{
    extract($attributes);

    require base_path('src/views/pages/' . $path);
}

#[NoReturn]
function redirect_to($path): void
{
    header("location: {$path}");
    exit();
}

function old_input($key, $default = '')
{
    $old = Core\Session::get_value('old');

    return is_array($old) && array_key_exists($key, $old) ? $old[$key] : $default;
}