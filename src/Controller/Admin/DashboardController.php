<?php

namespace App\Controller\Admin;

use App\Controller\ArticleController;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Menu;
use App\Entity\Comment;
use App\Entity\Media;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator)
    {
        
    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)
        ->generateUrl();


        return $this->redirect(($url));
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Capitaine des Eaux Noires');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Accueil du site', 'fa fa-undo', 'app_home');

        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        if($this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::subMenu('Menus', 'fas-fa-list')->setSubItems([
                MenuItem::linkToCrud('Pages', 'fas fa-file', Menu::class)
                ->setQueryParameter('subMenuIndex', '0'),
                MenuItem::linkToCrud('Articles', 'fas fa-newspaper', Menu::class)
                ->setQueryParameter('subMenuIndex', '1'),
                MenuItem::linkToCrud('Liens personnalisés', 'fas fa-link', Menu::class)
                ->setQueryParameter('subMenuIndex', '2'),
                MenuItem::linkToCrud('Catégories', 'fab fa-delicious', Menu::class)
                ->setQueryParameter('subMenuIndex', '3')
            ]);
        }
        if($this->isGranted('ROLE_AUTHOR') or $this->isGranted('ROLE_ADMIN')) {
            yield MenuItem::subMenu('Articles', 'fas fa-newspaper')->setSubItems([
                MenuItem::linkToCrud('Tous les articles', 'fas fa-newpaper', Article::class),
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
                MenuItem::linkToCrud('Catégories', 'fas fa-list', Category::class)
            ]);

            yield MenuItem::subMenu('Médias', 'fas fa-photo-video')->setSubItems([
                MenuItem::linkToCrud('Médiathèque', 'fas fa-photo-video', Media::class),
                MenuItem::linkToCrud('Ajouter', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW)
            ]);
            }

        if($this->isGranted('ROLE_ADMIN')) {
        yield MenuItem::linkToCrud('Commentaires', 'fas fa-comment', Comment::class);

        yield MenuItem::subMenu('Comptes', 'fas fa-user')->setSubItems([
            MenuItem::linkToCrud('Tous les comptes', 'fas fa-user-friends', User::class),
            MenuItem::linkToCrud('Ajouter', 'fas fa-plus', User::class)->setAction(Crud::PAGE_NEW)
        ]);
        }

    }
}
