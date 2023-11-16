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
        $location3->setName('Les pommiers');
        $location3->setStreetName('7 rue des test3');
        $location3->setLatitude(48.08332);
        $location3->setLongitude(-1.68333);
        $location3->setCities($city3);

        $manager->persist($location3);
        $manager->flush();

        $location4 = new Location();
        $location4->setName('Les chênes');
        $location4->setStreetName('32 rue des test4');
        $location4->setLatitude(48.08332);
        $location4->setLongitude(-1.68333);
        $location4->setCities($city4);

        $manager->persist($location4);
        $manager->flush();



        //        Fixtures des campus

        $campus1 = new Campus();
        $campus1->setName('Rennes');

        $manager->persist($campus1);
        $manager->flush();

        $campus2 = new Campus();
        $campus2->setName('Rouen');

        $manager->persist($campus2);
        $manager->flush();

        $campus3 = new Campus();
        $campus3->setName('Nantes');

        $manager->persist($campus3);
        $manager->flush();

        $campus4 = new Campus();
        $campus4->setName('Brest');

        $manager->persist($campus4);
        $manager->flush();





        //        Fixtures des Users




        $user1 = new User();
        $user1->setPseudo('Boby');
        $user1->setEmail('bob@gmail.com');
        $user1->setActiveStatus(true);
        $user1->setFirstname('Bob');
        $user1->setLastname('Boba');
        $user1->setPhone('0682929200');
        $user1->setCampus($campus1);
        $user1->setProfilePicture("../../public/images/Olaf.jpg");
        $user1->setPassword($this->hasher->hashPassword($user1, '0000'));

        $manager->persist($user1);
        $manager->flush();

        $user2 = new User();
        $user2->setPseudo('Max le banni( false en Active status )');
        $user2->setEmail('max@gmail.com');
        $user2->setActiveStatus(false);
        $user2->setFirstname('Maxime');
        $user2->setLastname('Roche');
        $user2->setPhone('0682929200');
        $user2->setCampus($campus2);
        $user2->setProfilePicture("../../public/images/Olaf.jpg");
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
        $user3->setProfilePicture("../../public/images/Olaf.jpg");
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
        $user4->setProfilePicture("../../public/images/Olaf.jpg");
        $user4->setPassword($this->hasher->hashPassword($user4, '0000'));

        $manager->persist($user4);
        $manager->flush();




        //        Fixtures des Activitées

        $dateStart = new \DateTime('2005-08-15T15:52:01+00:00');
        $subLimitDate = new \DateTime('2005-08-15T15:52:01+00:00');

        $activities1 = new Activity();
        $activities1->setName('Char à voile (Acti passée) ');
        $activities1->setCampus($campus1);
        $activities1->setLocation($location1);
        $activities1->setOrganizer($user1);
        $activities1->setIsPublished(true);
        $activities1->setStatus($status5);
        $activities1->setDuration(30);
        $activities1->setSubMax(3);
        $activities1->setDateStart(new \DateTime('2023-11-16T12:30:01+00:00'));
        $activities1->setSubLimitDate(new \DateTime('2023-11-16T08:30:01+00:00'));
        $activities1->setInfoActivity('Activité début le 16/11 et donc status : passée // sub limite à 3');


        $manager->persist($activities1);
        $manager->flush();


        $activities2 = new Activity();
        $activities2->setName('Escalade (acti en cours)');
        $activities2->setCampus($campus2);
        $activities2->setLocation($location2);
        $activities2->setOrganizer($user2);
        $activities2->setIsPublished(true);
        $activities2->setStatus($status4);
        $activities2->setDuration(20160);
        $activities2->setSubMax(10);
        $activities2->setDateStart(new \DateTime('2023-11-16T08:30:01+00:00'));
        $activities2->setSubLimitDate($subLimitDate);
        $activities2->setInfoActivity('Activité en cours');

        $manager->persist($activities2);
        $manager->flush();


        $activities3 = new Activity();
        $activities3->setName('Piscine (Activitée créée mais non publiée) ');
        $activities3->setCampus($campus3);
        $activities3->setLocation($location3);
        $activities3->setOrganizer($user3);
        $activities3->setIsPublished(false);
        $activities3->setStatus($status1);
        $activities3->setDuration(30);
        $activities3->setSubMax(10);
        $activities3->setDateStart(new \DateTime('2023-12-10T08:30:01+00:00'));
        $activities3->setSubLimitDate(new \DateTime('2023-12-05T08:30:01+00:00'));
        $activities3->setInfoActivity('Activité juste créée');

        $manager->persist($activities3);
        $manager->flush();


        $activities4 = new Activity();
        $activities4->setName('Boire des coups( activitée annulée)');
        $activities4->setCampus($campus4);
        $activities4->setLocation($location4);
        $activities4->setOrganizer($user4);
        $activities4->setIsPublished(true);
        $activities4->setStatus($status6);
        $activities4->setDuration(30);
        $activities4->setSubMax(10);
        $activities4->setDateStart(new \DateTime('2023-11-16T08:30:01+00:00'));
        $activities4->setSubLimitDate(new \DateTime('2023-11-10T08:30:01+00:00'));
        $activities4->setInfoActivity('L\'acti est annulée');

        $manager->persist($activities4);
        $manager->flush();


        $activities5 = new Activity();
        $activities5->setName('Karaoke (sortie créée inscription fermée)');
        $activities5->setCampus($campus3);
        $activities5->setLocation($location3);
        $activities5->setOrganizer($user1);
        $activities5->setIsPublished(true);
        $activities5->setStatus($status3);
        $activities5->setDuration(30);
        $activities5->setSubMax(10);
        $activities5->setDateStart(new \DateTime('2023-12-16T08:30:01+00:00'));
        $activities5->setSubLimitDate(new \DateTime('2023-11-16T08:30:01+00:00'));
        $activities5->setInfoActivity('Acti clotuée');

        $manager->persist($activities5);
        $manager->flush();



        $activities6 = new Activity();
        $activities6->setName('Coder ensemble(activitée ouverte)');
        $activities6->setCampus($campus2);
        $activities6->setLocation($location1);
        $activities6->setOrganizer($user3);
        $activities6->setIsPublished(true);
        $activities6->setStatus($status2);
        $activities6->setDuration(30);
        $activities6->setSubMax(10);
        $activities6->setDateStart(new \DateTime('2023-11-28T08:30:01+00:00'));
        $activities6->setSubLimitDate(new \DateTime('2023-11-15T08:30:01+00:00'));
        $activities6->setInfoActivity('acti ouverte');

        $manager->persist($activities6);
        $manager->flush();

    }
}
