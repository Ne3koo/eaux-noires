<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\User;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @method User getUser()
 */
class CommentController extends AbstractController
{

    public function __construct(
        private ArticleRepository $articleRepository,
        private CommentRepository $commentRepository,
        private UserRepository $userRepository
    )
    {
    }

    #[Route('/ajax/comment', name: 'comment_add', methods: ['POST'])]
    public function add(Request $request, ArticleRepository $articleRepository, CommentRepository $commentRepository, EntityManagerInterface $em): Response
    {
        $commentData = $request->request->all('comment');
    
        if (!$this->isCsrfTokenValid('add-comment', $commentData['_token'])) {
            return $this->json([
                'code' => 'INVALID_CSRF_TOKEN'
            ], Response::HTTP_BAD_REQUEST);
        }
    
        $article = $articleRepository->findOneBy(['id' => $commentData['article']]);
    
        if (!$article) {
            return $this->json([
                'code' => 'ARTICLE_NOT_FOUND'
            ], Response::HTTP_BAD_REQUEST);
        }
    
        $user = $this->getUser();
    
        if (!$user) {
            return $this->json([
                'code' => 'USER_NOT_AUTHENTICATED'
            ], Response::HTTP_BAD_REQUEST);
        }
    
        $note = isset($commentData['note']) ? (int)$commentData['note'] : null;
    
        if ($note !== null && ($note < 0 || $note > 5)) {
            return $this->json([
                'code' => 'INVALID_NOTE_VALUE',
                'message' => 'The note must be between 0 and 5.'
            ], Response::HTTP_BAD_REQUEST);
        }
    
        // Check if the user has already submitted a similar comment recently
        $recentComments = $commentRepository->findBy(['article' => $article, 'user' => $user], ['createdAt' => 'DESC'], 1);
    
        if ($recentComments && (new \DateTime())->getTimestamp() - $recentComments[0]->getCreatedAt()->getTimestamp() < 10) {
            return $this->json([
                'code' => 'DUPLICATE_COMMENT',
                'message' => 'You have already submitted a similar comment recently.'
            ], Response::HTTP_BAD_REQUEST);
        }
    
        $comment = new Comment($article);
        $comment->setContent($commentData['content']);
        $comment->setUser($user);
        $comment->setCreatedAt(new \DateTime());
    
        if ($note !== null) {
            $comment->setNote($note);
        }
    
        $em->persist($comment);
        $em->flush();
    
        $comments = $commentRepository->findBy(['article' => $article]);
        $html = $this->renderView('comment/index.html.twig', [
            'comments' => $comments
        ]);
    
        return $this->json([
            'code' => 'COMMENT_ADD_SUCCESS',
            'success' => true,
            'numberOfComments' => count($comments),
            'commentsHtml' => $html
        ]);
    }
    
}
