<?php
declare(strict_types=1);

namespace App\Application\Actions\User;

use Psr\Http\Message\ResponseInterface as Response;

class SearchUsernameAction extends UserAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $keyword = $this->resolveArg('keyword');

        $users = $this->userRepository->searchByUsername("%${keyword}%");

        $this->logger->info("User search `${keyword}` list was viewed.");

        return $this->respondWithData($users);
    }
}
