<?php

namespace App\Services\Seo;

enum HeadPlacement: string
{
    case PREPEND = 'prepend';
    case APPEND = 'append';
}
