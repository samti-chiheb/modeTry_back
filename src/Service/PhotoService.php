<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Photo;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PhotoService
{
  private $fileUploader;
  private $entityManager;

  public function __construct(FileUploader $fileUploader, EntityManagerInterface $entityManager)
  {
    $this->fileUploader = $fileUploader;
    $this->entityManager = $entityManager;
  }

  public function createAndSavePhoto(UploadedFile $file): int
  {
    $filePath = $this->fileUploader->upload($file);

    $photo = new Photo();
    $photo->setPath($filePath);
    // Autres configurations de Photo...

    $this->entityManager->persist($photo);
    $this->entityManager->flush();

    return $photo->getId();
  }
}
