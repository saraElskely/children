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
use TimeBundle\Utility\Date;
use TimeBundle\Security\ApiFirewallMatcher;
use TimeBundle\Exception\TimeBundleException;
use TimeBundle\constant\Exceptions;

/**
 * Dailyschedule controller.
 *
 */
class DailyScheduleController extends Controller {

    /**
     * Lists all child dailySchedule entities.
     *
     */
    public function showChildScheduleAction(Request $request, $child_id, $page = 1) {
//        $dailySchedules = $this->get(DailyScheduleService::class)->getChildDailySchedules($child_id);
//
//        $arrayOfDailyTasks = $this->get(DailyScheduleService::class)->getDayByDayDailySchedule($dailySchedules); // array => day is a key && value for each key is tasks in this day
//        dump($arrayOfDailyTasks);
//        die;
        $user = $this->getUser();
        $this->get(DailyScheduleService::class)->denyAccessUnlessGranted($user, $child_id);

        $arrayOfDailyTasks = $this->get(TaskService::class)->getWeeklyChildTasks($child_id, $page);
        $data = array(
            'dailySchedules' => $arrayOfDailyTasks,
            'child_id' => $child_id,
            'page' => $page
        );

        if (ApiFirewallMatcher::matches($request)) {
            return new JsonResponse(array('status' => 1, 'data' => $data));
        }
        return $this->render('TimeBundle:dailyschedule:index.html.twig', $data);
    }

    /**
     * Finds and displays a dailySchedule entity.
     *
     */
    public function showChildTodayScheduleAction(Request $request, $child_id) {
//        $todaySchedule = $this->get(DailyScheduleService::class)->getChildTodaySchedule($child_id);
//        $w = $this->get(DailyScheduleService::class)->deleteSchedule( 9, 6);
//        dump($w);
//        die();

        $user = $this->getUser();
        $this->get(DailyScheduleService::class)->denyAccessUnlessGranted($user, $child_id);

        $todayTasks = $this->get(TaskService::class)->getTodayChildTasks($child_id);

        $data = array(
            'dailySchedules' => $todayTasks,
            'child_id' => $child_id
        );

        if (ApiFirewallMatcher::matches($request)) {
            return new JsonResponse(array('status' => 1, 'data' => $data));
        }
        return $this->render('TimeBundle:dailyschedule:todaySchedule.html.twig', $data);
    }

    public function changeScheduleStateAction( $task_id, $state) {
        $user = $this->getUser();
        if ($user->getRole() !== Roles::ROLE_CHILD) {
            throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
        }
        $childId = $user->getId();
        $this->get(DailyScheduleService::class)->changeScheduleState($childId, $task_id, $state);
//        $firewall = $this->container
//                        ->get('security.firewall.map')
//                        ->getFirewallConfig($request)
//                        ->getName();
        return new JsonResponse(array("status" => 1, "data" => ['state' => !$state]));
    }

}
