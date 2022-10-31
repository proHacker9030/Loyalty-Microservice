# CalculateCartByAgent
### doc items
```
<document>
<
item_id=”bigint” {идентификатор позиции}
item_price=”money” {исходная стоимость позиции}
discounted_price=”money” {конечная цена позиции, после применения всех скидок (промо + бонусы}	 
promo_discounted_price=”money” {сумма скидки (промо+скидки)}
bonus_discounted_price=”money” {общая сумма примененных баллов}
discount_type=”varchar(15)”
/>
<
item_id=”bigint” {идентификатор позиции}
item_price=”money” {исходная стоимость позиции}
discounted_price=”money” {конечная цена позиции, после применения всех скидок (промо + бонусы}	 
bonus_discounted_price=”money”{общая сумма примененных баллов}
promo_discounted_price=”money” {сумма скидки}
discount_type=”varchar(15)” {тип скидки}
/>
</document>
discount_type:
Остался, чтобы не сломать старые интеграции.

DISCOUNT - в этом случае значение в поле available означает процент скидки, который рассчитывается от стоимости позиции

BONUS - в этом случае значение в поле available означает фактическую сумму, на которую необходимо уменьшить стоимость позиции

Если discount_type не указан, то по умолчанию считается, что discount_type=”BONUS”

```
