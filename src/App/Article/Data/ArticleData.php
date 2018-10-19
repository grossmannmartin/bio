<?php declare(strict_types=1);

namespace Bio\App\Article\Data;

use Nette\SmartObject;


class ArticleData {

    use SmartObject;


    /**
     * @var string
     */
    protected $heading;

    /**
     * @var PartResponseData[]
     */
    protected $content;

    /**
     * @var string
     */
    protected $lead;



    /**
     * @param PartResponseData[] $content
     */
    public function __construct(string $heading, string $lead, array $content) {
        $this->heading = $heading;
        $this->lead = $lead;
        $this->content = $content;
    }



    public function getHeading(): string {
        return $this->heading;
    }



    /**
     * @return PartResponseData[] $content
     */
    public function getContent(): array {
        return $this->content;
    }



    public function getLead(): string {
        return $this->lead;
    }

}
