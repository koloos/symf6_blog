<?php

namespace App\Controller;

use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MainController extends AbstractController
{
    #[Route('/', name: 'main')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();

        return $this->render('main/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    #[Route('/articles/{id}', name: 'app_articles_id')]
    public function getArticleById(
        $id,
        ArticleRepository $articleRepository,
        Request $request,
        UserRepository $userRepository,
        ManagerRegistry $managerRegistry
    ) {
        $article = $articleRepository->find($id);

        $commentaireForm = $this->createForm(CommentaireType::class);

        $commentaireForm->handleRequest($request);
        if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
            $commentaire = $commentaireForm->getData();
            $commentaire->setDateCommentaire(new \DateTime());
            $commentaire->setPublie(false);
            $commentaire->setArticle($article);
            $user = $this->getUser();
            $commentaire->setUser($user);

            $entityManager = $managerRegistry->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            $this->redirectToRoute('main');
        }

        return $this->render('main/article.html.twig', [
            'article' => $article,
            'commForm' => $commentaireForm->createView(),
        ]);
    }

    #[Route('/email', name: 'app_email')]
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('noos.noreply@gmail.com')
            ->to('nolwennlaxaguepro@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Valid your register!')
            ->text('Valide ton inscription.')
            ->html('<p>Félicitation ! Tu es bien inscrit sur le blog.</p>');

        $mailer->send($email);

        $this->redirectToRoute('main');
    }
}
