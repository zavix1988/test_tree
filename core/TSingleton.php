<?php

namespace Core;

trait TSingleton
{
    /**
     * @var
     */
    private static $instance;

    /**
     * @return TSingleton
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}