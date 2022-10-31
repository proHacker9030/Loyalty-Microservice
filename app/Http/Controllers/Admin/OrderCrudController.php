<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Enum\OrderStatuses;
use App\Helpers\StringHelper;
use App\Http\Requests\OrderRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class OrderCrudController.
 *
 * @property \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrderCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    // use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     */
    public function setup(): void
    {
        CRUD::setModel(\App\Models\Orders::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/order');
        CRUD::setEntityNameStrings('платеж', 'платежи');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     */
    protected function setupListOperation(): void
    {
        CRUD::column('order_id')->label('Id')->wrapper([
            'element' => 'span',
            'class' => function ($crud, $column, $entry, $related_key) {
                if (!empty($entry->error_text)) {
                    return 'text-danger';
                }

                return '';
            },
        ]);
        CRUD::addColumn([
            'name' => 'status_id',
            'label' => 'Статус',
            'type' => 'custom_html',
            'value' => function ($p) {
                return OrderStatuses::getHtml($p->status_id);
            },
            'searchLogic' => function (Builder $query, $column, $searchTerm): void {
                $findedStatuses = StringHelper::pregSearchValues($searchTerm, OrderStatuses::getLabels());
                if (empty($findedStatuses)) {
                    return;
                }
                $query->orWhereIn('status_id', array_keys($findedStatuses));
            },
        ]);
        CRUD::column('default_amount')->label('Исходная сумма');
        CRUD::column('amount')->label('Сумма с учетом скидки');
        CRUD::column('discount_amount')->label('Сумма скидки');
        CRUD::column('bonuses')->label('Бонусы');
        CRUD::column('promocode')->label('Промокод');
        CRUD::column('user.user_id')->label('Id пользователя')->visibleInTable(false);
        CRUD::column('user.email')->label('Email');
        CRUD::addColumn([
            'name' => 'error_text',
            'label' => 'Ошибка',
            'type' => 'custom_html',
            'visibleInTable' => false,
            'value' => function ($p) {
                if (empty($p->error_text)) {
                    return '-';
                }

                return '<span style="font-weight: normal;font-size: initial;" class="badge badge-danger">' . $p->error_text . '</span>';
            },
        ]);
        CRUD::column('project.name')->label('Проект')->searchLogic(
            function (Builder $query, $column, $searchTerm): void {
                $query->orWhereHas('project', function ($q) use ($searchTerm): void {
                    $q->where('name', 'like', '%' . $searchTerm . '%');
                });
            }
        );

        CRUD::column('created_at')->label('Создан')->visibleInTable(false);
        CRUD::column('updated_at')->label('Обновлен')->visibleInTable(false);

        CRUD::addButtonFromView('line', 'logs', 'viewLog', 'beginning');
        CRUD::addButtonFromView('line', 'operations', 'operations', 'end');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']);.
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     */
    protected function setupCreateOperation(): void
    {
        CRUD::setValidation(OrderRequest::class);

        CRUD::field('id');
        CRUD::field('order_id')->label('Id заказа');
        CRUD::field('status_id');
        CRUD::field('amount');
        CRUD::field('discount_amount');
        CRUD::field('bonuses');
        CRUD::field('promocode');
        CRUD::field('user_id');
        CRUD::field('user_email');
        CRUD::field('error_text');
        CRUD::field('project_id');
        CRUD::field('created_at');
        CRUD::field('updated_at');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));.
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     */
    protected function setupUpdateOperation(): void
    {
        $this->setupCreateOperation();
    }

    // if you just want to show the same columns as inside ListOperation
    protected function setupShowOperation(): void
    {
        $this->setupListOperation();
    }
}
