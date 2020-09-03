<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
//use Cocur\Slugify\Slugify;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder){

        $this->encoder = $encoder;

    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("Fr-fr");
        //$slugify = new Slugify();

        //Creer un role(role admin)
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);

        //Creer un user administrateur
        $adminUser = new User();
        $adminUser->setFirstName('SALAMI')
                  ->setLastName('SODIKI OLAWALE')
                  ->setEmail('salamisodikiolawale@gmail.com')
                  ->setHash($this->encoder->encodePassword($adminUser, 'password'))
                  ->setPicture('https://avatar.io/twiter/LiiorC')
                  ->setIntroduction($faker->sentence())
                  ->setDescription('<p>'.join('<p></p>', $faker->paragraphs(1)).'</p>')
                  ->addUserRole($adminRole);

        $manager->persist($adminUser);


        //Gestion des users

        $users = [];
        $genres = ['male', 'female'];

        for($i=1;$i<10;$i++)
        {
            $user = new User;

            $genre = $faker->randomElement($genres);

            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberBetween(1, 99). '.jpg';
            
            if($genre == 'male') 
                $picture = $picture . 'men/' .$pictureId;
            else 
                $picture = $picture . 'women/' .$pictureId;

            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setFirstName($faker->firstname)
                 ->setLastName($faker->lastname)
                 ->setIntroduction($faker->sentence())
                 ->setEmail($faker->email)
                 ->setDescription('<p>'.join('<p></p>', $faker->paragraphs(1)).'</p>')
                 ->setHash($hash)
                 ->setPicture($picture);
            
            $manager->persist($user);
            $users[] = $user;
        }

        //Gestion des annonces 
        for($i=0;$i<30;$i++){
    
            $ad = new Ad();

            $title = $faker->sentence();
            //$slug = $slugify->slugify($title); Elle a ete automatisée dans l'entity Methode : initilizeSlug
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content = '<p>'.join('<p></p>', $faker->paragraphs(5)).'</p>';
            $user = $users[mt_rand(0, count($users) -1)];

            $ad->setTitle($title)
               //->setSlug($slug) Elle a ete automatisée dans l'entity Methode : initilizeSlug
               ->setCoverImage($coverImage)
               ->setIntroduction($introduction)
               ->setContent($content)
               ->setPrice(mt_rand(40, 200))
               ->setRooms(mt_rand(1, 5 ))
               ->setAuthor($user);

               for($j=1; $j<=mt_rand(2, 5); $j++){
                   
                   $image = new Image();
                   $image->setUrl($faker->imageUrl())
                         ->setCaption($faker->sentence())
                         ->setAd($ad);

                    $manager->persist($image);
               }
            $manager->persist($ad);
        }
        
        $manager->flush();
    }
}
