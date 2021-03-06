<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Form\CommentsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class CommentsController extends AbstractController
{

    /**
     * @Route("/user_comments/edit/{id}", name="comment_edit")
     * @param Request $request
     * $return \Symfony\Component\HttpFoundation\Response
     */
    public function editComment(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository(Comments::class)->find($id);
        $form = $this->createForm(CommentsType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($comment);
                $em->flush();
                $this->addFlash('success', 'Zedytowano komentarz');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
            }

            return $this->redirectToRoute('user_details_auth');
        }
        return $this->render('comments/user_comments_edit.html.twig', [
            'commentForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user_comments/delete/{id}", name="comment_delete")
     * @param Request $request
     * $return \Symfony\Component\HttpFoundation\Response
     */
    public function deletePost($id)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $comment = $em->getRepository(Comments::class)->find($id);
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Usunięto komentarz');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Wystąpił nieoczekiwany błąd podczas usuwania');
        }
        return $this->redirectToRoute('user_details_auth');
    }
}
