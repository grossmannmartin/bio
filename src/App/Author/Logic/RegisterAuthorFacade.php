<?php declare(strict_types=1);

namespace Bio\App\Author\Logic;

use Bio\App\Author\Data\EmailPasswordData;
use Bio\App\Author\Persistence\Author;
use Bio\App\Author\Persistence\AuthorRepository;
use Bio\Exceptions\Runtime\DuplicateEntryException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Nette\SmartObject;


class RegisterAuthorFacade {

    use SmartObject;


    /**
     * @var AuthorRepository
     */
    protected $authorRepository;



    public function __construct(AuthorRepository $authorRepository) {
        $this->authorRepository = $authorRepository;
    }



    /**
     * @throws DuplicateEntryException
     */
    public function registerAuthor(EmailPasswordData $data): Author {
        return $this->register($data);
    }



    /**
     * @throws DuplicateEntryException
     */
    private function register(EmailPasswordData $data, $asAdmin = false): Author {
        $password = $data->getPassword();
        $emailAddress = $data->getEmailAddress();

        $author = new Author($emailAddress, $password);

        if ($asAdmin) {
            $author->makeAdmin();
        }

        try {
            $this->authorRepository->persist($author);
        } catch (UniqueConstraintViolationException $e) {
            throw DuplicateEntryException::emailNotUnique();
        }

        return $author;
    }



    /**
     * @throws DuplicateEntryException
     */
    public function registerAdmin(EmailPasswordData $data): Author {
        return $this->register($data, true);
    }

}
