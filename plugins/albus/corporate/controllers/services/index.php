<div class="control-tabs content-tabs tabs-flush" data-control="tab">
    <ul class="nav nav-tabs">
        <li class="active"><a href="#services">Услуги</a></li>
        <li><a href="#categories">Категории</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active">
            <?= $this->listRender('services') ?>
        </div>
        <div class="tab-pane">
            <?= $this->listRender('categories') ?>
        </div>
    </div>
</div>

<div class="padded-container">
    <div class="callout callout-warning">
        <div class="header">
            <i class="icon-database"></i>
            <h3>A Post "Has Many" Comments</h3>
            <p>One to many relationship</p>
        </div>
    </div>
</div>