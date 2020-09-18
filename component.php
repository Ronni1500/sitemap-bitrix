<?php
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	if($this->startResultCache()) {
		$arResult = $this->getResult($arParams);
	}
	$this->includeComponentTemplate();