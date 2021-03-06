<?php
namespace PortlandLabs\Concrete5\MigrationTool\Publisher\Routine;

use Concrete\Core\Page\Type\PublishTarget\Type\Type;
use PortlandLabs\Concrete5\MigrationTool\Batch\BatchInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;

defined('C5_EXECUTE') or die("Access Denied.");

class CreatePageTypePublishTargetTypesRoutine extends AbstractRoutine
{
    public function execute(BatchInterface $batch)
    {
        $types = $batch->getObjectCollection('page_type_publish_target_type');

        if (!$types) {
            return;
        }

        foreach ($types->getTypes() as $type) {
            if (!$type->getPublisherValidator()->skipItem()) {
                $pkg = false;
                if ($type->getPackage()) {
                    $pkg = \Package::getByHandle($type->getPackage());
                }
                Type::add($type->getHandle(), $type->getName(), $pkg);
            }
        }
    }
}
