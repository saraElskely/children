<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Service\TaskService;
use TimeBundle\constant\Roles;
//use JMS\Serializer\SerializerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use TimeBundle\Exception\TimeBundleException;
use TimeBundle\constant\Exceptions;
use TimeBundle\Security\ApiFirewallMatcher;
use TimeBundle\constant\General;

/**
 * Task controller.
 *
 */
class TaskController extends Controller
{
    /**
     * Lists all task entities.
     *
     */
    public function indexAction(Request $request)
    {        
        $user = $this->getUser();
        $this->get(TaskService::class)->denyAccessUnlessGranted('index', $user);
        if($user->getRole() === Roles::ROLE_ADMIN) {
            $session = $this->get('session');
            $session->set('filter', array(
                'taskName' => null,
                'schedule' => -1,
            ));
        }
        $tasks = $this->get(TaskService::class)
                    ->getTasks($user->getId(),$user->getRole());

        if( ApiFirewallMatcher::matches($request) )
        {
            return new JsonResponse(array('status'=> 1 , 'data'=>['tasks' => $tasks]));
        }
        return $this->render('TimeBundle:task:index.html.twig', array(
            'tasks' => $tasks,
        ));
    }

    public function filterAction(Request $request, $page=1)
    {   
        if ($this->getUser()->getRole() !== Roles::ROLE_ADMIN) {
            throw new AccessDeniedException();
        }
        $limit = General::PAGINATION_LIMIT;
        $taskName = $request->query->get('taskName') === '' ? null : $request->query->get('taskName') ;
        $schedule = $request->query->getInt('schedule',-1);
        
        $session = $this->get('session');
        $session->set('filter', array(
            'taskName' => $taskName,
            'schedule' => $schedule,
        ));
        $tasks = $this->get(TaskService::class)
                    ->getFilteredTasks($taskName, $schedule, $page, $limit);

//        dump($tasks);
//        die;
        return new JsonResponse($tasks);
//        return $this->render('TimeBundle:task:search.html.twig', array('tasks' => $tasks));
    
    }
    /**
     * Creates a new task entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $this->get(TaskService::class)->denyAccessUnlessGranted('new', $user);
        
        $task = new Task();
        $form = $this->createForm('TimeBundle\Form\TaskType', $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $creator = $this->getUser();

            $task = $this->get(TaskService::class)
                    ->createTask($task->getTaskName(),$task->getSchedule(),$creator);
            
            return $this->redirectToRoute('task_show', array('id' => $task->getId()));
        }

        return $this->render('TimeBundle:task:new.html.twig', array(
            'task' => $task,
            'form' => $form->createView(),
        ));
    }

    
    /**
     * Finds and displays a task entity.
     *
     */
    public function showAction(Request $request, $task_id)
    {
        $user = $this->getUser();
        $task = $this->get(TaskService::class)->getTask($task_id);
        $this->get(TaskService::class)->denyAccessUnlessGranted('show', $user, $task);
        
        $deleteForm = $this->createDeleteForm($task);
        if( ApiFirewallMatcher::matches($request) )
        {
            return new JsonResponse(array('status'=> 1 , 'data'=>['task' => $task]));
        }

        return $this->render('TimeBundle:task:show.html.twig', array(
            'task' => $task,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing task entity.
     *
     */
    public function editAction(Request $request, $task_id)
    {
        $user = $this->getUser();
        $task = $this->get(TaskService::class)->getTask($task_id);
        $this->get(TaskService::class)->denyAccessUnlessGranted('edit', $user, $task);

        $deleteForm = $this->createDeleteForm($task);
        $editForm = $this->createForm('TimeBundle\Form\TaskType', $task);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->get(TaskService::class)
                    ->updateTask($task->getId(), $task->getTaskName(),$task->getSchedule());
            
            return $this->redirectToRoute('task_edit', array('task_id' => $task->getId()));
        }

        return $this->render('TimeBundle:task:edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a task entity.
     *
     */
    public function deleteAction(Request $request, $task_id)
    {
        $user = $this->getUser();
        $task = $this->get(TaskService::class)->getTask($task_id);
        
        $this->get(TaskService::class)->denyAccessUnlessGranted('delete', $user, $task);
        
        $form = $this->createDeleteForm($task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get(TaskService::class)->deleteTask($task->getId());
        }

        if( ApiFirewallMatcher::matches($request) )
        {
            return new JsonResponse(array('status'=> 1 , 'data'=> 'task deleted'));
        }
        return $this->redirectToRoute('task_index');
    }

    /**
     * Creates a form to delete a task entity.
     *
     * @param Task $task The task entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Task $task)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('task_delete', array('task_id' => $task->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
