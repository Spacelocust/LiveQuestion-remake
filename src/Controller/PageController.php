<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\Question;
use App\Entity\User;
use App\Form\AddQuestionType;
use App\Repository\LikeRepository;
use App\Repository\QuestionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class PageController extends AbstractController
{

    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var QuestionRepository
     */
    private QuestionRepository $repoQuestion;
    /**
     * @var UserRepository
     */
    private UserRepository $repoUser;
    /**
     * @var LikeRepository
     */
    private LikeRepository $likeRepo;

    public function __construct(QuestionRepository $repoQuestion, Security $security,
    EntityManagerInterface $manager, UserRepository $repoUser, LikeRepository $likeRepo )
    {
        $this->security = $security;
        $this->manager = $manager;
        $this->repoQuestion = $repoQuestion;
        $this->repoUser = $repoUser;
        $this->likeRepo = $likeRepo;
    }

    /**
     * Affichage de l'ensemble des questions
     *
     * @Route("/flux", name="page_flux")
     * @return Response
     */
    public function flux(): Response
    {
        $userLog = $this->security->getUser();
        if(!$userLog){
            return $this->redirectToRoute('login',[], 302);
        }
        $questions = $this->repoQuestion->findAllByDate();

        return $this->render('page/flux.html.twig', [
            'controller_name' => 'Flux de questions',
            'questions' => $questions,
        ]);
    }

    /**
     * Affichage de la question
     *
     * @Route("/flux/{slug}-{id}", name="page_question", requirements={"slug": "[a-z0-9\-]*"})
     * @param Question $question
     * @param string $slug
     * @return Response
     */
    public function questionShow(Question $question ,string $slug): Response
    {
        $questionSlug = $question->getSlug();

        if ($questionSlug !== $slug){

            return $this->redirectToRoute('page_question',[
                'id' => $question->getId(),
                'slug'=> $questionSlug
            ], 301);
        }


        return $this->render('page/question.html.twig', [
            'controller_name' => $slug,
            'question' => $question
        ]);
    }

    /**
     * Formulaire d'ajout d'une question
     *
     * @Route("/add-question", name="add_question")
     * @return Response
     */
    public function add(Request $request): Response
    {
        $userLog = $this->security->getUser();
        $user = $this->repoUser->findByName($userLog->getUsername());

        $timeZone = new \DateTimeZone('Europe/Paris');
        $dateTime = new \DateTime('now',$timeZone);

        $manager = $this->manager;
        $question = new Question();

        $form = $this->createForm(AddQuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setUser($user);
            $question->setDate($dateTime);

            $manager->persist($question);
            $manager->flush();

            return $this->redirectToRoute('page_flux');
        }
        return $this->render('page/add-question.html.twig', [
            'controller_name' => 'Ajout d\'une question',
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'ajouté un j'aime et d'enlever un j'aime
     *
     * @Route("/flux/{id}/like", name="like_question")
     * @param Question $question
     * @return Response
     */
    public function like(Question $question): Response
    {
        $manager = $this->manager;

        $timeZone = new \DateTimeZone('Europe/Paris');
        $dateTime = new \DateTime('now',$timeZone);

        $userLog = $this->security->getUser();
        $user = $this->repoUser->findByName($userLog->getUsername());

        $likeRepo = $this->likeRepo;

        if($question->isLikedByUser($user)){
            $like = $likeRepo->findOneBy([
               'question'=> $question,
               'user'=> $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code'=>200,
                'message'=> 'j\'aime supprimé',
                'likes'=> $likeRepo->count(['question'=>$question])
            ],200);
        }
        $like = new Like();
        $like->setUser($user)
             ->setQuestion($question)
             ->setDate($dateTime);

        $manager->persist($like);
        $manager->flush();

        return $this->json([
            'code'=>200,
            'message'=>'ajout d\'un j\'aime',
            'likes'=>$likeRepo->count(['question'=>$question])
        ],200);
    }
}
