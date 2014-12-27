<?php
require("./sendmail.php");
$sendmail = new SendMail;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= $sendmail->projectName; ?></title>
	<link href="./img/favicon.ico" rel="shortcut icon">
	<link href="./bootstrap-3.1.1-dist/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
	<link href="./style/style.css" type="text/css" rel="stylesheet"/>
	<script src="./js/jquery-1.8.3.min.js" type="text/javascript"></script>
	<script src="./bootstrap-3.1.1-dist/js/bootstrap.min.js" type="text/javascript"></script>
	<script src="./js/sendmail.js?v<?= rand(100, 999); ?>" type="text/javascript"></script>
</head>

<body>
	<div class="navbar navbar-default navbar-fixed-top" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?= $sendmail->siteUrl; ?>"><?= $sendmail->projectName; ?></a>
			</div>
			<div class="navbar-collapse collapse">
			    <?php /*
				<ul class="nav navbar-nav">
					<li class="active"><a href="#">Home</a></li>
					<li><a href="#about">About</a></li>
					<li><a href="#contact">Contact</a></li>
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<li><a href="#">Action</a></li>
							<li><a href="#">Another action</a></li>
							<li><a href="#">Something else here</a></li>
							<li class="divider"></li>
							<li class="dropdown-header">Nav header</li>
							<li><a href="#">Separated link</a></li>
							<li><a href="#">One more separated link</a></li>
						</ul>
					</li>
				</ul>
                */ ?>
				<ul class="nav navbar-nav navbar-right">
				    <li class="dropdown">
				        <a href="#" class="dropdown-toggle" data-toggle="dropdown">Опции <b class="caret"></b></a>
    				    <ul class="dropdown-menu">
    				        <li class="dropdown-header">Nav header</li>
    				        <li class="divider"></li>
    				        <li><a href="#" onclick="return SendMail.clearTrash();"><span class="glyphicon glyphicon-filter"></span> Очистить E-mail'ы</a></li>
    				        <li><a href="?parseFolder" onclick="return SendMail.parseFolder();"><span class="glyphicon glyphicon-import"></span> Распарсить папку</a></li>
    				    </ul>
    				</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="container">
		<?php
		    $sendmail->getListEmails();
		    if ($countEmails = count($sendmail->Data['Emails'])) echo '<p>Элементов в массиве: ' . $countEmails . '</p>';
		?>
		<div class="jumbotron">
			<h1>Форма добавления адресов</h1>
			<form class="form-horizontal" action="" method="post">
				<div class="form-group">
					<label class="control-label col-sm-3" for="inputEmail">E-mail адрес</label>
					<div class="col-sm-9">
						<input class="form-control input-sm" id="inputEmail" type="email" name="email" placeholder="info@mycompany.ru">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="inputUrl">Url адрес (с http://)</label>
					<div class="col-sm-9">
						<input class="form-control input-sm" id="inputUrl" type="url" name="url" placeholder="http://somewhere.ru">
					</div>
				</div>
				<div class="form-group">
					<label class="control-label col-sm-3" for="inputFile">Загрузить файл:</label>
					<div class="col-sm-9">
						<input class="form-control input-sm" id="inputFile" type="file" name="file">
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-3"></div>
					<div class="col-sm-9">
						<button class="btn btn-primary btn-sm" type="submit" name="otpravit">Отправить</button>
						<button class="btn btn-default btn-sm" type="reset">Сбросить</button>
					</div>
				</div>
			</form>
		</div>
		
		<?php // echo '<pre>'; print_r($sendmail->Data); echo '</pre>'; ?>

		<?php if ($sendmail->Data['Errors']) { ?>
		<div class="alert alert-danger">
			<?php foreach ($sendmail->Data['Errors'] as $value) { ?>
				<?= $value . '<br />'; ?>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if ($sendmail->Data['Success']) { ?>
		<div class="alert alert-success">
			<?php foreach( $sendmail->Data['Success'] as $value ) { ?>
				<?= $value . '<br />'; ?>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if ($sendmail->Data['Emails']) { ?>
		<form action="" method="post">
			<table class="table table-condensed table-hover">
				<thead>
					<tr>
						<th>Id</th>
						<th>E-mail</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($sendmail->Data['Emails'] as $key => $value) { ?>
					<tr>
						<td><?= $key; ?></td>
						<td><?= $value; ?></td>
						<td>
							<button class="btn btn-default btn-sm" type="submit" name="editEmail" value="<?= $key; ?>" onclick="return SendMail.editItem(this);"><span class="glyphicon glyphicon-pencil"></span>&nbsp; Редактировать</button>
							<button class="btn btn-default btn-sm" type="submit" name="delEmail" value="<?= $key; ?>" onclick="return SendMail.delItem(this);"><span class="glyphicon glyphicon-remove"></span>&nbsp; Удалить</button>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</form>
		<?php } ?>

	</div>

	<div id="footer">
		<div class="container">
			<p class="text-muted">&copy; <?= $sendmail->projectName; ?> <?= date(Y); ?></p>
		</div>
	</div>

</body>
</html>