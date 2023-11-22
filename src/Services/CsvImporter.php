<?php

namespace App\Services;

use App\Entity\Campus;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Exception;
use League\Csv\InvalidArgument;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CsvImporter
{
    private $entityManager;
    private $passwordEncoder;
    private $validator;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
    }

    /**
     * @throws UnavailableStream
     * @throws InvalidArgument
     * @throws Exception
     */
    public function importFromCsv($file)
    {
        $csv = Reader::createFromPath($file->getRealPath(), 'r');
        $csv->setHeaderOffset(0); // La première ligne du CSV est utilisée comme en-tête
        $csv->setDelimiter(';');

        $records = $csv->getRecords(); // Récupère tous les enregistrements

        foreach ($records as $record) {

            $user = new User();
//            dd($records);
//            $record = array_flip($record);
          $user->setPseudo($record['pseudo']);
            $user->setPhone($record['phone']);
//            $user->setActiveStatus($record['activeStatus']);
            $user->setEmail($record['email']);
            $user->setFirstname($record['firstname']);
            $user->setLastname($record['lastname']);

//            if (isset($record['campus'])) {
//                $campus = $record['campus'];
//                $campus = $this->entityManager->getRepository(Campus::class)->find($campus);
//                if ($campus) {
//                    $user->setCampus($campus);
//                } else {
//                    $defaultCampusId = '1';
//                    $campus = $this->entityManager->getRepository(Campus::class)->find($defaultCampusId);
//                }
//            }

            $campusId = $record['campus'] ?? null; // Utilisez null coalescing si 'campus' peut être absent
            $campus = null;

            if ($campusId) {
                $campus = $this->entityManager->getRepository(Campus::class)->find($campusId);
            }

            if (!$campus) {
                // Si aucun campus spécifique n'est trouvé ou si 'campus' est absent, utilisez le campus par défaut
                $defaultCampusId = '1'; // Assurez-vous que cela correspond à l'ID du campus 'Rennes'
                $campus = $this->entityManager->getRepository(Campus::class)->find($defaultCampusId);
            }

            $user->setCampus($campus);

            // Encoder le mot de passe
            $encodedPassword = $this->passwordEncoder->encodePassword($user, $record['password']);
            $user->setPassword($encodedPassword);

            // Valider l'utilisateur
//            $errors = $this->validator->validate($user);
//            if (count($errors) > 0) {
//                // Logique de gestion des erreurs
//                continue; // Passe à l'enregistrement suivant en cas d'erreur
//            }

            // Activer l'utilisateur ou définir d'autres champs si nécessaire
            $user->setRoles(['ROLE_USER']); // ou un autre rôle selon vos besoins
            $user->setActiveStatus(true); // Active l'utilisateur par défaut
            $user->setProfilePicture('/public/images/defaultpicture.jpg');

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush(); // Sauvegarde tous les utilisateurs en une fois
    }
}
