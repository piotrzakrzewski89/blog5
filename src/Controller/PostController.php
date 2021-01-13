<?php

namespace App\Controller;

use App\Entity\Posts;
use App\Entity\Ratings;
use App\Form\PostsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\ImagesUploadService;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="user_posts_auth")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $em = $this->getDoctrine()->getManager();
        $postsData = $em->getRepository(Posts::class)->findBY(['user' => $this->getUser()]);

        return $this->render('post/user_posts_auth.html.twig', [
            'postsData' => $postsData,

        ]);
    }

    /**
     * @Route("/new_post", name="add_new_post")
     * @param Request $request
     * $return \Symfony\Component\HttpFoundation\Response
     */
    public function newPost(Request $request,  ImagesUploadService $imageUploadService)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $newPosts = new Posts();
        $newRatings = new Ratings();
        $form = $this->createForm(PostsType::class, $newPosts);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $newFileNamePhoto = $form->get('photo_path')->getData();

            if ($this->getUser()) {
                try {
                    $catalogPath = 'download/' . $this->getUser()->getId() . '/';
                    $newFileNamePhoto = $imageUploadService->uploadNewImage($newFileNamePhoto, $catalogPath);
                    $newPosts->setPhotoPath($newFileNamePhoto);
                    $newPosts->setUser($this->getUser());
                    $newPosts->setIsPublic(0);
                    $newPosts->setCreatedAt(new \DateTime());
                    $newRatings->setPositive(0);
                    $newRatings->setNegative(0);
                    $newRatings->setPost($newPosts);
                    $em->persist($newRatings);
                    $em->persist($newPosts);
                    $em->flush();
                    $this->addFlash('success', 'Dodano Post');
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
                }

                return $this->redirectToRoute('index_post');
            }
        }

        return $this->render('post/new.html.twig', [
            'postsForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/post/edit/{id}", name="post_edit")
     * @param Request $request
     * $return \Symfony\Component\HttpFoundation\Response
     */
    public function editPost(Request $request, $id, ImagesUploadService $imageUploadService)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository(Posts::class)->find($id);
        $form = $this->createForm(PostsType::class, $post);
        $oldFilePath = $post->getPhotoPath();
        $userId = $this->getUser()->getId();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $pictureFileName = $form->get('photo_path')->getData();
            try {
                $catalogPath = 'download/' . $this->getUser()->getId() . '/';
                if ($pictureFileName != null) {
                    $newFileNamePhoto = $imageUploadService->uploadEditImage($pictureFileName, $oldFilePath, $catalogPath);
                    $post->setPhotoPath($newFileNamePhoto);
                }
                $post->setModificatedAt(new \DateTime());
                $em->persist($post);
                $em->flush();
                $this->addFlash('success', 'Zedytowano post');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
            }

            return $this->redirectToRoute('user_posts_auth');
        }
        return $this->render('post/new.html.twig', [
            'postsForm' => $form->createView(),
            'userId' => $userId,
        ]);
    }

    /**
     * @Route("/post/delete/{id}", name="post_delete")
     * @param Request $request
     * $return \Symfony\Component\HttpFoundation\Response
     */
    public function deletePost($id)
    {
        $filesystem = new Filesystem();
        try {
            $catalogPath = 'download/' . $this->getUser()->getId() . '/';
            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository(Posts::class)->find($id);
            $filesystem->remove([$catalogPath . $post->getPhotoPath()]);
            $em->remove($post);
            $em->flush();
            $this->addFlash('success', 'Usunięto post');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Wystąpił nieoczekiwany błąd podczas usuwania');
        }
        return $this->redirectToRoute('user_posts_auth');
    }

    /**
     * @Route("/post/set_visibility/{id}{visibility}", name="post_set_visibility")
     */
    public function makeVisible($id, $visibility)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $post = $em->getRepository(Posts::class)->find($id);
            $post->setModificatedAt(new \DateTime());
            $post->setIsPublic($visibility);
            $em->persist($post);
            $em->flush();
            $this->addFlash('success', 'Zaktulizowano widoczność');
        } catch (\Exception $e) {
            $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
        }
        return $this->redirectToRoute('user_posts_auth');
    }
}
