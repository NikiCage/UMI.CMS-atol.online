<head>
	<title>Atol Install</title>
	<meta charset="utf-8"/>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<style>
		body {
			padding-top: 20px;
			padding-bottom: 20px;
		}

		.header,
		.marketing,
		.footer {
			padding-right: 15px;
			padding-left: 15px;
		}

		.header {
			padding-bottom: 20px;
			border-bottom: 1px solid #e5e5e5;
		}

		.header h3 {
			margin-top: 0;
			margin-bottom: 0;
			line-height: 40px;
		}

		.footer {
			padding-top: 19px;
			color: #777;
			border-top: 1px solid #e5e5e5;
		}

		@media (min-width: 768px) {
			.container {
				max-width: 730px;
			}
		}

		.container-narrow > hr {
			margin: 30px 0;
		}

		.jumbotron {
			text-align: center;
			border-bottom: 1px solid #e5e5e5;
		}

		.jumbotron .btn {
			padding: 8px;
			font-size: 16px;
		}

		.marketing {
			margin: 40px 0;
		}

		.marketing p + h4 {
			margin-top: 28px;
		}

		@media screen and (min-width: 768px) {
			.header,
			.marketing,
			.footer {
				padding-right: 0;
				padding-left: 0;
			}

			.header {
				margin-bottom: 30px;
			}

			.jumbotron {
				border-bottom: 0;
			}
		}
	</style>
</head>
<body>
<?php

error_reporting(E_ALL);
include "../standalone.php";
$moduleName = 'fx_atol';

$mode = getRequest('mode');
$dir = CURRENT_WORKING_DIR."/".$moduleName;

function installUmiDump($dir, $name){
	$importer = new xmlImporter();
	$importer->loadXmlFile($dir . "/{$name}.xml");
	$importer->setUpdateIgnoreMode(false); // режим НЕ обновления уже существующих записей
	$importer->setIgnoreParentGroups(false); // режим добавления родительских групп в типы данных
	$importer->setFilesSource($dir.'/files'); // путь до файлов
	$importer->execute();
}

?>
<div class="container">
	<div class="header clearfix">
		<div class="col-sm-3">
			<a href="http://online.atol.ru/">
				<img src="logo.png"/>
			</a>
		</div>

		<div class="col-sm-2 col-sm-offset-7">
			<a href="http://foxinet.ru/">
				<img src="http://foxinet.ru/images/foxi_logo.png" height="30px" alt="Foxinet Studio"/>
			</a>
		</div>
	</div>

	<div class="jumbotron">
		<?php
		if($mode == 'modules'){
			installUmiDump($dir, "dump");
			installUmiDump($dir, "{$moduleName}_modules");

			$modulePath = CURRENT_WORKING_DIR."/classes/modules/$moduleName/install.php";
			require_once $modulePath;
			def_module::install($INFO);

			?>
			<h1>АТОЛ Онлайн</h1>
			<p class="lead">Модуль успешно установлен.</p>
			<p>
				<a class="btn btn-success" href="/admin/<?=$moduleName?>/" role="button">Перейти к настройке</a>
			</p>
			<?php
		} elseif ($mode == 'components'){
			installUmiDump($dir, "dump");
			installUmiDump($dir, "{$moduleName}_components");

			$modulePath = CURRENT_WORKING_DIR."/classes/components/$moduleName/install.php";
			require_once $modulePath;
			def_module::install($INFO);

			?>
			<h1>АТОЛ Онлайн</h1>
			<p class="lead">Модуль успешно установлен!</p>
			<p>
				<a class="btn btn-success" href="/admin/<?=$moduleName?>/" role="button">Перейти к настройке</a>
			</p>
			<?php
		} else {
			?>
			<h1>АТОЛ Онлайн</h1>
			<p class="lead">Модуль для UMI.CMS позволяет быстро подключить отправку чеков в KaaS АТОЛ Онлайн.</p>
			<p>
				<a class="btn btn-success" href="/<?=$moduleName?>/components" role="button">Установка для версий на PHP7</a>
				<a class="btn btn-success" href="/<?=$moduleName?>/modules" role="button">Установка для версий на PHP5</a>
			</p>
			<?php
		}
		?>

	</div>

	<?php if($mode != 'modules' && $mode != 'components') :?>
	<div class="row marketing">
		<div class="col-lg-6">
			<h4>Надежно</h4>
			<p>Обеспечение работоспособности кассы 24/7/365, контроль очереди транзакций.</p>

			<h4>Удобно</h4>
			<p>Личный кабинет, доступ к сервисной статистике, все услуги и платежи в одном окне.</p>
		</div>

		<div class="col-lg-6">
			<h4>Выгодно</h4>
			<p>Соответствие 54-ФЗ с минимальными затратами средств и усилий </p>

			<h4>Безопасно</h4>
			<p>Размещение касс в ЦОД уровня TierIII. Подключение к сервису с авторизацией по протоколу HTTPS</p>
		</div>
	</div>
	<h3 class="text-center">Для работы требуются модули:</h3>
	<div class="row marketing">
		<div class="col-lg-6">
			<div class="col-sm-3">
				<img src="../images/cms/admin/mac/icons/medium/emarket.png" width="100%" alt="Интернет-магазин"/>
			</div>
			<div class="col-sm-9">
				<h4>Интернет-магазин</h4>
				<p>Чеки отправляются, когда заказ в UMI.CMS отмечается как принятый.</p>
			</div>
		</div>

		<div class="col-lg-6">
			<div class="col-sm-3">
				<img src="../images/cms/admin/mac/icons/medium/catalog.png" width="100%" alt="Каталог"/>
			</div>
			<div class="col-sm-9">
				<h4>Каталог</h4>
				<p>В объектах каталога можно выставлять индивидуальный процент НДС для каждой позиции.</p>
			</div>
		</div>
	</div>
	<?php endif;?>



	<footer class="footer">
		<p>&copy; 2017 Foxinet Studio.</p>
	</footer>

</div> <!-- /container -->

</body>
