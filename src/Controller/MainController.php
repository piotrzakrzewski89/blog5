<?php

namespace App\Controller;

use App\Entity\Comments;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Posts;
use App\Entity\User;
use App\Entity\Ratings;
use App\Form\CommentsType;
use App\Service\SendEmialToUserService;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Service\DeleteEntitiesService;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $postsData = $em->getRepository(Posts::class)->findBY(['is_public' => true]);
        $ratingsData = $em->getRepository(Ratings::class)->findOneBy(['post' => $postsData]);
        $avgRatings = ($ratingsData->getPositive() - $ratingsData->getNegative()) / 2;

        return $this->render('main/index.html.twig', [
            'postsData' => $postsData,
            'avgRatings' => $avgRatings,
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
    public function postDetails(int $id, Request $request)
    {
        $newComment = new Comments();

        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Posts::class)->findOneBY(['id' => $id]);
        $ratingsData = $em->getRepository(Ratings::class)->findOneBY(['post' => $post]);
        $commentsData = $em->getRepository(Comments::class)->findBY(['post' => $post]);

        $form = $this->createForm(CommentsType::class, $newComment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em = $this->getDoctrine()->getManager();
                $newComment->setPostedAt(new \DateTime());
                $newComment->setPost($post);
                $newComment->setUser($this->getUser());
                $em->persist($newComment);
                $em->flush();
                $this->addFlash('success', 'Dodano komentarz');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
            }

            return $this->redirectToRoute('post_details', ['id' => $post->getId()]);
        }

        return $this->render('main/post_details.html.twig', [
            'post' => $post,
            'ratingsData' => $ratingsData,
            'commentForm' => $form->createView(),
            'commentsData' => $commentsData,
        ]);
    }

    /**
     * @Route("/user_posts/{username}", name="user_posts")
     */
    public function userPosts($username)
    {
        $em = $this->getDoctrine()->getManager();
        $userData = $em->getRepository(User::class)->findOneBY(['username' => $username]);
        $userPosts = $em->getRepository(Posts::class)->findBY(['user' => $userData->getId()]);

        return $this->render('main/user_posts.html.twig', [
            'userPosts' => $userPosts,
            'userData' => $userData,
        ]);
    }

    /**
     * @Route("/user_comments/{username}", name="user_comments")
     */
    public function userComments($username)
    {
        $em = $this->getDoctrine()->getManager();
        $userData = $em->getRepository(User::class)->findOneBY(['username' => $username]);
        $userComments = $em->getRepository(Comments::class)->findBY(['user' => $userData->getId()]);

        return $this->render('comments/user_comments.html.twig', [
            'userComments' => $userComments,
            'userData' => $userData,
        ]);
    }

    /**
     * @Route("/user_panel/send_email/{user}", name="app_send_mail_to_user") 
     */
    public function sendEmailToUser($user, SendEmialToUserService $SendEmialToUserService)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $SendEmialToUserService->sendMailToUser($user, $em);
            $this->addFlash('success', 'Wysłano ponownie link aktywacyjny');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
        }
        return $this->redirectToRoute('user_panel');
    }

    /**
     * @Route("/user_panel/remove_account/{user}", name="remove_account") 
     */
    public function removeUserAccount($user, DeleteEntitiesService $deleteEntitiesService)
    {
        $filesystem = new Filesystem();
        $catalogPath = 'download/' . $this->getUser()->getId() . '/';
        $em = $this->getDoctrine()->getManager();
        try {
            $user = $em->getRepository(User::class)->find($user);
            $posts = $em->getRepository(Posts::class)->findBy(['user' => $user]);
            $comments = $em->getRepository(Comments::class)->findBy(['user' => $user]);
            if ($this->getUser()->getId() == $user->getId()) {
                $session = $this->get('session');
                $session = new Session();
                $session->invalidate();
            }
            foreach ($posts as $post) {
                $filesystem->remove([$catalogPath . $post->getPhotoPath()]);
            }
            $deleteEntitiesService->deleteEntities($em, $comments);
            $deleteEntitiesService->deleteEntities($em, $posts);

            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Usunięto konto z systemu');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
        }
        return $this->redirectToRoute('app_login');
    }
}
