<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPersister implements DataPersisterInterface
{

  public function __construct(
    private EntityManagerInterface $em,
    private UserPasswordHasherInterface $passwordHasher
  ){}

  public function supports($data): bool
  {
    return $data instanceof User;
  }

  public function persist($data): void
  {
    // 1. Mettre une date de création sur le lodging
    // $data->setCreatedAt(new \DateTime());
    // 2. Mettre une date de update sur le lodging
    // dd($data->getPassword());
    $hashedPassword = $this->passwordHasher->hashPassword($data, $data->getPassword());

    // dd($hashedPassword);
    $data->setPassword($hashedPassword);
    // 3. Ask Doctrine to persist the data
    $this->em->persist($data);
    // 4. Send data to the DB
    $this->em->flush();
  }

  public function remove($data): void
  {
    // 1. Demander à doctrine de supprimer le lodging
    $this->em->remove($data);
    $this->em->flush();
  }
}
