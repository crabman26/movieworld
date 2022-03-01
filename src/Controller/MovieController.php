<?php

namespace App\Controller;

use App\Controller\VotedController;
use App\Entity\Movie;
use App\Entity\Voted;
use App\Form\MovieType;
use App\Form\MovieVoteType;
use App\Repository\MovieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    
    public function __construct(Security $security)
    {
       $this->security = $security;
    }
    
    
    /**
     * @Route("/", name="movie_index", methods={"GET"})
     */
    public function index(MovieRepository $movieRepository): Response
    {
        return $this->render('movie/index.html.twig', [
            'movies' => $movieRepository->findAll(),
        ]);
    }

    //Add new movie to database
    /**
     * @Route("/new", name="movie_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('movie/new.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }

    

    //Edit movie in database.
    /**
     * @Route("/{id}/edit", name="movie_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Movie $movie, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $user = $this->security->getUser();
        $loggedemail = $user->getemail();
        $entityManager = $doctrine->getManager();
        $id = $request->get('id');
        $movie = $entityManager->getRepository(Movie::class)->find($id);
        $email = $movie->getNameOfTheUser();
        $update = TRUE;

        if ($loggedemail != $email) {
            $form = $this->createForm(MovieType::class, $movie);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager->flush();

                return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
            }

            return $this->renderForm('movie/edit.html.twig', [
                'movie' => $movie,
                'form' => $form,
                'update' => $update,
            ]);
        } else {
            $update = FALSE;
            return $this->render('movie/edit.html.twig',[
                'update' => $update
            ]);
        }
    }

    //Delete movie from database.
    /**
     * @Route("/{id}", name="movie_delete", methods={"POST"})
     */
    public function delete(Request $request, Movie $movie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$movie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($movie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('movie_index', [], Response::HTTP_SEE_OTHER);
    }

    //Movie voting
    /**
     * @Route("/{id}/vote", name="movie_vote", methods={"GET", "POST"})
     */
    public function vote(Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine, int $id): Response
    {
        $user = $this->security->getUser();
        $loggedemail = $user->getemail();
        $entityManager = $doctrine->getManager();
        $movie = $entityManager->getRepository(Movie::class)->find($id);
        $email = $movie->getNameOfTheUser();
        $title = $movie->getTitle();
        $vote = TRUE;

        if ($loggedemail != $email) {
            
            $data = $request->get('Opinion');
            $voted = new Voted();
            
            if ($data == 'Like') { 
                $likes = $movie->getLikes();
                $movie->setLikes($likes + 1);
                $entityManager->flush();
                $voted->setUser($loggedemail);
                $voted->setMovie($title);
                $voted->setVote('Like');
            } else {
                $hates = $movie->getHates();
                $movie->setHates($hates + 1);
                $entityManager->flush();
                $voted->setUser($loggedemail);
                $voted->setMovie($title);
                $voted->setVote('Hate');
            } 
            $entityManager->persist($voted);
            $entityManager->flush();
            return $this->render('movie/vote.html.twig',[
                'vote' => $vote,
            ]);
        } else {
            $vote = FALSE;
            return $this->render('movie/vote.html.twig',[
                'vote' => $vote,
            ]);
        }       
    }

    //Sort movies by likes,hates and date.
    /** 
     * @Route("/sort/", name="movie_sort", methods={"POST"})
     */ 
    public function sort(Request $request,EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response {
        $jsonData = array();  
        $idx = 0;
        if ($request->get('Value') == 'Dates') {
            $value = 'Date_of_publication';
        } else {
            $value = $request->get('Value');
        }
        
        $entityManager = $doctrine->getManager();
        
        $movies = $entityManager->getRepository(Movie::class)
        ->createQueryBuilder('e')
        ->addOrderBy('e.'.$value, 'ASC')
        ->getQuery()
        ->execute();
        
        foreach($movies as $movie) {  
            $temp = array(
                'title' => $movie->getTitle(),  
                'description' => $movie->getDescription(),
                'user' => $movie->getNameOfTheUser(),
                'date' => $movie->getDateOfPublication(),
                'likes' => $movie->getLikes(),
                'hates' => $movie->getHates(),  
            );   
            $jsonData[$idx++] = $temp;
        } 

        return new JsonResponse($jsonData); 
    }

    //Display list of movies by username.
    /** 
     * @Route("/list/{user}", name="movie_user", methods={"GET"})
     */ 

     public function user(Request $request,EntityManagerInterface $entityManager, ManagerRegistry $doctrine,string $user):Response {
        $entityManager = $doctrine->getManager();
        $movie = $entityManager->getRepository(Movie::class)->findBy(
            ['Name_of_the_user' => $user]);
       
        return $this->render('movie/user.html.twig',[
            'movies' => $movie
        ]);
     }
}
