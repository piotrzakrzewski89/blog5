<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Form\PostsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="index_post")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $postsData = $em->getRepository(Posts::class)->findAll();

        return $this->render('post/index.html.twig', [
            'postsData' => $postsData,
        ]);
    }

    /**
     * @Route("/new_post", name="add_new_post")
     * @param Request $request
     * $return \Symfony\Component\HttpFoundation\Response
     */
    public function newPost(Request $request)
    {
        $newPosts = new Posts();
        $form = $this->createForm(PostsType::class, $newPosts);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();


            try {
                $this->addFlash('success', 'Dodano Projekt');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
            }

            return $this->redirectToRoute('index_post');
        }

        return $this->render('post/new.html.twig', [
            'postsForm' => $form->createView(),
        ]);
    }
}
