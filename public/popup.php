<?php
$sendmail = new SendMail;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $sendmail->projectName; ?></title>
    <link href="img/favicon.ico" rel="shortcut icon">
    <link href="bootstrap-3.1.1-dist/css/bootstrap.min.css" type="text/css" rel="stylesheet"/>
    <link href="style/style.css" type="text/css" rel="stylesheet"/>
</head>
<body>
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?= $sendmail->siteUrl; ?>"><?= $sendmail->projectName; ?></a>
            </div>
        </div>
    </div>
    <div class="container">
        <?php if ($sendmail->Data['Errors']) { ?>
        <div class="alert alert-danger">
            <?php foreach ($sendmail->Data['Errors'] as $value) { ?>
                <?= $value . '<br />'; ?>
            <?php } ?>
        </div>
        <?php } ?>
        <?php if ($sendmail->Data['Success']) { ?>
        <div class="alert alert-success">
            <?php foreach ($sendmail->Data['Success'] as $value) { ?>
                <?= $value . '<br />'; ?>
            <?php } ?>
        </div>
        <?php } ?>
        <?php if ($_REQUEST['do'] == 'addEmail') { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Добавить e-mail адрес</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="" role="form">
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Новый Email</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="do" value="addEmail" />
                            <input id="inputEmail" class="form-control" type="email" name="email" value="" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary btn-sm" type="submit">Отправить</button>
                            <a class="btn btn-default btn-sm" href="<?= $sendmail->siteUrl; ?>">Отмена</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php } elseif (($_REQUEST['do'] == 'editEmail')) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Редактирование e-mail адреса</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="" role="form">
                    <div class="form-group">
                        <label for="inputEmail" class="col-sm-2 control-label">Новый Email</label>
                        <div class="col-sm-10">
                            <input type="hidden" name="do" value="editEmail" />
                            <?php if ($sendmail->Data['Email']['id']) { ?><input type="hidden" name="id" value="<?= $sendmail->Data['Email']['id']; ?>" /><?php } ?>
                            <input id="inputEmail" class="form-control" type="email" name="newName" value="<?= $sendmail->Data['Email']['item']; ?>" placeholder="Email">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary btn-sm" type="submit">Отправить</button>
                            <a class="btn btn-default btn-sm" href="<?= $sendmail->siteUrl; ?>">Отмена</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php } elseif (($_REQUEST['do'] == 'delEmail') && !$sendmail->Data['deleted']) { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Удаление e-mail адреса</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="" role="form">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            Подтверждаете удаление <strong><?= $sendmail->Data['Email']['item']; ?></strong> ?
                            <input type="hidden" name="do" value="delEmail" />
                            <?php if ($sendmail->Data['Email']['id']) { ?><input type="hidden" name="id" value="<?= $sendmail->Data['Email']['id']; ?>" /><?php } ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-danger btn-sm" type="submit"><span class="glyphicon glyphicon-trash"></span>&nbsp; Удалить</button>
                            <a class="btn btn-default btn-sm" href="<?= $sendmail->siteUrl; ?>">Отмена</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php } elseif ($_REQUEST['do'] == 'parseFolder') { ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Парсинг e-mail адресов в файлах</h3>
            </div>
            <div class="panel-body">
                <form class="form-horizontal" action="" role="form">
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            Подтверждаете выполнение операции ?
                            <input type="hidden" name="do" value="parseFolder" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <button class="btn btn-primary btn-sm" type="submit">Отправить</button>
                            <a class="btn btn-default btn-sm" href="<?= $sendmail->siteUrl; ?>">Отмена</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php } ?>
    </div>
</body>
</html>