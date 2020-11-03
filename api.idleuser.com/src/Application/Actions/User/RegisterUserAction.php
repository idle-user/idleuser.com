<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use App\Domain\User\Service\RegisterUserService;
use App\Domain\User\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface as Response;

class RegisterUserAction extends UserAction
{
    private $service;

    public function __construct(LoggerInterface $logger, UserRepository $userRepository, RegisterUserService $service)
    {
        parent::__construct($logger, $userRepository);
        $this->service = $service;
    }

    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    { 
        $username = $this->resolvePost('username');

        $this->logger->info("User `${username}` register attempt.");

        $user = $this->service->run($this->request->getParsedBody());

        return $this->respondWithData($user);
    }

}
