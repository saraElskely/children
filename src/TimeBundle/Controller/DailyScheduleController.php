<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\DailySchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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

        return $this->render('dailyschedule/index.html.twig', array(
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

        return $this->render('dailyschedule/show.html.twig', array(
            'dailySchedules' => $dailySchedules,
        ));
    }
}
