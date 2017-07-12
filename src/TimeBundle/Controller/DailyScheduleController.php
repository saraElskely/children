<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\DailySchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


/**
 * Dailyschedule controller.
 *
 */
class DailyScheduleController extends Controller
{
    /**
     * Lists all dailySchedule entities.
     *
     */
    public function showUserScheduleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $dailySchedules = $em->getRepository('TimeBundle:DailySchedule')->findByUserInSchedule($user);
//        dump($dailySchedules);
//        die();

        return $this->render('TimeBundle:dailyschedule:index.html.twig', array(
            'dailySchedules' => $dailySchedules,
        ));
    }

    /**
     * Finds and displays a dailySchedule entity.
     *
     */
    public function showTodayScheduleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $dailySchedules = $em->getRepository('TimeBundle:DailySchedule')->findBy(
                ['userInSchedule' => $user ,
                    'date' => new \DateTime()]);
//        dump($dailySchedules);
//        die();
        $schedule = $em->getRepository('TimeBundle:DailySchedule')->findOneById(4);
        return $this->render('TimeBundle:dailyschedule:show.html.twig', array(
            'dailySchedules' => $dailySchedules,
            's'=> $schedule
        ));
    }
    
    public function scheduleDoneAction($schedule_id)
    {
        $em = $this->getDoctrine()->getManager();
        $schedule = $em->getRepository('TimeBundle:DailySchedule')->findOneById($schedule_id);
//        dump($schedule);
        $done = !$schedule->getIsDone() ;
        $schedule->setIsDone($done);
        $em->flush();

        $response = array("code" => 200, "status" => $schedule->getIsDone());
          //you can return result as JSON
        return new Response(json_encode($response)); 
        
    }
}
