<?php declare(strict_types=1);

namespace Bio\Core\AccessControl\Identity;

use Nette\SmartObject;


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

}
