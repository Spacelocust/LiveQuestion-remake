<?php

namespace App\Controller\Admin;

use App\Entity\Question;
use App\Repository\LikeRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminQuestionController extends AbstractController
{
    /**
     * @var QuestionRepository
     */
    private QuestionRepository $repo;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var LikeRepository
     */
    private LikeRepository $repoLike;

    public function __construct(QuestionRepository $repo, EntityManagerInterface $manager, LikeRepository $repoLike)
    {
        $this->repo = $repo;
        $this->manager = $manager;
        $this->repoLike = $repoLike;
    }


    /**
     * Affichage de l'ensemble des questions Administration
     *
     * @Route("/admin/gestion-question", name="admin_question_show")
     */
    public function index(): Response
    {
        $questions = $this->repo->findAllByDate();
        return $this->render('admin/gestion-question/index.html.twig', [
            'questions' => $questions
        ]);
    }

    /**
     * Formulaire de suppression d'une question Administration
     *
     * @Route("/admin/gestion-question/{id}", name="admin_question_delete", methods="DELETE")
     */
    public function delete(Question $question, Request $request): Response
    {
        if($this->isCsrfTokenValid('delete'.$question->getId(), $request->get('_token'))){
            $like = $this->repoLike;
            $like->deleteLink($question);
            $this->manager->remove($question);
            $this->manager->flush();
        }
        return $this->redirectToRoute('admin_question_show');
    }

}
