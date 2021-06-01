<?php

namespace App\Subscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Entity\WebHook;
use App\Service\AssentService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class WebHookSubscriber implements EventSubscriberInterface
{
    private $trouwService;

    public function __construct(AssentService $trouwService)
    {
        $this->trouwService = $trouwService;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['webHook', EventPriorities::PRE_VALIDATE],
        ];
    }

    public function webHook(ViewEvent $event)
    {
        $webHook = $event->getControllerResult();

        if ($webHook instanceof WebHook) {
            $this->trouwService->webHook($webHook);
        }
    }
}
