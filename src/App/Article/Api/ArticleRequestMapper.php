<?php declare(strict_types=1);

namespace Bio\App\Article\Api;

use Apitte\Core\Http\ApiRequest;
use Bio\App\Article\Data\ArticleData;
use Bio\App\Article\Data\PartResponseData;
use Bio\Exceptions\Runtime\MalformedInputException;
use Nette\InvalidArgumentException;
use Nette\SmartObject;
use Nette\Utils\Arrays;
use Nette\Utils\Json;
use Nette\Utils\JsonException;
use function is_array;


class ArticleRequestMapper {

    use SmartObject;



    /**
     * @throws MalformedInputException When mandatory fields are missing or have invalid types.
     */
    public static function dataFromRequest(ApiRequest $request): ArticleData {
        try {
            // Not using native getJsonBody, due to its naive implementation
            $data = Json::decode($request->getBody(), Json::FORCE_ARRAY);

            $heading = (string)Arrays::get($data, 'heading');
            $lead = (string)Arrays::get($data, 'lead');
            $content = (array)Arrays::get($data, 'content');

            $content = array_map(
                function ($item) {
                    if (!is_array($item)) {
                        throw MalformedInputException::invalidData();
                    }

                    return new PartResponseData(
                        Arrays::get($item, 'type'),
                        Arrays::get($item, 'data')
                    );
                },
                $content
            );
        } catch (InvalidArgumentException|JsonException $e) {
            throw MalformedInputException::invalidData();
        }

        return new ArticleData($heading, $lead, $content);
    }

}
