<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Posts;
use App\Entity\User;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $postsData = $em->getRepository(Posts::class)->findBY(['is_public' => true]);

        return $this->render('main/index.html.twig', [
            'postsData' => $postsData,
        ]);
    }

    /**
     * @Route("/user_panel", name="user_panel")
     */
    public function userPanel()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('main/user_panel.html.twig', []);
    }


    /**
     * @Route("/post_details/{id}", name="post_details")
     */
    public function postDetails(int $id)
    {

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Posts::class)->findOneBY(['id' => $id]);

        return $this->render('main/post_details.html.twig', [
            'post' => $post,
        ]);
    }

    /**
     * @Route("/user_posts/{username}", name="user_posts")
     */
    public function userPostst($username)
    {
        $em = $this->getDoctrine()->getManager();
        $userPosts = $em->getRepository(Posts::class)->findBY(['user_id' => $this->getUser()]);
        $userData = $em->getRepository(User::class)->findOneBY(['id' => $this->getUser()]);

        return $this->render('main/user_posts.html.twig', [
            'userPosts' => $userPosts,
            'userData' => $userData,
        ]);
    }
}
