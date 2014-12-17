<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadAdminData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    private $container;

    public function load(ObjectManager $manager)
    {
        $manipulator = $this->container->get('fos_user.user_manager');

        /** @var $admin User example superadmin */
        $admin = $manipulator->createUser();
        $admin->setUsername('admin');
        $admin->setEmail('exadmin@email.com');
        $admin->setPlainPassword('password');
        $admin->setEnabled(true);
        $admin->setSuperAdmin(true);
        $manipulator->updateUser($admin);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }
}
