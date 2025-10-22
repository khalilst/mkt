<?php

namespace App\Module\Mkt\Controller;

use App\Module\Mkt\Action\MeasurementSetStoreAction;
use App\Module\Mkt\Dto\MeasurementSetStoreDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class MeasurementSetStoreController extends AbstractController
{
    #[Route('api/measurement-sets', name: 'measurement_set_store', methods: ['POST'])]
    public function __invoke(
        Request $request,
        ValidatorInterface $validator,
        MeasurementSetStoreAction $measurementSetStoreAction,
    ): JsonResponse {
        $dto = MeasurementSetStoreDto::fromRequest($request)
            ->validate($validator);

        $measurementSet = $measurementSetStoreAction->execute($dto);

        return $this->json(
            data: $measurementSet,
            status: Response::HTTP_CREATED,
            context: [
                'groups' => ['measurement_set:show'],
                'json_encode_options' => JSON_PRESERVE_ZERO_FRACTION,
            ],
        );
    }
}
