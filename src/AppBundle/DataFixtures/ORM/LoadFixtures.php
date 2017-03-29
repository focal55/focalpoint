<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Event;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AccountBundle\Entity\Account;
use AccountBundle\Entity\AccountTeam;
use UserBundle\Entity\User;

class LoadFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $users = [];

        // Create admin user.
        $user = new User();
        $user->setActive(true);
        $user->setEmail('admin@example.com');
        $user->setFirstName('Joe');
        $user->setLastName('Ybarra');
        $user->setPlainPassword('test');
        $user->addRole('ROLE_ADMIN');
        $manager->persist($user);
        $manager->flush();
        $users['admin'] = $user;

        // Focal55
        $account = new Account();
        $account->setActive(true);
        $account->setName('Focal55 Inc.');
        $account->getAddress()->setStreetNumber('32');
        $account->getAddress()->setStreetName('Haven Rd');
        $account->getAddress()->setLocality('South Portland');
        $account->getAddress()->setAdminLevel1Code('ME');
        $account->getAddress()->setPostalCode('04106');
        $account->getAddress()->setCountryCode('US');
        $account->getMailing()->setStreetName('PO BOX 648');
        $account->getMailing()->setLocality('Southborough');
        $account->getMailing()->setAdminLevel1Code('MA');
        $account->getMailing()->setPostalCode('01772');
        $account->getMailing()->setCountryCode('US');
        $manager->persist($account);
        $manager->flush();
        $accounts['focal55'] = $account;

        $team = new AccountTeam();
        $team->setAccount($accounts['focal55']);
        $team->setName('Administrators');
        $team->setAdmin(true);
        $manager->persist($team);
        $teams['focal55Admins'] = $team;

        $manager->flush();

        // Focal55 users
        $user = new User();
        $user->setActive(true);
        $user->setEmail('joe@focal55.com');
        $user->setFirstName('Joe');
        $user->setLastName('Ybarra');
        $user->setPlainPassword('test');
        $manager->persist($user);
        $manager->flush();
        $teams['focal55Admins']->addUser($user);
        $users['joe'] = $user;

        $user = new User();
        $user->setActive(true);
        $user->setEmail('margaret@focal55.com');
        $user->setFirstName('Margaret');
        $user->setLastName('Ybarra');
        $user->setPlainPassword('test');
        $manager->persist($user);
        $manager->flush();
        $teams['focal55Admins']->addUser($user);
        $users['margaret'] = $user;

        $manager->flush();

        // Events
        $date = new \DateTime('2017-01-01 6:00:00');
        $e = new Event();
        $e->setTitle('Small Group');
        $e->setDayOfWeek(["mon", "wed", "fri"]);
        $e->setEventStartTime('5:30pm');
        $e->setEventEndTime('6:30pm');
        $e->setRegistrationType('standard');
        $e->setStartDate($date);
        $e->setPrimaryInstructor($users['joe']->getId());
        $e->setStatus('active');

        $manager->persist($e);

        // Events
        $e = new Event();
        $e->setTitle('Pilates');
        $e->setDayOfWeek(["tue", "thur"]);
        $e->setEventStartTime('5:30pm');
        $e->setEventEndTime('6:30pm');
        $e->setRegistrationType('standard');
        $e->setStartDate($date);
        $e->setPrimaryInstructor($users['joe']->getId());
        $e->setStatus('active');

        $manager->persist($e);

        $manager->flush();
    }
}
