<?php

namespace TimeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Entity\User;
use TimeBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TimeBundle\constant\Roles;

class MotherController extends Controller
{
    
    public function register(Request $request, $role)
    {   
        $passwordEncoder = $this->get('security.password_encoder');
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($password);
            $user->setRole($role);
            if($role == Roles::ROLE_CHILD)
            {
                $mother = $this->getUser();
                $user->setMother($mother);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
//            return $user;
            return $this->redirectToRoute('mother_show_my_children');
        }

        return $this->render('TimeBundle:user:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }
    
    public function motherRegistrationAction(Request $request)
    {
        return $this->register($request, Roles::ROLE_MOTHER);
//        return $this->redirectToRoute('mother_show_my_children');
    }
    
    public function addChildAction(Request $request)
    {
        return $this->register($request, Roles::ROLE_CHILD);
//        dump($child);
//        die();
//        if($child instanceof User) {
//            $mother = $this->getUser();
//            $mother->addChild($child);
//            $em =$this->getDoctrine()->getManager()->persist($mother);
//                    $em->flush();
//            return $this->redirectToRoute('mother_show_my_children');
//        }
//        else {
//            return $child;
//        }
        
    }

    public function showMyChildrenAction()
    {
        $mother = $this->getUser();
        $children = $mother->getChildren();
        return $this->render('TimeBundle:Mother:show_my_children.html.twig', array(
            'children' => $children 
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
