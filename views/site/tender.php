<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'Tenders';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $tenderProvider,
        'columns' => [
            'id',
            'tender_id',
            'description',
            'amount',
            'date_modified:datetime'
        ],
    ]) ?>
</div>
