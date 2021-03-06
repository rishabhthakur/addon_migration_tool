<?php
namespace PortlandLabs\Concrete5\MigrationTool\Entity\Import\BlockValue;

/**
 * @Entity
 * @Table(name="MigrationImportBlockValues")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="value_type", type="string")
 */
abstract class BlockValue
{
    /**
     * @Id @Column(type="guid")
     * @GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @OneToOne(targetEntity="\PortlandLabs\Concrete5\MigrationTool\Entity\Import\AbstractBlock", mappedBy="block_value")
     **/
    protected $block;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getBlock()
    {
        return $this->block;
    }

    /**
     * @param mixed $block
     */
    public function setBlock($block)
    {
        $this->block = $block;
    }

    abstract public function getFormatter();
    abstract public function getPublisher();
    abstract public function getInspector();
}
