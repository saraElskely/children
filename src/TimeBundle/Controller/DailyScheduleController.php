<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\DailySchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TimeBundle\constant\Roles;
use TimeBundle\Service\DailyScheduleService;

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
        $todaySchedule = $this->get(DailyScheduleService::class)->getChildTodaySchedule($child_id);
        
        
        
        
        $todaySchedule = $this->get(DailyScheduleService::class)->createMothersDailySchedule(7,5);
        
        return $this->render('TimeBundle:dailyschedule:show.html.twig', array(
            'dailySchedules' => $todaySchedule,
            'child_id' => $child_id
        ));
    }
    
    
    public function scheduleDoneAction($schedule_id)
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
    
    
}
