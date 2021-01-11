<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Ratings;

class VotesController extends AbstractController
{
    /**
     * @Route("/post/vote/{id}{type}{value}", name="post_vote")
     */
    public function vote($id, $type, $value)
    {
        try {
            $em = $this->getDoctrine()->getManager();
            $rating = $em->getRepository(Ratings::class)->findOneBy(['post' => $id]);
            $user = $em->getRepository(User::class)->findOneBy(['id' =>  $this->getUser()]);
            if ($type == 'p') {
                $rating->setPositive($rating->getPositive() + $value);
            } elseif ($type == 'n') {
                $rating->setNegative($rating->getNegative() + $value);
            }
            if ($user->getHasVoted() == true) {
                $this->addFlash('error', 'Zagłosowano już');
            } else {
                $user->setHasVoted(true);
                $em->persist($user);
                $em->persist($rating);
                $em->flush();
                $this->addFlash('success', 'Zagłosowano');
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Wystąpił nieoczekiwany błąd');
        }
        return $this->redirectToRoute('post_details', ['id' => $id]);
    }
}
