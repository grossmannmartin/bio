<?php declare(strict_types=1);


namespace Bio\Core\Api;

use Apitte\Negotiation\Http\ArrayEntity;

interface IResponseData {

    public function toArray(): array;



    public function toArrayEntity(): ArrayEntity;

}
