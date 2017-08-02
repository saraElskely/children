<?php

namespace TimeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Entity\User;
use TimeBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TimeBundle\constant\Roles;
use TimeBundle\Service\UserService;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class MotherController extends Controller {

    public function motherRegistrationAction(Request $request) {
        $passwordEncoder = $this->get('security.password_encoder');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());

                $user = $this->get(UserService::class)
                        ->createUser($user->getUsername(), $user->getFname(), $user->getFname(), $password, Roles::ROLE_MOTHER, $user->getAge());

            return $this->redirectToRoute('mother_show_my_children',array(
                'motherId' => $this->getUser()->getId()
            ));
        }

        return $this->render('TimeBundle:user:new.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
    }

    public function addChildAction(Request $request) 
    {
        if( $this->getUser()->getRole() !== Roles::ROLE_MOTHER)
            throw new AccessDeniedException();

        $passwordEncoder = $this->get('security.password_encoder');

        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPassword());

                $user = $this->get(UserService::class)
                        ->createUser($user->getUsername(), $user->getFname(), $user->getFname(), $password, Roles::ROLE_CHILD, $user->getAge(),  $this->getUser());

            return $this->redirectToRoute('mother_show_my_children',array(
                'motherId' => $this->getUser()->getId()
            ));
        }

        return $this->render('TimeBundle:user:new.html.twig', array(
                    'user' => $user,
                    'form' => $form->createView(),
        ));
        
    }

    public function showMyChildrenAction($motherId) 
    {
        $user = $this->getUser();
        if( $user->getRole() === Roles::ROLE_ADMIN || $user->getRole() === Roles::ROLE_MOTHER )
        $mother = $this->get(UserService::class)->getUser($motherId);
        $children = $mother->getChildren();

        return $this->render('TimeBundle:Mother:show_my_children.html.twig', array(
                    'children' => $children
        ));
    }

//    public function showMyChildrenTasksAction()
//    {
//        $entityManager = $this->getDoctrine()->getManager();
//        
//        $mother_id = $this->getUser()->getId();
//        $adminTasks = $entityManager->getRepository('TimeBundle:Task')->getAdminTasks();
//        $motherTasks = $entityManager->getRepository('TimeBundle:Task')->getMotherTasks($mother_id);
//
////        dump($motherTasks);
////        die();
//        return $this->render('TimeBundle:Mother:show_my_children_tasks.html.twig', array(
//            'adminTasks' => $adminTasks ,
//            'motherTasks' => $motherTasks
//        ));
//    }
    
}
