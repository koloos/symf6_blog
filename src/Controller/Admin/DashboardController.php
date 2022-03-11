<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use Symfony\Component\HttpFoundation\Response;
use App\Controller\Admin\ArticleCrudController;
use Symfony\Component\Routing\Annotation\Route;
use App\Controller\Admin\CategorieCrudController;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symf6 Blog');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linkToDashboard('Accueil', 'fa fa-home'),

            MenuItem::section('Blog'),
            MenuItem::linkToCrud('Catégories', 'fa fa-tags', Categorie::class),
            MenuItem::linkToCrud('Articles', 'fa fa-file-text', Article::class),

            MenuItem::section('Utilisateurs'),
            MenuItem::linkToCrud('Commentaires', 'fa fa-comment', Commentaire::class),
            MenuItem::linkToCrud('Users', 'fa fa-user', User::class),

        ];
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
