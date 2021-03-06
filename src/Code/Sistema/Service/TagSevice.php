<?php

namespace Code\Sistema\Service;

use Doctrine\ORM\EntityManager;
use Code\Sistema\Entity\Tag;
use Code\Sistema\Service\AbstractService;

class TagSevice extends AbstractService {

    public function __construct(EntityManager $em, Tag $tag) {
        parent::__construct($em);
        $this->object = $tag;
        $this->entity = "Code\Sistema\Entity\Tag";
    }
    
    public function getTagsAvailable($tags) {
        $string = "0";
        foreach ($tags as $tag) {
            $string .= ",{$tag->getId()}";
        }
        $repo = $this->em->getRepository($this->entity);
        return $repo->getTagsAvailable($string);
    }

}
