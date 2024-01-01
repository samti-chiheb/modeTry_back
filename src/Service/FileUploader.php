<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class FileUploader
{
  private $targetDirectory;
  private $requestStack;

  public function __construct(ParameterBagInterface $params, RequestStack $requestStack)
  {
    $this->targetDirectory = $params->get('uploads_directory');
    $this->requestStack = $requestStack;
  }

  public function upload(UploadedFile $file): ?string
  {
    // Vérifier la taille et le format du fichier ici
    if ($file->getSize() > 2000000) { // Limiter à 2MB par exemple
      throw new \Exception("File is too large (Limit: 2MB)");
    }

    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
      throw new \Exception("Invalid file type. Only JPG, PNG and GIF are allowed.");
    }

    $filename = md5(uniqid()) . '.' . $file->guessExtension();

    try {
      $file->move($this->targetDirectory, $filename);

      $baseUrl = $this->requestStack->getCurrentRequest()->getSchemeAndHttpHost();
      return $baseUrl . '/uploads/images/' . $filename; // URL complète

    } catch (FileException $e) {

      return null;
    }
  }

  public function delete(string $filePath): bool
  {
    $fullPath = $this->targetDirectory . '/' . $filePath;

    if (file_exists($fullPath)) {
      return unlink($fullPath);
    }

    return false;
  }
}
