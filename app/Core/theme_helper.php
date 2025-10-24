<?php

use App\Core\Theme;

if (!function_exists('theme')) {
    function theme(): Theme
    {
        return app(Theme::class);
    }
}
