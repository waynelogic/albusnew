<div data-control="toolbar">
    <a
        href="<?= Backend::url('albus/corporate/services') ?>"
        class="btn btn-default oc-icon-arrow-left">
        Вернуться к услугам
    </a>

    <a
        href="<?= Backend::url('albus/corporate/servicecategories/create') ?>"
        class="btn btn-primary oc-icon-plus">
        <?= e(trans('backend::lang.list.create_button', ['name'=>'Категорию услуг'])) ?>
    </a>

    <button
        class="btn btn-danger oc-icon-trash-o"
        data-request="onDelete"
        data-list-checked-trigger
        data-list-checked-request
        data-stripe-load-indicator>
        <?= e(trans('backend::lang.list.delete_selected')) ?>
    </button>
</div>
