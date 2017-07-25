<?php


namespace TimeBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
/**
 * Description of ResponseService
 *
 * @author saraelsayed
 */
class ResponseService {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }
    
    public function getResponse($firewall, $data = '', $twig = '')
    {
        if ($firewall === 'api' ){
            return new JsonResponse(array('data' => $data));
        }else{
            
        }
    }
}
