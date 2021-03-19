<?php


namespace App\Enums;


class FileStatusEnum extends Enum
{
    const PENDING = 1;

    const PROCESSING = 2;

    const FAIL = 3;

    const COMPLETED = 4;
}
