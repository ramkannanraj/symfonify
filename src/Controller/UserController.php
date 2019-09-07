<?php
namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use App\Form\UserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller 
{
	/**
   * List Users
   * 
	 * @Route("/", name="user")
   * 
	 * @Method({"GET"})
	 */
	public function index()
	{
     $form  = $this->createForm(UserType::class);
  	 $users = $this->getDoctrine()->getRepository(User::class)->findAll();

  	 return $this->render('user/index.html.twig', ['users' => $users, 'user_form' => $form->createView()]);		
	}

  /**
   * Add Users
   *
   * @Route("/users/add", name="add_user")
   * 
   * @param Request $request Data request
   *
   * @Method({"GET", "POST"})
   */
  public function add(Request $request)
  {
      $form = $this->createForm(UserType::class);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $user = $form->getData();
        $em   = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        $user_id = $user->getId();
        if ($user_id) {
          $this->addFlash('success', 'Created Successfully');
        } else {
          $this->addFlash('failure', 'Something is wrong');
        }

       return $this->redirectToRoute('user');
      }//end if

      return $this->redirectToRoute('user');
  }

  /**
   * Delete a user and its associations
   *
   * @param Request $request Data request
   * @param integer $id      User Id
   *
   * @Route("/user/delete/{id}", name="delete_user")
   *
   * @Method({"DELETE"})
   */
  public function delete(Request $request, int $id)
  {
      $user = $this->getDoctrine()->getRepository(User::class)->find($id);
      $em   = $this->getDoctrine()->getManager();
      $em->remove($user);
      $em->flush();
      $this->addFlash('failure', 'User deleted!');

      return $this->redirectToRoute('user');
  }

  /**
   * add user to a  group
   *
   * @param Request $request Data request
   * @param integer $id      User Id
   * @param integer $groupId User Id
   *
   * @Route("/user/{id}/group/{groupId}", name="add_user_group")
   *
   * @Method({"GET", "POST"})
   */ 
  public function addUserGroup(Request $request, int $id, int $groupId)
  {
      $user  = $this->getDoctrine()->getRepository(User::class)->find($id);
      $group = $this->getDoctrine()->getRepository(Group::class)->find($groupId);
      $user->addGroup($group);
      $em = $this->getDoctrine()->getManager();
      $em->flush();
      $this->addFlash('success', 'User added to Group !');

      return new Response(Response::HTTP_OK);
  }

  /**
   * remove a user from a group
   *
   * @param Request $request Data request
   * @param integer $id      User Id
   * @param integer $groupId User Id
   * 
   * @Route("remove/user/{id}/group/{groupId}", name="remove_user_group")
   *
   * @Method({"DELETE"})
   */ 
  public function removeUserGroup(Request $request, int $id, int $groupId)
  {
      $user  = $this->getDoctrine()->getRepository(User::class)->find($id);
      $group = $this->getDoctrine()->getRepository(Group::class)->find($groupId);
      $user->removeGroup($group);
      $em = $this->getDoctrine()->getManager();
      $em->flush();
      $this->addFlash('failure', 'User removed from Group !');

      return new Response(Response::HTTP_OK);

  }

}
