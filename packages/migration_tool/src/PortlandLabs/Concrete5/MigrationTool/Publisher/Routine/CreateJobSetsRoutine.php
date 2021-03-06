<?php
namespace PortlandLabs\Concrete5\MigrationTool\Publisher\Routine;

use Concrete\Core\Job\Job;
use PortlandLabs\Concrete5\MigrationTool\Batch\BatchInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;

defined('C5_EXECUTE') or die("Access Denied.");

class CreateJobSetsRoutine extends AbstractRoutine
{
    public function execute(BatchInterface $batch)
    {
        $sets = $batch->getObjectCollection('job_set');

        if (!$sets) {
            return;
        }

        foreach ($sets->getSets() as $set) {
            if (!$set->getPublisherValidator()->skipItem()) {
                $pkg = null;
                if ($set->getPackage()) {
                    $pkg = \Package::getByHandle($set->getPackage());
                }
                $set = \Concrete\Core\Job\Set::add($set->getName(), $pkg);
                $jobs = $set->getJobs();
                foreach ($jobs as $handle) {
                    $j = Job::getByHandle($handle);
                    if (is_object($j)) {
                        $set->addJob($j);
                    }
                }
            }
        }
    }
}
