<?php

namespace App\Module\Mkt\Action;

use App\Module\Mkt\Entity\MeasurementSet;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

final class NotifyMktCalculatedAction
{
    public function __construct(private HubInterface $hub) {}

    public function execute(MeasurementSet $measurementSet): void
    {
        $update = new Update(
            sprintf('/measurement-sets/%d', $measurementSet->getId()),
            json_encode([
                'mkt' => $measurementSet->getMkt(),
                'status' => $measurementSet->getStatus()->value,
            ]),
        );

        $this->hub->publish($update);
    }
}
