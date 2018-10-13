<?php declare(strict_types=1);

namespace Bio\Exceptions\Runtime;

class MalformedInputException extends RuntimeException {

    public static function invalidData(): MalformedInputException {
        return new self('Provided data is not valid. Please consult schema.');
    }



    public static function invalidEmailAddress(): MalformedInputException {
        return new self('Provided email is not a valid email address.');
    }

}
