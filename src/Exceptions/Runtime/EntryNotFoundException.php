<?php declare(strict_types=1);

namespace Bio\Exceptions\Runtime;


class EntryNotFoundException extends RuntimeException {

    public static function notFound(): EntryNotFoundException {
        return new self('Requested record does not exists.');
    }



    public static function invalidId(): EntryNotFoundException {
        return new self('Requested record does not exists.');
    }

}
