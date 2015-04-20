<?php
$sendmail = new SendMail;

switch($_REQUEST['do']) {
    case 'addEmail':
        $sendmail->addEmail($_REQUEST['email']);
        break;
    case 'editEmail':
        $sendmail->editEmail($_REQUEST['id'], $_REQUEST['newName']);
        break;
    case 'delEmail':
        $sendmail->delEmail($_REQUEST['id']);
        break;
    case 'addUrl':
        $sendmail->addUrl($_REQUEST['url']);
        break;
    case 'parseFolder':
        $sendmail->parseFolder();
        break;
}

?>