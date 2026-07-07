<?php

namespace FinTrack\FinLib\Enums;

enum Api: int
{
    case Success = 200;
    case Created = 201;
    case NoContent = 204;
    case BadRequest = 400;
    case Unauthorized = 401;
    case Forbidden = 403;
    case NotFound = 404;
    case ValidationError = 422;
    case ServerError = 500;

    public function message(): string
    {
        return match ($this) {
            self::Success => 'Success',
            self::Created => 'Created successfully',
            self::NoContent => 'No content',
            self::BadRequest => 'An error occurred',
            self::Unauthorized => 'Unauthorized',
            self::Forbidden => 'Forbidden',
            self::NotFound => 'Resource not found',
            self::ValidationError => 'Validation failed',
            self::ServerError => 'Internal server error',
        };
    }
}
