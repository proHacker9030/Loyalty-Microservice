<?php

declare(strict_types=1);

namespace App\Enum;

class OrderStatuses extends AbstractEnum
{
    public const CALCULATE_FAILED = 0;
    public const CALCULATED = 1;
    public const PREPARED_FOR_PAY_LENTA = 21;
    public const PREPARED_FOR_PAY = 2;
    public const CONFIRMED_LOYALTY = 31;
    public const CONFIRMED = 3;
    public const CANCELED = 4;
    public const REFUNDED = 5;
    public const PARTIAL_REFUNDED = 6;

    public static function getLabels(): array
    {
        return [
            self::CALCULATE_FAILED => 'Ошибка расчета',
            self::CALCULATED => 'Рассчитан',
            self::PREPARED_FOR_PAY_LENTA => 'Помечен в ленте',
            self::PREPARED_FOR_PAY => 'Отправлен фискальный чек',
            self::CONFIRMED_LOYALTY => 'Подтвержден в лояльности',
            self::CONFIRMED => 'Подтвержден',
            self::CANCELED => 'Отменен',
            self::REFUNDED => 'Возвращен',
            self::PARTIAL_REFUNDED => 'Частично возвращен',
        ];
    }

    public static function getHtml(int $status_id): string
    {
        $labels = self::getLabels();
        $html = [
            self::CALCULATE_FAILED => '<span class="badge badge-danger">' . $labels[self::CALCULATE_FAILED] . '</span>',
            self::CALCULATED => '<span class="badge badge-secondary">' . $labels[self::CALCULATED] . '</span>',
            self::PREPARED_FOR_PAY_LENTA => '<span class="badge badge-warning">' . $labels[self::PREPARED_FOR_PAY_LENTA] . '</span>',
            self::PREPARED_FOR_PAY => '<span class="badge badge-warning">' . $labels[self::PREPARED_FOR_PAY] . '</span>',
            self::CONFIRMED => '<span class="badge badge-success">' . $labels[self::CONFIRMED] . '</span>',
            self::CONFIRMED_LOYALTY => '<span class="badge badge-success">' . $labels[self::CONFIRMED_LOYALTY] . '</span>',
            self::CANCELED => '<span class="badge badge-danger">' . $labels[self::CANCELED] . '</span>',
            self::REFUNDED => '<span class="badge badge-primary">' . $labels[self::REFUNDED] . '</span>',
            self::PARTIAL_REFUNDED => '<span class="badge badge-primary">' . $labels[self::PARTIAL_REFUNDED] . '</span>',
        ];

        return $html[$status_id];
    }
}
