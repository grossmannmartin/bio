<?php declare(strict_types=1);

namespace Bio\App\Author\Persistence;

use Bio\App\EmailAddress\EmailAddress;
use Bio\Exceptions\Runtime\EntryNotFoundException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityRepository;


class AuthorRepository extends EntityRepository {

    /**
     * @throws UniqueConstraintViolationException
     */
    public function persist(Author $author): void {
        $this->_em->persist($author);
        $this->_em->flush($author);
    }



    /**
     * @throws EntryNotFoundException
     */
    public function getById(string $id): Author {
        /** @var Author|null $author */
        $author = $this->findOneBy(['id' => $id]);

        if ($author === null) {
            throw EntryNotFoundException::notFound();
        }

        return $author;
    }



    /**
     * @throws EntryNotFoundException
     */
    public function getByEmail(EmailAddress $emailAddress): Author {
        /** @var Author|null $author */
        $author = $this->findOneBy(['email' => (string)$emailAddress]);

        if ($author === null) {
            throw EntryNotFoundException::notFound();
        }

        return $author;
    }

}
