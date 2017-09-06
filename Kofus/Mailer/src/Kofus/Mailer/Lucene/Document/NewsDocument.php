<?php

namespace Kofus\Mailer\Lucene\Document;
use Kofus\Mailer\Entity\NewsEntity;
use ZendSearch\Lucene\Document\Field;
use ZendSearch\Lucene\Document;

class NewsDocument extends Document
{
	public function populateNode(NewsEntity $entity)
	{
		$this->addField(
				Field::text('node_id', $entity->getNodeId())
		);
		$this->addField(
				Field::text('label', (string) $entity)
		);
		$this->addField(
				Field::text('node_type', $entity->getNodeType())
		);
		/*
		$this->addField(
				Field::text('content', strip_tags($entity->getContent()))
		); */
	}
}