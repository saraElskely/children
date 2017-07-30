<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\DailySchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use TimeBundle\constant\Roles;
use TimeBundle\Service\DailyScheduleService;
use TimeBundle\Service\UserService;
use TimeBundle\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;


/**
 * Dailyschedule controller.
 *
 */
class DailyScheduleController extends Controller
{
    /**
     * Lists all child dailySchedule entities.
     *
     */
    public function showChildScheduleAction($child_id)
    {
        $dailySchedules = $this->get(DailyScheduleService::class)->getChildDailySchedules($child_id);

        $arrayOfDailyTasks = $this->get(DailyScheduleService::class)->getDayByDayDailySchedule($dailySchedules); // array => day is a key && value for each key is tasks in this day
       
        return $this->render('TimeBundle:dailyschedule:index.html.twig', array(
            'dailySchedules' => $arrayOfDailyTasks,
            'child_id' => $child_id
        ));
    }

    /**
     * Finds and displays a dailySchedule entity.
     *
     */
    public function showChildTodayScheduleAction($child_id)
    {
//        $todaySchedule = $this->get(DailyScheduleService::class)->getChildTodaySchedule($child_id);
        
//        $w = $this->get(DailyScheduleService::class)->deleteSchedule( 9, 6);
//        dump($w);
//        die();
        $startDate =  new \DateTime();
            $startDate->modify('sunday this week');
        $this->get(TaskService::class)->getWeeklyChildTasks($startDate, $child_id);
        
        $todayTasks = $this->get(TaskService::class)->getTodayChildTasks( $child_id);
        
        return $this->render('TimeBundle:dailyschedule:todaySchedule.html.twig', array(
            'dailySchedules' => $todayTasks,
            'child_id' => $child_id
        ));
    }
    
    
    public function changeScheduleStateAction(Request $request, $task_id, $state)
    {
        $childId = $this->getUser()->getId();
        
        $this->get(DailyScheduleService::class)->changeScheduleState( $childId, $task_id, $state);
        
        $firewall = $this->container
                        ->get('security.firewall.map')
                        ->getFirewallConfig($request)
                        ->getName();
 
        
        $response = array("status" => $firewall, "data" => 1);
        
        if( $firewall === 'api')
        {
            return new JsonResponse();
        }

        
          // returned result as JSON
        return new JsonResponse($response); 
        
    }
    
    
}
