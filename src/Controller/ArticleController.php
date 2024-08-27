<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\Type\CommentType;
use App\Form\ArticleType;
use App\Service\ArticleService;
use App\Service\CommentService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    #[Route('/article/{slug}', name: 'article_show')]
    public function show(?Article $article, CommentService $commentService): Response
    {
        if (!$article)
        {
            return $this->redirectToRoute('app_home');
        }

        $comment = new comment($article);

        $commentForm = $this->createForm(CommentType::class, $comment);

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'comments' => $commentService->getPaginatedComments($article),
            'commentForm' => $commentForm
        ]);
    }
    #[Route('/articles', name: 'articles')]
    public function index(?Article $article, ManagerRegistry $mr, ArticleService $articleService): Response
    {
        $articles = $mr->getRepository(Article::class)->findAll();

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'articles' => $articleService->getPaginatedArticles($article)
        ]);
    }

    #[Route('/articles/new', name: 'article_new')]
    public function new(Request $request, ManagerRegistry $mr, SluggerInterface $slugger): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                if (!$article->getSlug()) {
                    $slug = $slugger->slug($article->getTitle())->lower();
                    $article->setSlug($slug);
                }
    
                if (!$article->getCreatedAt()) {
                    $article->setCreatedAt(new \DateTime());
                }
                $article->setUpdatedAt(new \DateTime());
    
                try {
                    $entityManager = $mr->getManager();
                    $entityManager->persist($article);
                    $entityManager->flush();
                    
                    return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
                } catch (\Exception $e) {
                    $this->addFlash('error', 'Une erreur est survenue : ' . $e->getMessage());
                }
            } else {
                dump($form->getErrors(true, false));
            }
        }
    
        return $this->render('article/new.html.twig', [
            'articleForm' => $form->createView(),
        ]);
    }
    
    #[Route('/article/{slug}/edit', name: 'article_edit')]
    public function edit(Request $request, ?Article $article, ManagerRegistry $mr): Response
    {
        if (!$article) {
            return $this->redirectToRoute('app_home');
        }
        $article->setContent(strip_tags($article->getContent()));

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Generate slug from title
            $slug = $this->slugify($article->getTitle());
            $article->setSlug($slug);
            // Set updatedAt if modified
            $article->setUpdatedAt(new \DateTime());

            $entityManager = $mr->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('article_show', ['slug' => $article->getSlug()]);
        }

        return $this->render('article/edit.html.twig', [
            'article' => $article,
            'articleForm' => $form->createView(),
        ]);
    }
    #[Route('/article/{slug}/delete', name: 'article_delete')]
    public function delete(Request $request, ?Article $article, ManagerRegistry $mr): Response
    {
        if (!$article) {
            return $this->redirectToRoute('app_home');
        }

        // Vérification du token CSRF pour éviter les attaques CSRF
        $token = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete-article'.$article->getId(), $token)) {
            $entityManager = $mr->getManager();
            $entityManager->remove($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a été supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide. La suppression n\'a pas abouti.');
        }

        return $this->redirectToRoute('articles');
    }
    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, '-');

        $text = preg_replace('~-+~', '-', $text);

        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
