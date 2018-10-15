<?php

namespace BioTest\Core\Utils;

require __DIR__ . '/../../../../bootstrap.php';

use Bio\Core\Utils\Password;
use Tester\Assert;
use Tester\TestCase;
use function strlen;


class PasswordTest extends TestCase {

    public function testGenerate(): void {
        Assert::same(10, strlen(Password::generate()));
    }



    public function testHash(): void {
        $passwordHash = Password::hash('abc');

        Assert::true(Password::verify('abc', $passwordHash));
        Assert::false(Password::verify('abd', $passwordHash));
        Assert::false(Password::verify('abc', 'abc'));
    }

}


(new PasswordTest())->run();
