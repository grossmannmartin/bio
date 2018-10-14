<?php declare(strict_types=1);

namespace Bio\Core\AccessControl;

use Bio\Exceptions\Logic\LogicException;
use Nette\DI\CompilerExtension;


class AccessControlExtension extends CompilerExtension {

    private $defaults = [
        'issuer' => 'example.com',
        'audience' => 'example.com',
        'privateKeyPath' => null,
        'publicKeyPath' => null,
    ];



    public function loadConfiguration(): void {
        $config = $this->validateConfig($this->defaults);
        $containerBuilder = $this->getContainerBuilder();

        if ($config['privateKeyPath'] === null || $config['publicKeyPath'] === null) {
            throw new LogicException('You must provide private and public key for secured authentication');
        }

        $containerBuilder->addDefinition($this->prefix('token'))
                         ->setType(Token::class)
                         ->setArguments(
                             [
                                 'issuer' => $config['issuer'],
                                 'audience' => $config['audience'],
                                 'privateKeyPath' => $config['privateKeyPath'],
                                 'publicKeyPath' => $config['publicKeyPath'],
                             ]
                         );

        $containerBuilder->addDefinition($this->prefix('authenticator'))
                         ->setType(JwtAuthenticator::class);
    }

}
