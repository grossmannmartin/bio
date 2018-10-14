<?php declare(strict_types=1);

namespace Bio\Core\AccessControl\Exceptions;


use Bio\Exceptions\Runtime\RuntimeException;

class AuthenticationException extends RuntimeException {

    public static function emailNotFound(): AuthenticationException {
        return new self('User is not registered.');
    }



    public static function disabledUser(): AuthenticationException {
        return new self('User is disabled.');
    }



    public static function badPassword(): AuthenticationException {
        return new self('Bad password.');
    }



    public static function invalidToken(): AuthenticationException {
        return new self('Authentication token is not valid.');
    }

}
