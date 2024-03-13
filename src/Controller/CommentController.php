<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommentController extends AbstractController
{
    #[Route('/ajax/comment', name: 'comment_add')]
    public function add(Request $request, ArticleRepository $articleRepository, CommentRepository $commentRepository, EntityManagerInterface $em, UserRepository $userRepository): Response
    {
        $commentData = $request->request->all('comment');

        if(!$this->isCsrfTokenValid('add-comment', $commentData['_token'])) {
            return $this->json([
                'code' => 'INVALID_CSRF_TOKEN'
            ], Response::HTTP_BAD_REQUEST);
        }

        $article = $articleRepository->findOneBy(['id' => $commentData['article']]);

        if(!$article) {
            return $this->json([
                'code' => 'ARTICLE_NOT_FOUND'
            ], Response::HTTP_BAD_REQUEST);
        }

        $comment = new Comment($article);
        $comment->setContent($commentData['content']);
        $comment->setUser($userRepository->findOneBy(['id' => 1]));
        $comment->setCreatedAt(new DateTime());
        // dd($comment);

        $em->persist($comment);
        $em->flush();

        $html = $this->renderView('comment/index.html.twig', [
            'comment' => $comment
        ]);

        return $this->json([
            'code' => 'COMMENT_ADD_SUCCESS',
            'message' => $html,
            'numberOfComments' => $commentRepository->count(['article' => $article])
        ]);

        //dd($commentData);
    }
}
