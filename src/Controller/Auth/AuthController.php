<?php

namespace App\Controller\Auth;

use App\Request\Auth\AuthRequest;
use App\Service\Auth\SignupService;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
    public function signup(AuthRequest $request): Response
    {
        $statusCode = Response::HTTP_CREATED;
        $message = 'User created successfully.';
        $data = $request->validated();

        try {
            $this->signupService->execute($data);
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
