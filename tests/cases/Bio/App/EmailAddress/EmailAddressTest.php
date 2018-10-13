<?php

namespace Bio\App\EmailAddress;

require __DIR__ . '/../../../../bootstrap.php';

use Tester\Assert;
use Tester\TestCase;


class EmailAddressTest extends TestCase {

    public function testValidEmail(): void {
        $emailsToTest = [
            'simple@example.com',
            'very.common@example.com',
            'disposable.style.email.with+symbol@example.com',
            'other.email-with-hyphen@example.com',
            'fully-qualified-domain@example.com',
            'user.name+tag+sorting@example.com',
            'x@example.com',
            '"very.(),:;<>[]\".VERY.\"very@\\ \"very\".unusual"@strange.example.com',
            'example-indeed@strange-example.com',
            '#!$%&\'*+-/=?^_`{}|~@example.org',
            '"()<>[]:,;@\\!#$%&\'-/=?^_`{}| ~.a"@example.org',
            'example@s.example',
            '" "@example.org',
        ];

        foreach ($emailsToTest as $email) {
            Assert::type(EmailAddress::class, new EmailAddress($email));
        }
    }



    public function testInvalidEmail(): void {
        $emailsToTest = [
            'Abc.example.com', // no @ character
            'A@b@c@example.com', // only one @ is allowed outside quotation marks
            'a"b(c)d,e:f;g<h>i[j\k]l@example.com', // none of the special characters in this local-part are allowed outside quotation marks
            'just"not"right@example.com', // quoted strings must be dot separated or the only element making up the local-part
            'this is"not\allowed@example.com', // spaces, quotes, and backslashes may only exist when within quoted strings and preceded by a backslash
            'this\ still\"not\\allowed@example.com', // even if escaped (preceded by a backslash), spaces, quotes, and backslashes must still be contained by quotes
            'john..doe@example.com', // double dot before @
            'john.doe@example..com', // double dot after @
        ];

        foreach ($emailsToTest as $email) {
            Assert::exception(
                function () use ($email) {
                    new EmailAddress($email);
                },
                InvalidEmailAddressException::class
            );
        }
    }



    public function testLocalPart(): void {
        $emailAddress = new EmailAddress('disposable.style.email.with+symbol@example.com');

        Assert::equal('disposable.style.email.with+symbol', $emailAddress->getLocalPart());
    }



    public function testDomain(): void {
        $emailAddress = new EmailAddress('disposable.style.email.with+symbol@example.com');

        Assert::equal('example.com', $emailAddress->getDomain());
    }



    public function testGetValue(): void {
        $emailAddress = new EmailAddress('simple@example.com');

        Assert::equal('simple@example.com', (string)$emailAddress);
        Assert::equal('simple@example.com', $emailAddress->get());
    }



    public function testDisplayName(): void {
        $emailAddress = new EmailAddress('simple@example.com', 'John Doe');
        Assert::equal('John Doe <simple@example.com>', $emailAddress->getDisplayName());

        $emailAddress = new EmailAddress('simple@example.com');
        Assert::equal('simple@example.com', $emailAddress->getDisplayName());

        $emailAddress = new EmailAddress('simple@example.com', null);
        Assert::equal('simple@example.com', $emailAddress->getDisplayName());

        $emailAddress = new EmailAddress('simple@example.com', '');
        Assert::equal('simple@example.com', $emailAddress->getDisplayName());
    }

}


(new EmailAddressTest())->run();
