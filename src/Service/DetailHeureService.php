<?php

namespace App\Service;

use App\Repository\DetailHeuresRepository;

/**
 * @property DetailHeuresRepository $detailHeuresRepo
 */
class DetailHeureService
{
    public function __construct(public DetailHeuresRepository $detailHeuresRepo)
    {
    }

    public function cleanLastWeek(): void
    {
        // $this->detailHeuresRepository->findCleanLastWeek();
    }
}
