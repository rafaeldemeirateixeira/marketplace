<?php

namespace App\EventSubscriber;

use App\Entity\User;
use App\Interface\User\PasswordAuthenticationInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class PasswordAuthenticationSubscriber implements EventSubscriberInterface
{
    /**
     * @param TokenStorageInterface $tokenStorage
     * @param UserPasswordHasherInterface $userPasswordHasher
     */
    public function __construct(
        public TokenStorageInterface $tokenStorage,
        public UserPasswordHasherInterface $userPasswordHasher
    ) {
        //
    }

    public function onKernelController(ControllerEvent $event)
    {
        $controller = $event->getController();

        // when a controller class defines multiple action methods, the controller
        // is returned as [$controllerInstance, 'methodName']
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof PasswordAuthenticationInterface) {
            $token = $this->tokenStorage->getToken();
            if (!$token) {
                return;
            }

            /** @var User */
            $user = $token->getUser();
            $currentPassword = $event->getRequest()->request->get('current_password');
            if (empty($currentPassword)) {
                throw new HttpException(
                    Response::HTTP_UNPROCESSABLE_ENTITY,
                    'This action needs a valid current password.'
                );
            }

            if (!$this->userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                throw new HttpException(
                    Response::HTTP_FORBIDDEN,
                    'Invalid credentials.'
                );
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}
