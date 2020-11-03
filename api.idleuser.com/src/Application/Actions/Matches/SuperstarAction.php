<?php
declare(strict_types=1);

namespace App\Application\Actions\Matches;

use App\Application\Actions\Action;
use App\Domain\Matches\Repository\SuperstarRepository;
use Psr\Log\LoggerInterface;

abstract class SuperstarAction extends Action
{
    /**
     * @var SuperstarRepository
     */
    protected $superstarRepository;

    /**
     * @param LoggerInterface $logger
     * @param SuperstarRepository  $superstarRepository
     */
    public function __construct(LoggerInterface $logger,  SuperstarRepository $superstarRepository)
    {
        parent::__construct($logger);
        $this->superstarRepository = $superstarRepository;
    }
}
