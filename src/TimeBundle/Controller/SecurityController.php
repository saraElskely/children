<?php

namespace TimeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Form\LoginForm;
use TimeBundle\constant\Roles;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        
        $lastUsername = $authUtils->getLastUsername();
        
        $form = $this->createForm(LoginForm::class ,[
            '_username' => $lastUsername
        ]);
        
        return $this->render('TimeBundle:user:login.html.twig', array(
            'last_username' => $lastUsername ,
            'error'  => $error,
        ));
//        'form' => $form->createView(),
    }
    
    public function redirectAction()
    {
        $user = $this->getUser();
        if( $user->getRole() === Roles::ROLE_ADMIN) {
            return $this->redirectToRoute('user_index');
        } 
        if( $user->getRole() === Roles::ROLE_MOTHER) {
            return $this->redirectToRoute('mother_show_my_children',
                    array(
                        'motherId' => $user->getId()
                    ));
        }
        if( $user->getRole() === Roles::ROLE_CHILD ) {
           return $this->redirectToRoute('dailySchedule_index',
                   array(
                       'child_id'=> $user->getId()
                   )) ;
        }
    }

        public function logoutAction()
    {
    
        throw new \Exception('this should not be reached!');
    }

}
