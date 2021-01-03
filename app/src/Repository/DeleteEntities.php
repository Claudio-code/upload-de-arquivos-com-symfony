<?php

namespace App\Repository;

trait DeleteEntities
{
    public function runDelete(object $doctrineEntity): void
    {
        $manager = $this->getEntityManager();
        $manager->remove($doctrineEntity);
        $manager->flush();
    }
}