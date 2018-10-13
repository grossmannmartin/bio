<?php declare(strict_types=1);

namespace Bio\Core\Doctrine\Types;


use Bio\App\EmailAddress\EmailAddress;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class EmailType extends StringType {

    private const EMAIL_TYPE = 'email';



    public function getName(): string {
        return self::EMAIL_TYPE;
    }



    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string {
        return $platform->getVarcharTypeDeclarationSQL(['length' => 120]);
    }



    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        return (string)$value;
    }



    public function convertToPHPValue($value, AbstractPlatform $platform): EmailAddress {
        return new EmailAddress($value);
    }

}
