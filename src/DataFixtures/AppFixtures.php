<?php

namespace App\DataFixtures;

use App\Entity\Activity;
use App\Entity\Campus;
use App\Entity\City;
use App\Entity\Location;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {


        //        Fixtures de ville

        $city1 = new City();
        $city1->setName('Rennes');
        $city1->setPostcode('35000');


        $manager->persist($city1);
        $manager->flush();

        $city2 = new City();
        $city2->setName('Rouen');
        $city2->setPostcode('76000');


        $manager->persist($city2);
        $manager->flush();

        $city3 = new City();
        $city3->setName('Nantes');
        $city3->setPostcode('44000');


        $manager->persist($city3);
        $manager->flush();

        $city4 = new City();
        $city4->setName('Brest');
        $city4->setPostcode('29000');


        $manager->persist($city4);
        $manager->flush();


        //        Fixtures d'etat d'activités

        $status1 = new Status();
        $status1->setWording('Créée');

        $manager->persist($status1);
        $manager->flush();

        $status2 = new Status();
        $status2->setWording('Ouverte');

        $manager->persist($status2);
        $manager->flush();

        $status3 = new Status();
        $status3->setWording('Clôturée');

        $manager->persist($status3);
        $manager->flush();

        $status4 = new Status();
        $status4->setWording('Activité en cours');

        $manager->persist($status4);
        $manager->flush();

        $status5 = new Status();
        $status5->setWording('Passée');

        $manager->persist($status5);
        $manager->flush();

        $status6 = new Status();
        $status6->setWording('Annulée');

        $manager->persist($status6);
        $manager->flush();

        $status7 = new Status();
        $status7->setWording('Archivée');

        $manager->persist($status7);
        $manager->flush();


        //        Fixtures de lieu

        $location1 = new Location();
        $location1->setName('Les gayeulles');
        $location1->setStreetName('8 rue des test1');
        $location1->setLatitude(48.08332);
        $location1->setLongitude(-1.68333);
        $location1->setCities($city1);

        $manager->persist($location1);
        $manager->flush();

        $location2 = new Location();
        $location2->setName('Les peupliers');
        $location2->setStreetName('4 rue des test2');
        $location2->setLatitude(48.08332);
        $location2->setLongitude(-1.68333);
        $location2->setCities($city2);

        $manager->persist($location2);
        $manager->flush();

        $location3 = new Location();
        $location3->setName('Crozon');
        $location3->setStreetName('7 avenue des plages');
        $location3->setLatitude(48.08332);
        $location3->setLongitude(-1.68333);
        $location3->setCities($city3);

        $manager->persist($location3);
        $manager->flush();

        $location4 = new Location();
        $location4->setName('Chez Maxime !');
        $location4->setStreetName('32 rue let\'s go dude');
        $location4->setLatitude(48.08332);
        $location4->setLongitude(-1.68333);
        $location4->setCities($city4);

        $manager->persist($location4);
        $manager->flush();


        //        Fixtures des campus

        $campus1 = new Campus();
        $campus1->setName('Nantes');

        $manager->persist($campus1);
        $manager->flush();

        $campus2 = new Campus();
        $campus2->setName('Brest');

        $manager->persist($campus2);
        $manager->flush();

        $campus3 = new Campus();
        $campus3->setName('Rennes');

        $manager->persist($campus3);
        $manager->flush();

        $campus4 = new Campus();
        $campus4->setName('Rouen');

        $manager->persist($campus4);
        $manager->flush();


        //        Fixtures des Users


        $user1 = new User();
        $user1->setPseudo('Boby');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setEmail('bob@gmail.com');
        $user1->setActiveStatus(true);
        $user1->setFirstname('Bob');
        $user1->setLastname('Boba');
        $user1->setPhone('0682929200');
        $user1->setCampus($campus1);
        $user1->setProfilePicture("telechargement-655f1df83934a.jpg");
        $user1->setPassword($this->hasher->hashPassword($user1, '0000'));


        $manager->persist($user1);
        $manager->flush();

        $user2 = new User();
        $user2->setPseudo('Max');
        $user2->setEmail('max@gmail.com');
        $user2->setActiveStatus(true);
        $user2->setFirstname('Maxime');
        $user2->setLastname('Roche');
        $user2->setPhone('0682929200');
        $user2->setCampus($campus2);
        $user2->setProfilePicture("max.jpg");
        $user2->setPassword($this->hasher->hashPassword($user2, '0000'));

        $manager->persist($user2);
        $manager->flush();

        $user3 = new User();
        $user3->setPseudo('Cassis');
        $user3->setEmail('cass@gmail.com');
        $user3->setActiveStatus(true);
        $user3->setFirstname('Cassandra');
        $user3->setLastname('Bru');
        $user3->setPhone('0682929200');
        $user3->setCampus($campus3);
        $user3->setProfilePicture("cass.jpg");
        $user3->setPassword($this->hasher->hashPassword($user3, '0000'));

        $manager->persist($user3);
        $manager->flush();

        $user4 = new User();
        $user4->setPseudo('Badabing');
        $user4->setEmail('bada@gmail.com');
        $user4->setActiveStatus(true);
        $user4->setFirstname('Quentin');
        $user4->setLastname('Lebeau');
        $user4->setPhone('0682929200');
        $user4->setCampus($campus4);
        $user4->setProfilePicture("Quentin.jpg");
        $user4->setPassword($this->hasher->hashPassword($user4, '0000'));

        $manager->persist($user4);
        $manager->flush();

        $user5 = new User();
        $user5->setPseudo('Ben le banni');
        $user5->setEmail('Ben@gmail.com');
        $user5->setActiveStatus(true);
        $user5->setFirstname('Ben');
        $user5->setLastname('Ben');
        $user5->setPhone('0682929200');
        $user5->setCampus($campus4);
        $user5->setProfilePicture("ben.jpg");
        $user5->setPassword($this->hasher->hashPassword($user5, '0000'));

        $manager->persist($user5);
        $manager->flush();


        //        Fixtures des Activitées

        $dateStart1 = new \DateTime('2023-11-16T15:52:01+00:00');
        $subLimitDate1 = new \DateTime('2023-11-17T15:52:01+00:00');
        $dateStart2 = new \DateTime('2023-11-10T15:52:01+00:00');
        $subLimitDate2 = new \DateTime('2023-11-12T15:52:01+00:00');
        $dateStart3 = new \DateTime('2023-12-01T15:52:01+00:00');
        $subLimitDate3 = new \DateTime('2024-01-19T15:52:01+00:00');

        $activities1 = new Activity();
        $activities1->setName('Char à voile');
        $activities1->setCampus($campus1);
        $activities1->setLocation($location1);
        $activities1->setOrganizer($user1);
        $activities1->setIsPublished(true);
        $activities1->setStatus($status2);
        $activities1->setDuration(60);
        $activities1->setSubMax(12);
        $activities1->setDateStart(new \DateTime('2023-12-15T12:30:01+00:00'));
        $activities1->setSubLimitDate(new \DateTime('2023-12-12T12:30:01+00:00'));
        $activities1->setInfoActivity('J\'organise une sortie char a voile, venez nombreux <3');
        $activities1->setActivityPicture("CharAVoile.jpg");


        $manager->persist($activities1);
        $manager->flush();


        $activities2 = new Activity();
        $activities2->setName('Escalade');
        $activities2->setCampus($campus2);
        $activities2->setLocation($location2);
        $activities2->setOrganizer($user2);
        $activities2->setIsPublished(false);
        $activities2->setStatus($status1);
        $activities2->setDuration(120);
        $activities2->setSubMax(10);
        $activities2->setDateStart(new \DateTime('2024-02-13T08:30:01+00:00'));
        $activities2->setSubLimitDate(new \DateTime('2024-02-10T00:00:01+00:00'));
        $activities2->setInfoActivity('Escalade en pleine nature, sur les magnifiques cotes de la presqu\'ile de Crozon');
        $activities2->setActivityPicture("escalade.jpg");

        $manager->persist($activities2);
        $manager->flush();


        $activities3 = new Activity();
        $activities3->setName('Pool party');
        $activities3->setCampus($campus3);
        $activities3->setLocation($location3);
        $activities3->setOrganizer($user3);
        $activities3->setIsPublished(true);
        $activities3->setStatus($status6);
        $activities3->setDuration(240);
        $activities3->setSubMax(35);
        $activities3->setDateStart(new \DateTime('2023-12-10T19:30:01+00:00'));
        $activities3->setSubLimitDate(new \DateTime('2023-12-05T12:30:01+00:00'));
        $activities3->setInfoActivity('Soirée entre étudiant autour d\'une piscine, cocktail, musique ! Let\'s go !');
        $activities3->setActivityPicture("poolParty.jpg");

        $manager->persist($activities3);
        $manager->flush();


        $activities4 = new Activity();
        $activities4->setName('Bar en trans');
        $activities4->setCampus($campus3);
        $activities4->setLocation($location4);
        $activities4->setOrganizer($user4);
        $activities4->setIsPublished(true);
        $activities4->setStatus($status3);
        $activities4->setDuration(120);
        $activities4->setSubMax(8);
        $activities4->setDateStart(new \DateTime('2023-11-16T08:30:01+00:00'));
        $activities4->setSubLimitDate(new \DateTime('2023-11-10T08:30:01+00:00'));
        $activities4->setInfoActivity('Petit tour des bar à Rennes pour profiter de l\'évènement');
        $activities4->setActivityPicture("barTrans.jpg");

        $manager->persist($activities4);
        $manager->flush();


        $activities5 = new Activity();
        $activities5->setName('Karaoke');
        $activities5->setCampus($campus3);
        $activities5->setLocation($location3);
        $activities5->setOrganizer($user1);
        $activities5->setIsPublished(true);
        $activities5->setStatus($status4);
        $activities5->setDuration(60);
        $activities5->setSubMax(40);
        $activities5->setDateStart(new \DateTime('2023-11-24T12:30:01+00:00'));
        $activities5->setSubLimitDate(new \DateTime('2023-11-22T08:30:01+00:00'));
        $activities5->setInfoActivity('Une soirée karaoke intense en compagnie du groupe \'Plaisir Coupable\'');
        $activities5->setActivityPicture("karaoke.webp");

        $manager->persist($activities5);
        $manager->flush();


        $activities6 = new Activity();
        $activities6->setName('Coder ensemble');
        $activities6->setCampus($campus2);
        $activities6->setLocation($location1);
        $activities6->setOrganizer($user3);
        $activities6->setIsPublished(true);
        $activities6->setStatus($status5);
        $activities6->setDuration(720);
        $activities6->setSubMax(10);
        $activities6->setDateStart(new \DateTime('2023-11-22T08:30:01+00:00'));
        $activities6->setSubLimitDate(new \DateTime('2023-11-15T08:30:01+00:00'));
        $activities6->setInfoActivity('12h pour coder un site internet, qui sera le meilleur ?');
        $activities6->setActivityPicture("code.jpg");

        $manager->persist($activities6);
        $manager->flush();



        $activities7 = new Activity();
        $activities7->setName('Saut en parachute');
        $activities7->setCampus($campus1);
        $activities7->setLocation($location1);
        $activities7->setOrganizer($user1);
        $activities7->setIsPublished(true);
        $activities7->setStatus($status7);
        $activities7->setDuration(60);
        $activities7->setSubMax(3);
        $activities7->setDateStart(new \DateTime('2023-10-22T08:30:01+00:00'));
        $activities7->setSubLimitDate(new \DateTime('2023-10-15T08:30:01+00:00'));
        $activities7->setInfoActivity('Opportunité de saut, prix : 100€');
        $activities7->setActivityPicture("parachute.jpeg");

        $manager->persist($activities7);
        $manager->flush();


    }
}
