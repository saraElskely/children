<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TimeBundle\constant\Roles;
use TimeBundle\Service\UserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use TimeBundle\Exception\TimeBundleException;
use TimeBundle\constant\Exceptions;
use TimeBundle\Security\ApiFirewallMatcher;
use TimeBundle\constant\General;
use TimeBundle\constant\Actions;



/**
 * User controller.
 *
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     */
    
    // authorization missing 
    public function indexAction(Request $request)
    {
//        if($this->getUser()->getRole() !== Roles::ROLE_ADMIN) {
//            throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
//        }
//        $limit = General::PAGINATION_LIMIT;
//
//        $result = $this->get(UserService::class)->getUsers($page, $limit);
//        $session = $this->get('session');
//        $session->set('filter', array(
//            'username' => null,
//            'role' => null,
//            ));

        return  $this->filterAction($request);
    }
    
    public function getMothersAction(Request $request)
    {   
        if($this->getUser()->getRole() !== Roles::ROLE_ADMIN) {
            throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
        }
        $users = $this->get(UserService::class)->getMothers();
        if( ApiFirewallMatcher::matches($request) )
        {
            return new JsonResponse(array('status'=> 1 , 'data'=> ['users' => $users]));
        }
        return $this->render('TimeBundle:user:mother.html.twig', array(
            'users' => $users ,
        ));
    }

    public function filterAction(Request $request )
    { 
        if($this->getUser()->getRole() !== Roles::ROLE_ADMIN) {
            throw new TimeBundleException(Exceptions::CODE_ACCESS_DENIED);
        }
        $limit = General::PAGINATION_LIMIT;
        $username = $request->query->get('username');
        
//        dump($request->query->get('page'));
//        die;
        $role = $request->query->get('role') ;
        if( !is_null($role)) {
            $role = $role === ''? null : $request->query->getInt('role', null);
        }
        if($role === 0) {
            throw new TimeBundleException(Exceptions::CODE_ROLE_NOT_FOUND);
        }
        
        $page = $request->query->get('page') === null ? 1 : $request->query->getInt('page', 1);
        if($page === 0) {
            throw new TimeBundleException(Exceptions::CODE_PAGE_NUM_NOT_FOUND);
        }
        $result = $this->get(UserService::class)
                    ->getFilteredUsers($username, $role, $page, $limit);
        
        if( $request->isXmlHttpRequest() || ApiFirewallMatcher::matches($request) ) {
            return new JsonResponse(array('status'=> 1 , 'data'=>$result));
        }
        return $this->render('TimeBundle:user:index.html.twig', $result);
        
    }

    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction(Request $request, $id)
    {   
        $currUser = $this->getUser();
        $user = $this->get(UserService::class)->getUser($id);
        $this->get(UserService::class)->denyAccessUnlessGranted(Actions::SHOW, $currUser, $user);

        $deleteForm = $this->createDeleteForm($user);
        if( ApiFirewallMatcher::matches($request) )
        {
            return new JsonResponse(array('status'=> 1 , 'data'=> ['user' => $user]));
        }
        return $this->render('TimeBundle:user:show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $passwordEncoder = $this->get('security.password_encoder');
        
        $currUser = $this->getUser();
        $user = $this->get(UserService::class)->getUser($id);
        $this->get(UserService::class)->denyAccessUnlessGranted(Actions::EDIT, $currUser, $user);
        
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('TimeBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $user->setPassword($passwordEncoder->encodePassword($user, $user->getPassword()));
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('TimeBundle:user:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $currUser = $this->getUser();
        $user = $this->get(UserService::class)->getUser($id);
        $this->get(UserService::class)->denyAccessUnlessGranted(Actions::DELETE, $currUser, $user);
        
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get(UserService::class)->deleteUser($user->getId());
        }
        if( ApiFirewallMatcher::matches($request) )
        {
            return new JsonResponse(array('status'=> 1 , 'data'=> 'user deleted'));
        }
        return $this->redirectToRoute('login_redirection');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
