<?php
declare(strict_types=1);

namespace App\Application\Actions\Matches;

use Psr\Http\Message\ResponseInterface as Response;

class ListSuperstarsAction extends SuperstarAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $superstars = $this->superstarRepository->findAll();

        $this->logger->info("Superstar list was viewed.");

        return $this->respondWithData($superstars);
    }
}
