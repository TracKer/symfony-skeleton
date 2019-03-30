<?php

namespace App\DataFixtures;

use App\Entity\Security\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AppFixtures extends Fixture implements ContainerAwareInterface {
  /**
   * @var ContainerInterface
   */
  private $container;

  /**
   * {@inheritDoc}
   */
  public function setContainer(ContainerInterface $container = null) {
    $this->container = $container;
  }

  /**
   * {@inheritDoc}
   */
  public function load(ObjectManager $manager) {
    // $product = new Product();
    // $manager->persist($product);
    $user = (new User())
      ->setUsername('admin')
      ->setEmail('andrey.rudenko@gmail.com')
      ->addRole('ROLE_SUPER_ADMIN')
      ->setEnabled(true);

    $encoder = $this->container->get('security.password_encoder');
    $password = $encoder->encodePassword($user, 'admin');
    $user->setPassword($password);

//    $manager = $this->container->get('doctrine.orm.entity_manager');
    $manager->persist($user);

    $manager->flush();
  }
}
