<?php

/**
 *
 * @param $string
 * @return array|string|string[]
 */
function upperCamelCase ($string)
    {
        return str_replace(
            ' ',
            '',
            ucwords(
                str_replace(
                    '-',
                    ' ',
                    $string
                )
            )
        );
    }

/**
 *
 * @param $string
 * @return string
 */
function lowerCamelCase ($string): string
{
    return lcfirst(
        upperCamelCase($string)
    );
}

function getBaseUrl()
{
    return (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
}