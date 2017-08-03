<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Service\TaskService;

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
    public function indexAction()
    {        
        $user = $this->getUser();
        $this->get(TaskService::class)->denyAccessUnlessGranted('index', $user);

        $tasks = $this->get(TaskService::class)
                    ->getTasks($user->getId(),$user->getRole());

        return $this->render('TimeBundle:task:index.html.twig', array(
            'tasks' => $tasks,
        ));
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
    public function showAction( $task_id)
    {
        $user = $this->getUser();
        $task = $this->get(TaskService::class)->getTask($task_id);
        $this->get(TaskService::class)->denyAccessUnlessGranted('show', $user, $task);
        
        $deleteForm = $this->createDeleteForm($task);

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
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('task_edit', array('task_id' => $task->getId()));
        }

        return $this->render('TimeBundle:task:edit.html.twig', array(
            'task' => $task,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    public function filterAction(Request $request)
    {        
//        if ($form->isSubmitted()) {
        
            $tasks = $this->get(TaskService::class)
                    ->getFilteredTasks($request->query->get('taskName'), $request->query->get('schedule'),NULL);
            dump($tasks);
            die;
            return $this->render('TimeBundle:task:search.html.twig', array('tasks' => $tasks));
//        }

        
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
