<?php

namespace TimeBundle\Controller;

use TimeBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TimeBundle\Form\UserType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use TimeBundle\constant\Roles;
use TimeBundle\Service\UserService;
use TimeBundle\Utility\Paginator;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


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
    public function indexAction($page = 1)
    {
        $limit = 3;
//        dump($this->get(UserService::class)->getQueryCount());
        $result = $this->get(UserService::class)->getUsers($page, $limit);
        
        
//        $resultCount = $this->get(UserService::class)->getMothersCount();
//        $paginator = new Paginator($resultCount);
//        $maxPages = $paginator->getMaxPage();
//        $offest = $paginator->getOffest($page);
//        $users = $this->get(UserService::class)->getMothers( $offest ,$limit);
        
        
        return $this->render('TimeBundle:user:index.html.twig', $result);
    }
    
    public function filterAction(Request $request ,$page =1)
    { 
        if($this->getUser()->getRole() !== Roles::ROLE_ADMIN) {
            throw new AccessDeniedException();
        }
        $limit = 3;
        
//        $request->query->get('username');
//        
//        return new \Symfony\Component\HttpFoundation\JsonResponse($request->query);
//        return new \Symfony\Component\HttpFoundation\JsonResponse( $request->request);
//        dump($request);
//        die;
        $result = $this->get(UserService::class)
                    ->getFilteredUsers($request->query->get('username'),$request->query->get('role'), $page, $limit);
        $username = $request->query->get('username');
        $role = $request->query->get('role');
        $page = $request->query->get('page');
        $session = $this->get('session');
        $session->set('filter', array(
            'username' => $username,
            'role' => $role,
            ));
        
        return new \Symfony\Component\HttpFoundation\JsonResponse($result);
//            dump($session);
//            die;
//        return $this->render('TimeBundle:user:index.html.twig', $result);
//        return $this->render('TimeBundle:user:search.html.twig', array('users' => $users));
    }

    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction($id)
    {   
        $currUser = $this->getUser();
        $user = $this->get(UserService::class)->getUser($id);
        $this->get(UserService::class)->denyAccessUnlessGranted('show', $currUser, $user);

        $deleteForm = $this->createDeleteForm($user);
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
        $this->get(UserService::class)->denyAccessUnlessGranted('edit', $currUser, $user);
        
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
        $this->get(UserService::class)->denyAccessUnlessGranted('delete', $currUser, $user);
        
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get(UserService::class)->deleteUser($user->getId());
        }
            
        //if mother ==> error
        return $this->redirectToRoute('user_index');
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
