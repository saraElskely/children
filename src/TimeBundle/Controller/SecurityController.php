<?php

namespace TimeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Form\LoginForm;

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
        
        return $this->render('TimeBundle:Security:login.html.twig', array(
            'last_username' => $lastUsername ,
            'error'  => $error,
        ));
//        'form' => $form->createView(),
    }
    
    public function logoutAction()
    {
    
        throw new \Exception('this should not be reached!');
    }

}
