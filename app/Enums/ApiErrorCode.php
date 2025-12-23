<?php
// app/Enums/ApiErrorCode.php
namespace App\Enums;

enum ApiErrorCode: string
{
        case VALIDATION_ERROR = 'VALIDATION_ERROR';
        case AUTH_UNAUTHENTICATED = 'AUTH_UNAUTHENTICATED';
        case AUTH_FORBIDDEN = 'AUTH_FORBIDDEN';
        case RATE_LIMIT_EXCEEDED = 'RATE_LIMIT_EXCEEDED';
        case SERVER_ERROR = 'SERVER_ERROR';
        case METHOD_NOT_ALLOWED = 'METHOD_NOT_ALLOWED';
        case NOT_FOUND = 'NOT_FOUND';

}
