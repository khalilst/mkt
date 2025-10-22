<?php

namespace App\Module\Mkt\EventListener;

use App\Module\Mkt\Event\MeasurementSetCreatedEvent;
use App\Module\Mkt\Message\ProcessMeasurementsFile;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Messenger\MessageBusInterface;

final class ProcessMeasurementSetListener
{
    public function __construct(private MessageBusInterface $bus) {}

    #[AsEventListener(event: MeasurementSetCreatedEvent::class)]
    public function onMeasurementSetCreatedEvent(MeasurementSetCreatedEvent $event): void
    {
        $this->bus->dispatch(
            new ProcessMeasurementsFile($event->payload),
        );
    }
}
