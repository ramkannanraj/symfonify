<?php
namespace App\Controller;

use App\Entity\Group;
use App\Form\GroupsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupController extends Controller 
{
	/**
   * List Groups
   * 
	 * @Route("/groups", name="groups")
   * 
	 * @Method({"GET"})
	 */
	public function index()
	{
      $form   = $this->createForm(GroupsType::class);
      $groups = $this->getDoctrine()->getRepository(Group::class)->findAll();

      return $this->render('groups/index.html.twig', ['groups' => $groups, 'form' => $form->createView()]);		
	}

	/**
   * Add Group
   *
   * @param Request $request Data request
   *
   * @Route("/groups/add", name="add_group")
   *
   * @Method({"GET", "POST"})
   */
  public function add(Request $request)
  {
      $form = $this->createForm(GroupsType::class);
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {
        $group = $form->getData();
        $em   = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();
        $group_id = $group->getId();
        if ($group_id) {
          $this->addFlash('success', 'Group created successfully');
        } else {
          $this->addFlash('failure', 'Something is wrong');
        }

       return $this->redirectToRoute('groups');
      }//end if

      return $this->redirectToRoute('groups');
  }

  /**
   * Delete a user and its associations
   *
   * @param Request $request Data request
   * @param integer $id      Group Id
   *  
   * @Route("/groups/delete/{id}", name="delete_group")
   *
   * @Method({"DELETE"})
   */
  public function delete(Request $request, int $id)
  {
      $group = $this->getDoctrine()->getRepository(Group::class)->find($id);
      $em    = $this->getDoctrine()->getManager();
      $isUserExist = $this->getDoctrine()->getRepository(Group::class)->findOneByIdJoinedToUser($id);

      if ($isUserExist[1] <= 0) {
          $em->remove($group);
          $em->flush();
          $this->addFlash('failure', 'Group deleted successfully!');
      } else {
          $this->addFlash('failure', 'Group has users!');
      }

      return $this->redirectToRoute('groups');
  }
	
}
