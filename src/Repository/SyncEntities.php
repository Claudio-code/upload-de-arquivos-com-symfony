<?php

namespace App\Repository;

use DateTime;
use DateTimeZone;

trait SyncEntities
{
	public function runSync(object $doctrineEntity): object
	{
		$doctrineEntity->setUpdatedAt(
			new DateTime('now', new DateTimeZone('America/Sao_Paulo'))
		);

		$this->getEntityManager()->persist($doctrineEntity);
		$this->getEntityManager()->flush();

		return $doctrineEntity;
	}
}
