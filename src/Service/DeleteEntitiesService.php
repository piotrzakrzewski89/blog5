<?php

namespace App\Service;

class DeleteEntitiesService
{
    public function deleteEntities($em, $entities)
    {
        foreach ($entities as $entity) {
            $em->remove($entity);
        }
    }
}
