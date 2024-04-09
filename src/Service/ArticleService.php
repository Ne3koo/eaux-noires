<?php

namespace App\Service;

use App\Entity\Category;
use App\Repository\ArticleRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ArticleService
{
    public function __construct
    (
        private RequestStack $requestStack,
        private ArticleRepository $articleRepository,
        private PaginatorInterface $paginatorInterface,
        private OptionService $optionService
    )
    {
        
    }

    public function getPaginatedArticles(?Category $category = null): PaginationInterface
    {
        $request = $this->requestStack->getMainRequest();

        $page = $request->query->getInt('page', 1);
        $limit = $this->optionService->getValue('blog_articles_limit');

        $articleQuery = $this->articleRepository->findForPagination($category);

        return $this->paginatorInterface->paginate($articleQuery, $page, $limit);
    }
}