<?php

namespace App\Enums;

enum OrderStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case PROCESSED = 'processed';
    case FAILED = 'failed';
}