<?php
namespace PortlandLabs\Concrete5\MigrationTool\Publisher\Routine;

use PortlandLabs\Concrete5\MigrationTool\Batch\BatchInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;

defined('C5_EXECUTE') or die("Access Denied.");

class CreatePackagesRoutine extends AbstractRoutine
{
    public function execute(BatchInterface $batch)
    {
        $packages = $batch->getObjectCollection('package');

        if (!$packages) {
            return;
        }

        foreach ($packages->getPackages() as $package) {
            if (!$package->getPublisherValidator()->skipItem()) {
                $pkg = \Package::getClass($package->getHandle());
                if (!$pkg->isPackageInstalled()) {
                    $pkg->install();
                }
            }
        }
    }
}
