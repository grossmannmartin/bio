<?php declare(strict_types=1);

namespace Bio\Core\AccessControl\Identity;

use Nette\SmartObject;
use Nette\Utils\Arrays;


class Identity {

    use SmartObject;


    /**
     * @var string
     */
    protected $id;

    /**
     * @var array
     */
    protected $data;



    public function __construct(string $id, array $data = []) {
        $this->id = $id;
        $this->data = $data;
    }



    public function getId(): string {
        return $this->id;
    }



    /**
     * @return mixed|null
     */
    public function getDataValue(string $key) {
        return Arrays::get($this->data, $key, null);
    }



    public function getData(): array {
        return $this->data;
    }

}
