<?php
declare(strict_types=1);

namespace App\Application\Actions\Matches;

use Psr\Http\Message\ResponseInterface as Response;

class ViewSuperstarAction extends SuperstarAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $superstarId = (int) $this->resolveArg('id');
        $superstar = $this->superstarRepository->findById($superstarId);

        $this->logger->info("Superstar of id `${superstarId}` was viewed.");

        return $this->respondWithData($superstar);
    }
}
