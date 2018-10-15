<?php declare(strict_types=1);

namespace Bio\App\Author\Api;

use Apitte\Core\Http\ApiRequest;
use Bio\App\Author\Data\EmailPasswordData;
use Bio\App\EmailAddress\EmailAddress;
use Bio\App\EmailAddress\InvalidEmailAddressException;
use Bio\Exceptions\Runtime\MalformedInputException;
use Nette\InvalidArgumentException;
use Nette\SmartObject;
use Nette\Utils\Arrays;
use Nette\Utils\Json;
use Nette\Utils\JsonException;


class AuthorRequestMapper {

    use SmartObject;



    /**
     * @throws MalformedInputException When mandatory fields are missing or have invalid types.
     */
    public static function dataFromRequest(ApiRequest $request): EmailPasswordData {
        try {
            // Not using native getJsonBody, due to its naive implementation
            $data = Json::decode($request->getBody(), Json::FORCE_ARRAY);

            $email = (string)Arrays::get($data, 'email');
            $password = (string)Arrays::get($data, 'password');

            $emailAddress = new EmailAddress($email);

        } catch (InvalidArgumentException|JsonException $e) {
            throw MalformedInputException::invalidData();

        } catch (InvalidEmailAddressException $e) {
            throw MalformedInputException::invalidEmailAddress();

        }

        return new EmailPasswordData($emailAddress, $password);
    }

}
