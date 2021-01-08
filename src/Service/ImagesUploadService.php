<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;

class ImagesUploadService
{
    public function uploadNewImage($pictureFileName, $catalogPath)
    {
        try {
            $oryginalFileName = pathinfo($pictureFileName->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9] remove; Lower()', $oryginalFileName);
            $newFileNamePhoto = $safeFileName . '-' . uniqid() . '.' . $pictureFileName->guessExtension();
            $pictureFileName->move($catalogPath, $newFileNamePhoto);
            return $newFileNamePhoto;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function uploadEditImage($pictureFileName, $oldFilePath, $catalogPath)
    {

        $filesystem = new Filesystem();
        try {
            $filesystem->remove([$catalogPath . $oldFilePath]);
            $oryginalFileName = pathinfo($pictureFileName->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9] remove; Lower()', $oryginalFileName);
            $newFileNamePhoto = $safeFileName . '-' . uniqid() . '.' . $pictureFileName->guessExtension();
            $pictureFileName->move($catalogPath, $newFileNamePhoto);
            return $newFileNamePhoto;
        } catch (\Exception $e) {
            return false;
        }
    }
}
