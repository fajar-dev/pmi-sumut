<?php

namespace App\Enum;

enum PostStatus
{
    const PUBLISHED = 'Published';

    const DRAFT = 'Draft';

    const status = [
        self::PUBLISHED => 'Published',
        self::DRAFT => 'Draft',
    ];
}
