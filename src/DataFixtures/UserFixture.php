<?php

namespace App\DataFixtures;

use App\Entity\Usuario;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    
    private $encoder;
    
    public function __construct(UserPasswordEncoderInterface $encoder) 
    {
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager)
    {
        $user = new Usuario();
        $user->setApodo('admin');
        $user->setNombre('administrador');
        $user->setApellidos('admin sistem');
        $user->setPass(
                $this->encoder->encodePassword($user, '0000')
        );
        
        $user->setEmail('admin@webmaster.com');
        
        $manager->persist($user);

        $manager->flush();
    }
}
