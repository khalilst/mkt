<?php

namespace App\Module\Mkt\Controller;

use App\Module\Mkt\Entity\MeasurementSet;
use App\Module\Mkt\Query\MeasurementIndexQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MeasurementIndexController extends AbstractController
{
    #[Route('/api/measurement-sets/{measurementSet}/measurements', name: 'measurement_index')]
    public function __invoke(Request $request, MeasurementSet $measurementSet, MeasurementIndexQuery $query): JsonResponse
    {
        return $this->json(
            data: $query->getPaginatedList(
                $measurementSet->getId(),
                $request->get('page', 1),
                $request->get('limit'),
            ),
            context: [
                'groups' => ['measurement:index'],
                'json_encode_options' => JSON_PRESERVE_ZERO_FRACTION,
            ],
        );
    }
}
