<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
{{--<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>--}}
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('project') }}'><i class='nav-icon la la-project-diagram text-primary'></i> Проекты</a></li>
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('order') }}'><i class='nav-icon la la-money' style="color: green"></i> Платежи</a></li>
<li class='nav-item'><a class='nav-link' target="_blank" href='{{ backpack_url('telescope') }}'><i class='nav-icon la la-info text-primary'></i> Логи</a></li>
<li class='nav-item'><a class='nav-link' target="_blank" href='{{ backpack_url('health') }}'><i class='nav-icon la la-heartbeat' style="color: red"></i> Состояние сервера</a></li>


<form id="operation-form" action="" method="POST"
      style="display: none;">
    @csrf
</form>
