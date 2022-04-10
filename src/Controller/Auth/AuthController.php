<?php

namespace App\Controller\Auth;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Auth\SignupService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    /**
     * @param SignupService $signupService
     */
    public function __construct(
        private SignupService $signupService
    ) {
        //
    }

    /**
     * @Route("/api/signup", name="api_signup", methods={"POST"})
     * @return Response
     */
    public function signup(Request $request): Response
    {
        $statusCode = Response::HTTP_CREATED;
        $message = 'User created successfully.';
        try {
            $this->signupService->execute($request->request->all());
            return $this->json([
                'message' => $message,
                'code' => $statusCode
            ], $statusCode);

        } catch (UniqueConstraintViolationException $e) {
            $statusCode = Response::HTTP_CONFLICT;
            $message = $e->getMessage();
        } catch (\Exception $e) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $message = $e->getMessage();
        }

        return $this->json([
            'message' => $message,
            'code' => $statusCode
        ], $statusCode);
    }
}
