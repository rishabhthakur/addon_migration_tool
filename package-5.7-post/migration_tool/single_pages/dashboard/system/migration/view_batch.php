<? defined('C5_EXECUTE') or die("Access Denied."); ?>
<div class="ccm-dashboard-header-buttons">
<div class="btn-group" role="group" aria-label="...">
    <a href="javascript:void(0)" data-dialog="add-to-batch" data-dialog-title="<?=t('Add Content')?>" class="btn btn-default"><?=t("Add Content to Batch")?></a>
    <a href="<?=$view->action('batch_files', $batch->getID())?>" class="btn btn-default"><?=t('Files')?></a>
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?=t('Edit Batch')?>
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li class="dropdown-header"><?=t('Map Content')?></li>
            <? foreach($mappers->getDrivers() as $mapper) {?>
                <li><a href="<?=$view->action('map_content', $batch->getId(), $mapper->getHandle())?>"><?=$mapper->getMappedItemPluralName()?></a></li>
            <? } ?>
            <li class="divider"></li>
            <? /*
            <li><a href="<?=$view->action('find_and_replace', $batch->getID())?>"><?=t("Find and Replace")?></a></li>
 */ ?>
            <li><a href="javascript:void(0)" data-dialog="create-content" data-dialog-title="<?=t('Import Batch to Site')?>" class=""><span class="text-primary"><?=t("Import Batch to Site")?></span></a>
            </li>

            <li class="divider"></li>
            <li><a href="javascript:void(0)" data-dialog="clear-batch" data-dialog-title="<?=t('Clear Batch')?>" class=""><span class="text-danger"><?=t("Clear Batch")?></span></a>
            </li>
            <li><a href="javascript:void(0)" data-dialog="delete-batch" data-dialog-title="<?=t('Delete Batch')?>"><span class="text-danger"><?=t("Delete Batch")?></span></a></li>
        </ul>
    </div>
</div>
    </div>

<div style="display: none">

    <div id="ccm-dialog-create-content" class="ccm-ui">
        <form method="post" action="<?=$view->action('create_content_from_batch')?>">
            <?=Core::make('token')->output('create_content_from_batch')?>
            <input type="hidden" name="id" value="<?=$batch->getID()?>">
            <p><?=t('Create site content from the contents of this batch?')?></p>
            <div class="dialog-buttons">
                <button class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
                <button class="btn btn-primary pull-right" onclick="$('#ccm-dialog-create-content form').submit()"><?=t('Import Batch')?></button>
            </div>
        </form>
    </div>

    <div id="ccm-dialog-delete-batch" class="ccm-ui">
        <form method="post" action="<?=$view->action('delete_batch')?>">
            <?=Loader::helper("validation/token")->output('delete_batch')?>
            <input type="hidden" name="id" value="<?=$batch->getID()?>">
            <p><?=t('Are you sure you want to delete this import batch? This cannot be undone.')?></p>
            <div class="dialog-buttons">
                <button class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
                <button class="btn btn-danger pull-right" onclick="$('#ccm-dialog-delete-batch form').submit()"><?=t('Delete Batch')?></button>
            </div>
        </form>
    </div>

    <div id="ccm-dialog-clear-batch" class="ccm-ui">
        <form method="post" action="<?=$view->action('clear_batch')?>">
            <?=Loader::helper("validation/token")->output('clear_batch')?>
            <input type="hidden" name="id" value="<?=$batch->getID()?>">
            <p><?=t('Are you sure you remove all content from this import batch? This cannot be undone.')?></p>
            <div class="dialog-buttons">
                <button class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
                <button class="btn btn-danger pull-right" onclick="$('#ccm-dialog-clear-batch form').submit()"><?=t('Clear Batch')?></button>
            </div>
        </form>
    </div>

    <div id="ccm-dialog-add-to-batch" class="ccm-ui">
        <form method="post" enctype="multipart/form-data" action="<?=$view->action('add_content_to_batch')?>">
            <?=Loader::helper("validation/token")->output('add_content_to_batch')?>
            <input type="hidden" name="id" value="<?=$batch->getID()?>">
            <div class="form-group">
                <?=Loader::helper("form")->label('xml', t('XML File'))?>
                <?=Loader::helper('form')->file('xml')?>
            </div>
        </form>
        <div class="dialog-buttons">
            <button class="btn btn-default pull-left" onclick="jQuery.fn.dialog.closeTop()"><?=t('Cancel')?></button>
            <button class="btn btn-primary pull-right" onclick="$('#ccm-dialog-add-to-batch form').submit()"><?=t('Add Content')?></button>
        </div>
    </div>
</div>


<? if ($batch) { ?>

    <h2><?=t('Batch')?>
        <small><?=$batch->getDate()->format('F d, Y g:i a')?></small></h2>

    <? if ($batch->getNotes()) { ?>
        <p><?=$batch->getNotes()?></p>
    <? } ?>

    <? if ($batch->hasRecords()) { ?>

    <h3><?=t('Status')?></h3>
    <div class="alert alert-info" id="migration-batch-status">
        <div data-message="status-message">
            <i class="fa fa-spin fa-refresh"></i> <?=t('Computing Batch Status')?>
        </div>
    </div>

    <? foreach($batch->getObjectCollections() as $collection) {
        if ($collection->hasRecords()) {
            $formatter = $collection->getFormatter();
            ?>

            <h3><?=$formatter->getPluralDisplayName()?></h3>
            <? print $formatter->displayObjectCollection()?>
        <? } ?>
    <? } ?>

    <?
    } else { ?>
        <p><?=t('This content batch is empty.')?></p>
    <? } ?>



<? } ?>



<script type="text/javascript">
    $(function() {
        $('a[data-dialog]').on('click', function() {
            var element = '#ccm-dialog-' + $(this).attr('data-dialog');
            jQuery.fn.dialog.open({
                element: element,
                modal: true,
                width: 320,
                title: $(this).attr('data-dialog-title'),
                height: 'auto'
            });
        });

    });
</script>


<style type="text/css">
    tr.migration-item-skipped td {
        color: #ddd;
        text-decoration: line-through;
    }

    table .launch-tooltip {
        margin-left: 5px;
    }
</style>