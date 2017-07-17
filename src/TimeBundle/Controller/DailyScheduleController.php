<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\DailySchedule;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use TimeBundle\constant\Roles;

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
        $em = $this->getDoctrine()->getManager();
        
        $user = $this->getUser();
        $role = $user->getRoles();
        if($child_id == 0 && $role == Roles::ROLE_CHILD){ //if current user is child
            $dailySchedules = $em->getRepository('TimeBundle:DailySchedule')->findByUserInSchedule($user);
        }
        elseif ($child_id == 0) {
            throw new \Exception('your child id not found');
        }
        else{ //if current user [mother or admin ]
            $dailySchedules = $em->getRepository('TimeBundle:DailySchedule')->getChildDailySchedules($child_id);
        }

        $arrayOfDailyTasks = array(); // day is a key && value for each key is tasks in this day
        foreach ($dailySchedules as $task){
            $date = $task->getDate()->format('y-m-d');
            if(array_key_exists($date, $arrayOfDailyTasks)){
                array_push($arrayOfDailyTasks[$date], $task);
            }
            else{
                $arrayOfDailyTasks[$date]=array($task);
            }
        }
//        dump($weeklySchedule);
//        die();
        return $this->render('TimeBundle:dailyschedule:index.html.twig', array(
            'dailySchedules' => $arrayOfDailyTasks,
            'child_id' => $child_id
        ));
    }

    /**
     * Finds and displays a dailySchedule entity.
     *
     */
    public function showTodayScheduleAction($child_id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $role = $user->getRoles();
        
        if($child_id == 0 && $role == Roles::ROLE_CHILD){ //if current user is child
            $dailySchedules = $em->getRepository('TimeBundle:DailySchedule')->findBy(
                    ['userInSchedule' => $user ,
                        'date' => new \DateTime()]);
        }elseif($child_id == 0){
            throw new \Exception('your child id not found');
        }
        else {
            $dailySchedules = $em->getRepository('TimeBundle:DailySchedule')->getChildTodaySchedule($child_id);
        }

        return $this->render('TimeBundle:dailyschedule:show.html.twig', array(
            'dailySchedules' => $dailySchedules,
            'child_id' => $child_id
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
          // returned result as JSON
        return new Response(json_encode($response)); 
        
    }
}
