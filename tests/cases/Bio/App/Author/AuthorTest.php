<?php

namespace BioTest\App\Author;

require __DIR__ . '/../../../../bootstrap.php';

use Bio\App\Author\Persistence\Author;
use Bio\App\EmailAddress\EmailAddress;
use Tester\Assert;
use Tester\TestCase;


class AuthorTest extends TestCase {

    public function testDisplayName(): void {
        $author = new Author(new EmailAddress('simple@example.com'), '');
        Assert::equal('simple@example.com', $author->getDisplayName());
    }



    public function testVerifyPassword(): void {
        $author = new Author(new EmailAddress('simple@example.com'), '1234');

        Assert::true($author->verifyPassword('1234'));
        Assert::false($author->verifyPassword('12346'));
    }



    public function testCanLogin(): void {
        $author = new Author(new EmailAddress('simple@example.com'), '1234');

        Assert::true($author->authenticate('1234'));
        Assert::false($author->authenticate('12346'));

        $author->ban();

        Assert::false($author->authenticate('1234'));
        Assert::false($author->authenticate('12346'));
    }

}


(new AuthorTest())->run();
