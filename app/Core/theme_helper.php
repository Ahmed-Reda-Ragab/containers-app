<?php

use App\Core\Theme;

if (!function_exists('theme')) {
    function theme(): Theme
    {
        return app(Theme::class);
    }
}

if (!function_exists('addHtmlAttribute')) {
    function addHtmlAttribute(string $scope, string $name, string $value): void
    {
        theme()->addHtmlAttribute($scope, $name, $value);
    }
}

if (!function_exists('addHtmlAttributes')) {
    function addHtmlAttributes(string $scope, array $attributes): void
    {
        theme()->addHtmlAttributes($scope, $attributes);
    }
}

if (!function_exists('addHtmlClass')) {
    function addHtmlClass(string $scope, string $value): void
    {
        theme()->addHtmlClass($scope, $value);
    }
}

if (!function_exists('removeHtmlClass')) {
    function removeHtmlClass(string $scope, string $value): void
    {
        theme()->removeHtmlClass($scope, $value);
    }
}

if (!function_exists('printHtmlAttributes')) {
    function printHtmlAttributes(string $scope): string
    {
        return theme()->printHtmlAttributes($scope);
    }
}

if (!function_exists('printHtmlClasses')) {
    function printHtmlClasses(string $scope, bool $full = true)
    {
        return theme()->printHtmlClasses($scope, $full);
    }
}

if (!function_exists('getSvgIcon')) {
    function getSvgIcon(string $path, string $classNames = 'svg-icon'): string
    {
        return theme()->getSvgIcon($path, $classNames);
    }
}

if (!function_exists('setModeSwitch')) {
    function setModeSwitch(bool $flag): void
    {
        theme()->setModeSwitch($flag);
    }
}

if (!function_exists('isModeSwitchEnabled')) {
    function isModeSwitchEnabled(): bool
    {
        return theme()->isModeSwitchEnabled();
    }
}

if (!function_exists('setModeDefault')) {
    function setModeDefault(string $mode): void
    {
        theme()->setModeDefault($mode);
    }
}

if (!function_exists('getModeDefault')) {
    function getModeDefault(): string
    {
        return theme()->getModeDefault();
    }
}

if (!function_exists('setDirection')) {
    function setDirection(string $direction): void
    {
        theme()->setDirection($direction);
    }
}

if (!function_exists('getDirection')) {
    function getDirection(): string
    {
        return theme()->getDirection();
    }
}

if (!function_exists('extendCssFilename')) {
    function extendCssFilename(string $path): string
    {
        return theme()->extendCssFilename($path);
    }
}

if (!function_exists('isRtlDirection')) {
    function isRtlDirection(): bool
    {
        return theme()->isRtlDirection();
    }
}

if (!function_exists('includeFavicon')) {
    function includeFavicon(): string
    {
        return theme()->includeFavicon();
    }
}

if (!function_exists('includeFonts')) {
    function includeFonts(): string
    {
        return theme()->includeFonts();
    }
}

if (!function_exists('getGlobalAssets')) {
    function getGlobalAssets(string $type = 'js'): array
    {
        return theme()->getGlobalAssets($type);
    }
}

if (!function_exists('addVendors')) {
    function addVendors(array $vendors): array
    {
        return theme()->addVendors($vendors);
    }
}

if (!function_exists('addVendor')) {
    function addVendor(string $vendor): void
    {
        theme()->addVendor($vendor);
    }
}

if (!function_exists('addJavascriptFile')) {
    function addJavascriptFile(string $file): void
    {
        theme()->addJavascriptFile($file);
    }
}

if (!function_exists('addCssFile')) {
    function addCssFile(string $file): void
    {
        theme()->addCssFile($file);
    }
}

if (!function_exists('getVendors')) {
    function getVendors(string $type): array
    {
        return theme()->getVendors($type);
    }
}

if (!function_exists('getCustomJs')) {
    function getCustomJs(): array
    {
        return theme()->getCustomJs();
    }
}

if (!function_exists('getCustomCss')) {
    function getCustomCss(): array
    {
        return theme()->getCustomCss();
    }
}

if (!function_exists('getHtmlAttribute')) {
    function getHtmlAttribute(string $scope, string $attribute)
    {
        return theme()->getHtmlAttribute($scope, $attribute);
    }
}

if (!function_exists('getIcon')) {
    function getIcon(string $name, string $class = '', string $type = '', string $tag = 'span'): string
    {
        return theme()->getIcon($name, $class, $type, $tag);
    }
}
