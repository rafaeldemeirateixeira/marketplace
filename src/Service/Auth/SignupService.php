<?php

namespace App\Service\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SignupService
{
    /**
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(
        public EntityManagerInterface $entityManager,
        public UserPasswordHasherInterface $userPasswordHasher
    ) {
        //
    }

    /**
     * @param array $data
     * @return void
     */
    public function execute(array $data): void
    {
        $user = new User();
        $encryptedPassword = $this->userPasswordHasher->hashPassword($user, $data['password']);
        $user
            ->setEmail($data['email'])
            ->setPassword($encryptedPassword)
            ->setIsServiceProvider(false)
            ->setCreatedAt();

        /** @var UserRepository */
        $userRepository = $this->entityManager->getRepository(User::class);
        $userRepository->add($user);
    }
}
