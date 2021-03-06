<?php
namespace PortlandLabs\Concrete5\MigrationTool\Publisher\Routine;

use PortlandLabs\Concrete5\MigrationTool\Batch\BatchInterface;

defined('C5_EXECUTE') or die("Access Denied.");

interface RoutineActionInterface
{
    public function execute(BatchInterface $batch);
}
