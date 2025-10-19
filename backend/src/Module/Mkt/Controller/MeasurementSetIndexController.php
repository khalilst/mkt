<?php

namespace App\Module\Mkt\Controller;

use App\Module\Mkt\Query\MeasurementSetIndexQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class MeasurementSetIndexController extends AbstractController
{
    #[Route('/api/measurement-sets', name: 'measurement_set_index')]
    public function __invoke(Request $request, MeasurementSetIndexQuery $query): JsonResponse
    {
        return $this->json(
            data: $query->getPaginatedList(
                $request->get('page', 1),
                $request->get('limit'),
            ),
            context: [
                'groups' => ['measurement_set:index'],
                'json_encode_options' => JSON_PRESERVE_ZERO_FRACTION,
            ],
        );
    }
}
