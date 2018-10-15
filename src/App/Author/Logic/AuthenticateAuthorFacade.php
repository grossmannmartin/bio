<?php declare(strict_types=1);

namespace Bio\App\Author\Logic;

use Bio\App\Author\Data\EmailPasswordData;
use Bio\App\Author\Persistence\Author;
use Bio\App\Author\Persistence\AuthorRepository;
use Bio\Core\AccessControl\Exceptions\AuthenticationException;
use Bio\Exceptions\Runtime\EntryNotFoundException;
use Nette\SmartObject;


class AuthenticateAuthorFacade {

    use SmartObject;


    /**
     * @var AuthorRepository
     */
    protected $authorRepository;



    public function __construct(AuthorRepository $authorRepository) {
        $this->authorRepository = $authorRepository;
    }



    /**
     * @throws AuthenticationException
     */
    public function authenticate(EmailPasswordData $emailPasswordData): Author {
        try {
            $author = $this->authorRepository->getByEmail($emailPasswordData->getEmailAddress());
        } catch (EntryNotFoundException $e) {
            throw AuthenticationException::emailNotFound();
        }


        if (!$author->authenticate($emailPasswordData->getPassword())) {
            if ($author->hasBan()) {
                throw AuthenticationException::disabledUser();
            }

            throw  AuthenticationException::badPassword();
        }

        return $author;
    }

}
