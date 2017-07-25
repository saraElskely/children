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
    public function indexAction($page = 1)
    {
        $limit = 2;
        $resultCount = $this->get(UserService::class)->getMothersCount();
        $paginator = new Paginator($resultCount);
        $maxPages = $paginator->getMaxPage();
        $offest = $paginator->getOffest($page);
        $users = $this->get(UserService::class)->getMothers( $offest ,$limit);
        
        
        return $this->render('TimeBundle:user:index.html.twig', array(
            'users' => $users,
            'currentPage' => $page,
            'maxPages' => $maxPages
        ));
    }

    /**
     * Creates a new user entity.
     *
     */
//    public function registerAction(Request $request, $role)
//    {   
//        $passwordEncoder = $this->get('security.password_encoder');
//        $user = new User();
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $password = $passwordEncoder->encodePassword($user, $user->getPassword());
//            $user->setPassword($password);
//            $user->setRole($role);
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($user);
//            $em->flush();
//
//            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
//        }
//
//        return $this->render('TimeBundle:user:new.html.twig', array(
//            'user' => $user,
//            'form' => $form->createView(),
//        ));
//    }
    
    

    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction(User $user)
    {
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
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('TimeBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
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
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get(UserService::class)->deleteUser($user->getId());
        }

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
