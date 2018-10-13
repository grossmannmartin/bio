<?php declare(strict_types=1);

namespace Bio\Exceptions\Runtime;


class DuplicateEntryException extends RuntimeException {

    public static function emailNotUnique(): DuplicateEntryException {
        return new self('Email address already exists.');
    }

}
