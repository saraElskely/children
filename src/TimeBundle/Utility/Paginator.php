<?php

namespace TimeBundle\Utility;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Description of PaginatorService
 *
 * @author saraelsayed
 */
class Paginator {
    private $maxPage;
    private $offest;
    private $numOfItem;
    private $limit;
    
    public function __construct($numOfItem, $limit = 2) {
        if($numOfItem > 1){
            $this->numOfItem = $numOfItem ;
        } else {
            $this->numOfItem = 0 ;
        }
        $this->limit = $limit  ;
        $this->maxPage = ceil($this->numOfItem / $this->limit);
        
    }
    
    public function getMaxPage()
    {
        return $this->maxPage; 
    }
    
    public function getOffest( $page = 1)
    {
        ($page <= $this->getMaxPage()) ? $this->offest= $this->limit*($page-1) : $this->offest= 0;
        return $this->offest ;
    }
    
}
