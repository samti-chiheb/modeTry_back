<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JWTCreatedListener
{
  public function onJWTCreated(JWTCreatedEvent $event)
  {
    // Récupération de l'utilisateur
    $user = $event->getUser();

    // Ajouter des données personnalisées au payload du Token
    $payload = $event->getData();

    $payload['id'] = $user->getId();
    $payload['email'] = $user->getEmail();
    $payload['username'] = $user->getUsername();
    $payload['profilePicture'] = $user->getProfilePicture();
    $payload['size'] = $user->getSize();
    $payload['height'] = $user->getHeight();
    $payload['createdAt'] = $user->getCreatedAt()->format('Y-m-d H:i:s');
    $payload['updatedAt'] = $user->getUpdatedAt()->format('Y-m-d H:i:s');

    // Remplacer le payload par votre payload personnalisé
    $event->setData($payload);
  }
}
