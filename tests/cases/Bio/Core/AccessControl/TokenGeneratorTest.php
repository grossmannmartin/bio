<?php

namespace BioTest\Core\AccessControl;

require __DIR__ . '/../../../../bootstrap.php';

use Bio\Core\AccessControl\TokenGenerator;
use BioTest\Fixtures\FakeSigner;
use Tester\Assert;
use Tester\TestCase;


class TokenGeneratorTest extends TestCase {

    public function testVerify(): void {
        $signer = new FakeSigner();

        $tokenGenerator = new TokenGenerator('', '', $signer);

        $token = $tokenGenerator->generate('ab', ['name' => 'Dipper']);

        $identity = $tokenGenerator->verify($token);

        Assert::equal('ab', $identity->getId());
        Assert::equal('Dipper', $identity->getDataValue('name'));
    }

}


(new TokenGeneratorTest())->run();
