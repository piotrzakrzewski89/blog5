<?php

namespace App\Controller;

use App\Entity\Posts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Ratings;

class VotesController extends AbstractController
{
    /**
     * @Route("/post/vote/{id}{type}{value}", name="post_vote")
     */
    public function vote($id, $type, $value)
    {
        $newRating = new Ratings();
        $em = $this->getDoctrine()->getManager();
        $checkUserVote = $em->getRepository(Ratings::class)->findBy(['user' =>  $this->getUser(), 'post' => $id]);

        if (empty($checkUserVote)) {
            try {

                $post = $em->getRepository(Posts::class)->findOneBy(['id' => $id]);
                if ($type == 'p') {
                    $newRating->setPositive($value);
                    $newRating->setNegative(0);
                    $newRating->setPost($post);
                    $newRating->setUser($this->getUser());
                } elseif ($type == 'n') {
                    $newRating->setPositive(0);
                    $newRating->setNegative($value);
                    $newRating->setPost($post);
                    $newRating->setUser($this->getUser());
                }
                $em->persist($newRating);
                $em->flush();

                $this->addFlash('success', 'Zagłosowano');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
            }
        } else {
            $this->addFlash('error', 'Oddano już głos w tym poście !');
        }
        return $this->redirectToRoute('post_details', ['id' => $id]);
    }
}
