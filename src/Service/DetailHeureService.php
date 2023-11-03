<?php

namespace App\Service;

use App\Repository\DetailHeuresRepository;

/**
 * @property DetailHeuresRepository $detailHeuresRepository
 */

class DetailHeureService
{

    public function __construct(
        DetailHeuresRepository $detailHeuresRepository
    ) {

        $this->detailHeuresRepository = $detailHeuresRepository;
    }

    public function cleanLastWeek(): void
    {
        
            $this->detailHeuresRepository->findCleanLastWeek();
    }
}
