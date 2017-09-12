<?php

namespace TimeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use TimeBundle\constant\Roles;
use TimeBundle\Service\DailyScheduleService;
use TimeBundle\Service\TaskService;
use Symfony\Component\HttpFoundation\Request;
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
    public function showChildScheduleAction(Request $request, $child_id) {
//        $dailySchedules = $this->get(DailyScheduleService::class)->getChildDailySchedules($child_id);
//
//        $arrayOfDailyTasks = $this->get(DailyScheduleService::class)->getDayByDayDailySchedule($dailySchedules); // array => day is a key && value for each key is tasks in this day
//        dump($arrayOfDailyTasks);
//        die;
        
        $user = $this->getUser();
        $this->get(DailyScheduleService::class)->denyAccessUnlessGranted($user, $child_id);
        
        $week = $request->query->getInt('week',-1);
//        dump($week);die;
        $data = $this->get(TaskService::class)->getWeeklyChildTasks($child_id, $week);

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

    public function changeScheduleStateAction(Request $request, $task_id) {
        $user = $this->getUser();
        if ($user->getRole() !== Roles::ROLE_CHILD) {
            throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
        }
        $childId = $user->getId();
        $state = $request->request->getBoolean('state');

        $this->get(DailyScheduleService::class)->changeScheduleState($childId, $task_id, $state);
//        $firewall = $this->container
//                        ->get('security.firewall.map')
//                        ->getFirewallConfig($request)
//                        ->getName();
        return new JsonResponse(array("status" => 1, "data" => ['state' => $state]));
    }

}
