<?php

namespace TimeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MotherController extends Controller
{
    public function showMyChildrenAction()
    {
        $mother = $this->getUser();
        $children = $mother->getChildren();
        return $this->render('TimeBundle:Mother:show_my_children.html.twig', array(
            'children' => $children 
        ));
    }

    public function addChildAction()
    {
        return $this->render('TimeBundle:Mother:add_child.html.twig', array(
            // ...
        ));
    }

    public function showMyChildrenTasksAction()
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $mother_id = $this->getUser()->getId();
        $adminTasks = $entityManager->getRepository('TimeBundle:Task')->getAdminTasks();
        $motherTasks = $entityManager->getRepository('TimeBundle:Task')->getMotherTasks($mother_id);

//        dump($motherTasks);
//        die();
        return $this->render('TimeBundle:Mother:show_my_children_tasks.html.twig', array(
            'adminTasks' => $adminTasks ,
            'motherTasks' => $motherTasks
        ));
    }

}
