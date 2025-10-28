<?
$aMenuLinks = Array(
	Array(
		"Мой кабинет",
		"personal/",
		Array(),
		Array(),
		""
	),


	Array(
		"Текущие заказы",
		"personal/orders/",
		Array("personal/orders/"),
		Array(),
		""
	),

	Array(
		"Личный счет",
		"personal/account/",
		Array(), 
		Array(),
		"CBXFeatures::IsFeatureEnabled('SaleAccounts')"
	),

	Array(
		"Личные данные",
		"personal/private/",
		Array(), 
		Array(), 
		"" 
	),
	Array(
		"История заказов",
		"personal/orders/?filter_history=Y",
		Array("personal/orders/?filter_history=Y"),
		Array(),
		""
	),
	Array(
		"Профили заказов",
		"personal/profiles/",
		Array(),
		Array(),
		""
	),
	Array(
		"Подписки",
		"personal/subscribes/",
		Array(),
		Array(),
		""
	),
);
?>