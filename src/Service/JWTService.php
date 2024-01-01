<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class JWTService
{
  private $config;

  public function __construct(string $secretKey)
  {
    // Assurez-vous que la clé secrète est passée via le constructeur, idéalement injectée depuis les paramètres de configuration
    $this->config = Configuration::forSymmetricSigner(
      new Sha256(),
      InMemory::plainText($secretKey) // Utilisez le contenu de la clé secrète directement
    );
  }

  public function getDataFromJWT(Request $request): array
  {
    $jwtString = str_replace('Bearer ', '', $request->headers->get('Authorization'));

    try {
      $token = $this->config->parser()->parse($jwtString);

      // Assurez-vous que le token est signé avec la clé correcte
      $constraints = [
        new SignedWith($this->config->signer(), $this->config->signingKey()),
        // Ajoutez d'autres contraintes si nécessaire, par exemple PermittedFor pour le public
      ];

      if (!$this->config->validator()->validate($token, ...$constraints)) {
        throw new \Exception("Invalid token signature");
      }

      return $token->claims()->all();
    } catch (\Exception $e) {
      return ['error' => $e->getMessage()];
    }
  }
}
