<?php


namespace TimeBundle\Controller;

use TimeBundle\Entity\DailySchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TimeBundle\constant\Roles;
use TimeBundle\Service\DailyScheduleService;

/**
 * Description of ApiDailyScheduleController
 *
 * @author saraelsayed
 */
class ApiDailyScheduleController extends Controller {
    
    public function changeScheduleStateAction($schedule_id ,$state)
    {
        $em = $this->getDoctrine()->getManager();
        $schedule = $em->getRepository('TimeBundle:DailySchedule')->findOneById($schedule_id);
//        dump($schedule);
        $schedule->setIsDone(!$schedule->getIsDone());
        $em->flush();

        $response = array("code" => 200, "status" => $schedule->getIsDone());
          // returned result as JSON
        return new Response(json_encode($response)); 
    }
    
    public function testAction()
    {
        
    }

   
}
