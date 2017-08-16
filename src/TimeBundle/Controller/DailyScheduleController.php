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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use TimeBundle\Utility\Date;


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
    public function showChildScheduleAction($child_id, $page=1)
    {
//        $dailySchedules = $this->get(DailyScheduleService::class)->getChildDailySchedules($child_id);
//
//        $arrayOfDailyTasks = $this->get(DailyScheduleService::class)->getDayByDayDailySchedule($dailySchedules); // array => day is a key && value for each key is tasks in this day
//        dump($arrayOfDailyTasks);
//        die;
        $user = $this->getUser();
        $this->get(DailyScheduleService::class)->denyAccessUnlessGranted( $user, $child_id);
        
        $arrayOfDailyTasks = $this->get(TaskService::class)->getWeeklyChildTasks( $child_id, $page);
        return $this->render('TimeBundle:dailyschedule:index.html.twig', array(
            'dailySchedules' => $arrayOfDailyTasks,
            'child_id' => $child_id,
            'page' => $page
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
        
        $user = $this->getUser();
        $this->get(DailyScheduleService::class)->denyAccessUnlessGranted( $user, $child_id);
        
        $todayTasks = $this->get(TaskService::class)->getTodayChildTasks( $child_id);
        
        return $this->render('TimeBundle:dailyschedule:todaySchedule.html.twig', array(
            'dailySchedules' => $todayTasks,
            'child_id' => $child_id
        ));
    }
    
    
    public function changeScheduleStateAction(Request $request, $task_id, $state)
    {
        $user = $this->getUser();
        if($user->getRole() !== Roles::ROLE_CHILD) {
            throw new AccessDeniedException();
        }

        $childId = $user->getId() ;
        $this->get(DailyScheduleService::class)->changeScheduleState( $childId, $task_id, $state);
        
        $firewall = $this->container
                        ->get('security.firewall.map')
                        ->getFirewallConfig($request)
                        ->getName();
 
        
        $response = array("status" => $firewall, "data" => 1);
        
        if( $firewall === 'api') {
            return new JsonResponse();
        }

        
          // returned result as JSON
        return new JsonResponse($response); 
        
    }
    
    
}
