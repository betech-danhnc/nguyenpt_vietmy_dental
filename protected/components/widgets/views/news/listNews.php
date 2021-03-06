<?php // if (!Yii::app()->user->isGuest) : ?>
<?php if (CommonProcess::checkUserIsLoggedIn()) : ?>
<?php foreach (NewsCategories::getListParent() as $category) : ?>
<?php
$mNews = new News('search');
$aNews = $mNews->getArrayNews(News::STATUS_ACTIVE, $category->id);
?>
<div class="ad_read_news">
    <section>
        <h1 class="title"><strong><?php echo $category->name; ?></strong></h1>
        <div class="content document">
            <ul>
                <?php foreach ($aNews as $key => $mNews) { ?>
                <li>
                    <a target="" class="_link <?php echo ($mNews->isNew()) ? 'new' : ''; ?>" href="<?php echo Yii::app()->createAbsoluteUrl('/front/news/view',['id'=>$mNews->id]); ?>"><?php echo $mNews->getField('description'); ?></a>
                </li>
                <?php } ?>
                <?php foreach($category->rChildren as $childCategory) : ?>
                    <li>
                        <a target="" class="_link" href="<?php echo $childCategory->getFrontEndUrl(); ?>"><?php echo $childCategory->name; ?></a>
                    </li>
                    <?php
                    $aNews = $mNews->getArrayNews(News::STATUS_ACTIVE, $childCategory->id);
                    ?>
                    <?php foreach ($aNews as $key => $mNews) { ?>
                    <li style="margin-left: 26px;" type="square">
                        <a target="" class="_link <?php echo ($mNews->isNew()) ? 'new' : ''; ?>" href="<?php echo Yii::app()->createAbsoluteUrl('/front/news/view',['id'=>$mNews->id]); ?>">
                            <?php echo DomainConst::SPACE . $mNews->getField('description'); ?>
                        </a>
                    </li>
                    <?php } ?>
                <?php endforeach; ?>
            </ul>
        </div>
    </section>
</div>
<?php endforeach; ?>
<?php endif; ?>

<style>
    .new {
        color: red;
        font-weight: bold;
    }
</style>