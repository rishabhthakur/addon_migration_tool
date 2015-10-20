<?php

namespace PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Type;

use Concrete\Core\Page\Type\Type;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\Item;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\Item\ItemInterface;
use PortlandLabs\Concrete5\MigrationTool\Batch\ContentMapper\MapperInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\TargetItem;
use PortlandLabs\Concrete5\MigrationTool\Entity\ContentMapper\TargetItemInterface;
use PortlandLabs\Concrete5\MigrationTool\Entity\Import\Batch;

defined('C5_EXECUTE') or die("Access Denied.");

class Area implements MapperInterface
{

    public function getMappedItemPluralName()
    {
        return t('Areas');
    }

    public function getHandle()
    {
        return 'area';
    }

    public function getItems(Batch $batch)
    {
        $areas = array();
        foreach($batch->getPages() as $page) {
            foreach($page->getAreas() as $area) {
                if ($area->getName() && !in_array($area->getName(), $areas)) {
                    $areas[] = $area->getName();
                }
            }
        }

        $pageTypes = $batch->getObjectCollection('page_type');
        foreach($pageTypes->getTypes() as $type) {
            $defaults = $type->getDefaultPageCollection();
            foreach($defaults->getPages() as $page) {
                foreach($page->getAreas() as $area) {
                    if ($area->getName() && !in_array($area->getName(), $areas)) {
                        $areas[] = $area->getName();
                    }
                }
            }
        }

        $items = array();
        foreach($areas as $area) {
            $item = new Item();
            $item->setIdentifier($area);
            $items[] = $item;
        }
        return $items;
    }

    public function getMatchedTargetItem(Batch $batch, ItemInterface $item)
    {
        $list = \Concrete\Core\Area\Area::getHandleList();
        if (in_array($item->getIdentifier(), $list)) {
            $targetItem = new TargetItem($this);
            $targetItem->setItemId($item->getIdentifier());
            $targetItem->setItemName($item->getIdentifier());
            return $targetItem;
        }
    }

    public function getBatchTargetItems(Batch $batch)
    {
        return array();
    }

    public function getInstalledTargetItems(Batch $batch)
    {
        $areas = \Concrete\Core\Area\Area::getHandleList();
        asort($areas);
        $items = array();
        foreach($areas as $area) {
            $item = new TargetItem($this);
            $item->setItemId($area);
            $item->setItemName($area);
            $items[] = $item;
        }
        return $items;
    }

    public function getTargetItemContentObject(TargetItemInterface $targetItem)
    {
        return $targetItem->getItemID();
    }




}