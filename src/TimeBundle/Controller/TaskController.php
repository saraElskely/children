<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Service\TaskService;
use TimeBundle\constant\Roles;
use Symfony\Component\HttpFoundation\JsonResponse;
use TimeBundle\Exception\TimeBundleException;
use TimeBundle\constant\Exceptions;
use TimeBundle\Security\ApiFirewallMatcher;
use TimeBundle\constant\General;
use TimeBundle\constant\Actions;

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
        $this->get(TaskService::class)->denyAccessUnlessGranted(Actions::INDEX, $user);
        
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

    public function filterAction(Request $request)
    {   
        if ($this->getUser()->getRole() !== Roles::ROLE_ADMIN) {
            throw new AccessDeniedException();
        }
        
        $taskName = $request->query->get('taskName') === '' ? null : $request->query->get('taskName') ;
        $schedule = $request->query->getInt('schedule',-1);
        
        $page = $request->query->get('page') === null ? 1 : $request->query->getInt('page', 1);
        if($page === 0) {
            throw new TimeBundleException(Exceptions::CODE_PAGE_NUM_NOT_FOUND);
        }
        $tasks = $this->get(TaskService::class)
                    ->getFilteredTasks($taskName, $schedule, $page, General::PAGINATION_LIMIT);

//        dump($tasks);
//        die;
        return new JsonResponse(array('status'=> 1 , 'data'=>$tasks));
//        return $this->render('TimeBundle:task:search.html.twig', array('tasks' => $tasks));
    
    }
    /**
     * Creates a new task entity.
     *
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        $this->get(TaskService::class)->denyAccessUnlessGranted(Actions::CREATE, $user);
        
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
        $this->get(TaskService::class)->denyAccessUnlessGranted(Actions::SHOW, $user, $task);
        
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
        $this->get(TaskService::class)->denyAccessUnlessGranted(Actions::EDIT, $user, $task);

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
        
        $this->get(TaskService::class)->denyAccessUnlessGranted(Actions::DELETE, $user, $task);
        
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
