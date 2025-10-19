<?php

namespace App\Module\Mkt\Controller;

use App\Module\Mkt\Entity\MeasurementSet;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class MeasurementSetShowController extends AbstractController
{
    #[Route('/api/measurement-sets/{id}', name: 'measurement_set_show')]
    public function __invoke(MeasurementSet $measurementSet): JsonResponse
    {
        return $this->json(
            data: $measurementSet,
            context: [
                'groups' => ['measurement_set:show'],
                'json_encode_options' => JSON_PRESERVE_ZERO_FRACTION,
            ],
        );
    }
}
