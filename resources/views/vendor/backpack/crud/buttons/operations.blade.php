@php
    use App\Enum\OrderStatuses;
    use App\Helpers\OrderHelper;
@endphp

<div class="dropdown d-inline">
    <button @if($entry->status_id === OrderStatuses::CANCELED) disabled @endif class="btn btn-secondary dropdown-toggle" type="button" id="dropdownOperationsButton"
            data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
        Операции
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownOperationsButton">
        @if($entry->status_id !== OrderStatuses::CANCELED)
            <a class="dropdown-item text-danger"
               onclick="submitForm('{{ route('admin-cancel-order', ['id' => $entry->id]) }}')"
               href="#">Отменить</a>
            <a class="dropdown-item text-danger"
               onclick="submitForm('{{ route('admin-cancel_force-order', ['id' => $entry->id]) }}')"
               href="#">Отменить принудительно</a>
        @endif
        @if(OrderHelper::isNeedToSetFiscalCheck($entry->status_id))
            <a class="dropdown-item text-warning"
               onclick="submitForm('{{ route('admin-set_fiscal_check-order', ['id' => $entry->id]) }}')"
               href="#">Отправить фискальный чек</a>
        @endif
        @if(OrderHelper::isNeedToConfirm($entry->status_id))
            <a class="dropdown-item text-success"
               onclick="submitForm('{{ route('admin-confirm-order', ['id' => $entry->id]) }}')"
               href="#">Провести</a>
        @endif
    </div>
</div>

<script>
    function submitForm(action) {
        event.preventDefault();
        if(!confirm('Вы уверены?')) {
            return false;
        }
        // See form in sidebar_content.blade.php
        let $form = document.getElementById('operation-form');
        $form.setAttribute('action', action);
        $form.submit();
    }
</script>
