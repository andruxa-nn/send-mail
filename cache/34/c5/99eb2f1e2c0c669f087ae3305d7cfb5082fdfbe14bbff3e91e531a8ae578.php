<?php

/* main.php */
class __TwigTemplate_34c599eb2f1e2c0c669f087ae3305d7cfb5082fdfbe14bbff3e91e531a8ae578 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<?php
require(\"./sendmail.php\");
\$sendmail = new SendMail;
?>
<!DOCTYPE html>
<html lang=\"ru\">
<head>
\t<meta charset=\"utf-8\">
\t<meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">
\t<meta name=\"viewport\" content=\"width=device-width, initial-scale=1\">
\t<title><?= \$sendmail->projectName; ?></title>
\t<link href=\"./img/favicon.ico\" rel=\"shortcut icon\">
\t<link href=\"./bootstrap-3.1.1-dist/css/bootstrap.min.css\" type=\"text/css\" rel=\"stylesheet\"/>
\t<link href=\"./style/style.css\" type=\"text/css\" rel=\"stylesheet\"/>
\t<script src=\"./js/jquery-1.8.3.min.js\" type=\"text/javascript\"></script>
\t<script src=\"./bootstrap-3.1.1-dist/js/bootstrap.min.js\" type=\"text/javascript\"></script>
\t<script src=\"./js/sendmail.js?v<?= rand(100, 999); ?>\" type=\"text/javascript\"></script>
</head>
<body>
\t<div class=\"navbar navbar-default navbar-fixed-top\" role=\"navigation\">
\t\t<div class=\"container\">
\t\t\t<div class=\"navbar-header\">
\t\t\t\t<button type=\"button\" class=\"navbar-toggle\" data-toggle=\"collapse\" data-target=\".navbar-collapse\">
\t\t\t\t\t<span class=\"sr-only\">Toggle navigation</span>
\t\t\t\t\t<span class=\"icon-bar\"></span>
\t\t\t\t\t<span class=\"icon-bar\"></span>
\t\t\t\t\t<span class=\"icon-bar\"></span>
\t\t\t\t</button>
\t\t\t\t<a class=\"navbar-brand\" href=\"<?= \$sendmail->siteUrl; ?>\"><?= \$sendmail->projectName; ?></a>
\t\t\t</div>
\t\t\t<div class=\"navbar-collapse collapse\">
\t\t\t\t<ul class=\"nav navbar-nav navbar-right\">
\t\t\t\t    <li class=\"dropdown\">
\t\t\t\t        <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\">Опции <b class=\"caret\"></b></a>
    \t\t\t\t    <ul class=\"dropdown-menu\">
    \t\t\t\t        <li class=\"dropdown-header\">Nav header</li>
    \t\t\t\t        <li class=\"divider\"></li>
    \t\t\t\t        <li><a href=\"#\" onclick=\"return SendMail.clearTrash();\"><span class=\"glyphicon glyphicon-filter\"></span> Очистить E-mail'ы</a></li>
    \t\t\t\t        <li><a href=\"<?= \$sendmail->siteUrl; ?>/popup.php?do=parseFolder\" onclick=\"return SendMail.parseFolder();\"><span class=\"glyphicon glyphicon-import\"></span> Распарсить папку</a></li>
    \t\t\t\t    </ul>
    \t\t\t\t</li>
\t\t\t\t</ul>
\t\t\t</div>
\t\t</div>
\t</div>
\t<div class=\"container\">
\t\t<?php
\t\t    \$sendmail->getListEmails();
\t\t    if (\$countEmails = count(\$sendmail->Data['Emails'])) echo '<p>Элементов в массиве: ' . \$countEmails . '</p>';
\t\t?>
\t\t<div class=\"jumbotron\">
\t\t\t<h1>Форма добавления адресов</h1>
\t\t\t<form class=\"form-horizontal\" action=\"\" method=\"post\">
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<label class=\"control-label col-sm-3\" for=\"inputEmail\">E-mail адрес</label>
\t\t\t\t\t<div class=\"col-sm-9\">
\t\t\t\t\t\t<input class=\"form-control input-sm\" id=\"inputEmail\" type=\"email\" name=\"email\" placeholder=\"info@mycompany.ru\">
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<label class=\"control-label col-sm-3\" for=\"inputUrl\">Url адрес (с http://)</label>
\t\t\t\t\t<div class=\"col-sm-9\">
\t\t\t\t\t\t<input class=\"form-control input-sm\" id=\"inputUrl\" type=\"url\" name=\"url\" placeholder=\"http://somewhere.ru\">
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<label class=\"control-label col-sm-3\" for=\"inputFile\">Загрузить файл:</label>
\t\t\t\t\t<div class=\"col-sm-9\">
\t\t\t\t\t\t<input class=\"form-control input-sm\" id=\"inputFile\" type=\"file\" name=\"file\">
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t\t<div class=\"form-group\">
\t\t\t\t\t<div class=\"col-sm-3\"></div>
\t\t\t\t\t<div class=\"col-sm-9\">
\t\t\t\t\t\t<button class=\"btn btn-primary btn-sm\" type=\"submit\" name=\"otpravit\">Отправить</button>
\t\t\t\t\t\t<button class=\"btn btn-default btn-sm\" type=\"reset\">Сбросить</button>
\t\t\t\t\t</div>
\t\t\t\t</div>
\t\t\t</form>
\t\t</div>
\t\t<?php if (\$sendmail->Data['Errors']) { ?>
\t\t<div class=\"alert alert-danger\">
\t\t\t<?php foreach (\$sendmail->Data['Errors'] as \$value) { ?>
\t\t\t\t<?= \$value . '<br />'; ?>
\t\t\t<?php } ?>
\t\t</div>
\t\t<?php } ?>
\t\t<?php if (\$sendmail->Data['Success']) { ?>
\t\t<div class=\"alert alert-success\">
\t\t\t<?php foreach (\$sendmail->Data['Success'] as \$value) { ?>
\t\t\t\t<?= \$value . '<br />'; ?>
\t\t\t<?php } ?>
\t\t</div>
\t\t<?php } ?>
\t\t<?php if (\$sendmail->Data['Emails']) { ?>
\t\t<table class=\"table table-condensed table-hover\">
\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t<th>Id</th>
\t\t\t\t\t<th>E-mail</th>
\t\t\t\t\t<th>&nbsp;</th>
\t\t\t\t</tr>
\t\t\t</thead>
\t\t\t<tbody>
\t\t\t\t<?php foreach (\$sendmail->Data['Emails'] as \$key => \$value) { ?>
\t\t\t\t<tr>
\t\t\t\t\t<td><?= \$key; ?></td>
\t\t\t\t\t<td><?= \$value; ?></td>
\t\t\t\t\t<td>
\t\t\t\t\t\t<a class=\"btn btn-default btn-sm\" href=\"<?= \$sendmail->siteUrl; ?>/popup.php?do=editEmail&id=<?= \$key; ?>\" onclick=\"return SendMail.editItem(this);\" target=\"_blank\">
\t\t\t\t\t\t    <span class=\"glyphicon glyphicon-pencil\"></span>&nbsp; Редактировать</a>
\t\t\t\t\t\t<a class=\"btn btn-default btn-sm\" href=\"<?= \$sendmail->siteUrl; ?>/popup.php?do=delEmail&id=<?= \$key; ?>\" onclick=\"return SendMail.delItem(this);\" target=\"_blank\">
\t\t\t\t\t\t    <span class=\"glyphicon glyphicon-remove\"></span>&nbsp; Удалить</a>
\t\t\t\t\t</td>
\t\t\t\t</tr>
\t\t\t\t<?php } ?>
\t\t\t</tbody>
\t\t</table>
\t\t<?php } ?>
\t</div>
\t<div id=\"footer\">
\t\t<div class=\"container\">
\t\t\t<p class=\"text-muted\">&copy; <?= \$sendmail->projectName; ?> <?= date(Y); ?></p>
\t\t</div>
\t</div>
</body>
</html>";
    }

    public function getTemplateName()
    {
        return "main.php";
    }

    public function getDebugInfo()
    {
        return array (  19 => 1,);
    }
}
