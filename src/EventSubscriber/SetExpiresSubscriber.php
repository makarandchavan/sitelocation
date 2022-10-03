<?php

declare(strict_types=1);

namespace Drupal\sitelocation\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Cache\Cache;

/**
 * Base class for event page laod.
 */
class SetExpiresSubscriber implements EventSubscriberInterface {

  /**
   * Event subscriber.
   *
   * @param mixed $event
   *   Object variable.
   */
  public function onResponse($event) {
    $request = $event->getRequest();
    $response = $event->getResponse();
    if ($event->isMasterRequest()) {
      $request_time = $request->server->get('REQUEST_TIME');
      $expires_time = (new \Datetime())->setTimestamp($request_time + 60);
      $response->setExpires($expires_time);

      // Clear cache tags of our block.
      Cache::invalidateTags(['sitelocation']);
    }
  }

  /**
   * Get Subscribed events.
   *
   * @return mixed
   *   Variable.
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onResponse'];
    return $events;
  }

}
