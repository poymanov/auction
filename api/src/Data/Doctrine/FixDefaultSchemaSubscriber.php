<?php

declare(strict_types=1);

namespace App\Data\Doctrine;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\PostgreSqlSchemaManager;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;

class FixDefaultSchemaSubscriber implements EventSubscriber
{
    /**
     * @return string[]
     */
    public function getSubscribedEvents(): array
    {
        return [
            ToolEvents::postGenerateSchema => 'postGenerateSchema',
        ];
    }

    /**
     * @param GenerateSchemaEventArgs $args
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schemaManager = $args
            ->getEntityManager()
            ->getConnection()
            ->getSchemaManager();

        if (!$schemaManager instanceof PostgreSqlSchemaManager) {
            return;
        }

        foreach ($schemaManager->getExistingSchemaSearchPaths() as $namespace) {
            if (!$args->getSchema()->hasNamespace($namespace)) {
                $args->getSchema()->createNamespace($namespace);
            }
        }
    }
}