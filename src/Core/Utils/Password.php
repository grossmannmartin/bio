<?php declare(strict_types=1);

namespace Bio\Core\Utils;

use Bio\Exceptions\Runtime\UnexpectedValueException;
use Nette\SmartObject;
use Nette\Utils\Random;


class Password {

    use SmartObject;



    // @todo: Ensure password policy
    public static function generate(): string {
        /** @noinspection SpellCheckingInspection */
        // removed ambiguous characters (l, 1, o, 0, O)
        $charList = 'abcdefghijkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789';

        return Random::generate(10, $charList);
    }



    public static function hash(string $plaintextPassword): string {
        $passwordHash = password_hash($plaintextPassword, PASSWORD_ARGON2I);

        if ($passwordHash === false) {
            throw new UnexpectedValueException('Unable to create hash of a password');
        }

        return $passwordHash;
    }



    public static function verify($plaintextPassword, $passwordHash): bool {
        return password_verify($plaintextPassword, $passwordHash);
    }

}
