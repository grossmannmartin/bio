<?php declare(strict_types=1);

namespace Bio\App\Article\Persistence\Parts;

use Bio\Exceptions\Logic\InvalidArgumentException;
use Nette\SmartObject;


class UnknownPartTypeException extends InvalidArgumentException {

    use SmartObject;



    public static function unknownType(string $type, array $availableTypes = []): UnknownPartTypeException {
        $message = "Type '$type' is not allowed." . ($availableTypes ? ' Use one of following types: ' . implode(', ', $availableTypes) : '');

        return new self($message);
    }
}
