<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class AppFixtures extends Fixture
{

    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasherInterface){
        $this->passwordHasher = $userPasswordHasherInterface;
    }

    
    public function load(ObjectManager $manager): void
     {
        $lila = new User();
        $lila->setEmail("lila@patachou.fr");
        $lila->setDateCreation(new \DateTime());
        $lila->setPseudo("lila");
        $hash = $this->passwordHasher->hashPassword($lila, "lilaflower");
        $lila->setPassword($hash);
        $manager->persist($lila);

        $elsa = new User();
        $elsa->setEmail("elsa@hernandez.fr");
        $elsa->setDateCreation(new \DateTime());
        $elsa->setPseudo("elsa");
        $hash= $this->passwordHasher->hashPassword($elsa, "elsahash");
        $elsa->setPassword($hash);
        $manager->persist($elsa);

         for($i = 1; $i <= 5; $i++){

             $cat = new Categorie();
             $cat->setNom("Nouvelle catégorie n°" .$i);
             $manager->persist($cat);
            
               
             for ($j = 1; $j <= 10; $j++){
                
                 $article = new Article();
                 $article->setTitre( $i."-"."Nouvel article n°"." ".$j);
                 $article->setContenu("Lorem ipsum, dolor sit amet consectetur adipisicing elit. Odio aliquam quos pariatur commodi sunt animi unde, beatae reprehenderit atque doloribus eligendi numquam ipsa molestias earum quasi tempore. Vel, quos eius.
                 ");
                 $article->setDatePublication(new \DateTime());
                 $article->setImageSrc("images/toto.jpg");
                 $article->setNombreVues(0);
                 $article->setCategorie($cat);
                 $article->setUser($lila);
                 $manager->persist($article);
    
                 for ($k =1; $k <= 5; $k++){
                    
                     $comm = new Commentaire();
                     $comm->setDateCommentaire(new \DateTime());
                     $comm->setContenuCommentaire("super article !" .$k);
                     $comm->setPublie(false);
                     $comm->setArticle($article);
                     $comm->setUser($elsa);
                     $manager->persist($comm);
                 }
             }
         }


    //     $comm2 = new Commentaire();
    //     $comm2->setDateCommentaire(new \DateTime());
    //     $comm2->setContenuCommentaire("article super intéressant !");
    //     $comm2->setPublie(false);
    //     $comm2->setArticle($article);
    //     $manager->persist($comm2);

         $manager->flush();
     }
}
