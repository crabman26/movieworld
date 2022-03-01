<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Entity\Voted;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class VotedController extends AbstractController
{
    
    
    
    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    /**
     * @Route("/voted", name="voted")
     */
    public function index(): Response
    {
        return $this->render('voted/index.html.twig', [
            'controller_name' => 'VotedController',
        ]);
    }

    //Display votes by user.
    /**
     * @Route("/display", name="voted_display", methods={"GET"})
     */
    public function display(Request $request,EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response {
        $jsonData = array();  
        $idx = 0;
        $user = $this->security->getUser();
        $loggedemail = $user->getemail();
        $entityManager = $doctrine->getManager();
        $votes = $entityManager->getRepository(Voted::class)
        ->findBy(
            ['User' => $loggedemail]);
        
        foreach($votes as $vote) {  
            $temp = array(
                'Title' => $vote->getMovie(),
                'Vote' => $vote->getVote());
            $jsonData[$idx++] = $temp;
        }    
        return new JsonResponse($jsonData); 
    }

    //Change vote according to username.
    /**
     * @Route("/changeVote", name="voted_changevote", methods={"POST"})
    */

    public function changeVote(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response {
        $user = $this->security->getUser();
        $loggedemail = $user->getemail();
        $entityManager = $doctrine->getManager();
        $title = $request->get('Title');
        $votes = $entityManager->getRepository(Voted::class)
        ->findBy(
            ['User' => $loggedemail,
            'Movie' => $title]);
        $movies = $entityManager->getRepository(Movie::class)
        ->findBy(
            ['Title' => $title]);

        if ($votes) {
            foreach($votes as $vote) {  
                $Vote = $vote->getVote();
                if ($Vote == 'Like') {
                    $vote->setVote('Hate');
                    $entityManager->flush();
                    foreach ($movies as $movie) {
                        $hates = $movie->getHates();
                        $movie->setHates($hates + 1);
                        $entityManager->flush();
                    }     
                } else {
                    $vote->setVote('Like');
                    $entityManager->flush();
                    foreach ($movies as $movie) {
                        $likes = $movie->getLikes();
                        $movie->setLikes($likes + 1);
                        $entityManager->flush();
                    }        
                }       
            }
            return new JsonResponse('Vote Changed'); 
        } else {
            return new JsonResponse('Vote not changed'); 
        }       
    }

    //Retract user vote.
    /**
     * @Route("/retractVote", name="voted_retractvote", methods={"POST"})
    */

    public function retractVote(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine) : Response {
        $user = $this->security->getUser();
        $loggedemail = $user->getemail();
        $entityManager = $doctrine->getManager();
        $title = $request->get('Title');
        $votes = $entityManager->getRepository(Voted::class)
        ->findBy(
            ['User' => $loggedemail,
            'Movie' => $title]);
        $movies = $entityManager->getRepository(Movie::class)
        ->findBy(
            ['Title' => $title]);
        
        foreach ($votes as $vote) {
            $Vote = $vote->getVote();
            if ($Vote == 'Like') {
                foreach ($movies as $movie) {
                    $likes = $movie->getLikes();
                    $movie->setLikes($likes - 1);
                    $entityManager->flush();
                }  
            } else {
                foreach ($movies as $movie) {
                    $hates = $movie->getHates();
                    $movie->setHates($hates - 1);
                    $entityManager->flush();
                }            
            }     
        }
        $entityManager->remove($vote);
        $entityManager->flush();
        
        return new JsonResponse('Vote deleted');
    }
}
