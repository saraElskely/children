<?php

namespace TimeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MotherController extends Controller
{
    public function showMyChildrenAction()
    {
        return $this->render('TimeBundle:Mother:show_my_children.html.twig', array(
            // ...
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
        return $this->render('TimeBundle:Mother:show_my_children_tasks.html.twig', array(
            // ...
        ));
    }

}
