<?php
/**
 * Created by IntelliJ IDEA.
 * User: Scuola
 * Date: 08/07/2015
 * Time: 18:46
 */
if (!isset($_GET['q']))
{
    $gg = "1";
}else
    $gg = $_GET['q'];

if (!isset($_GET['i']))
{
    $pp = "Parasar1267072903";
}else
    $pp = $_GET['i'];

require_once "librerie/sql.php";
require_once "librerie/date.php";
$rt["id"]=$pp;
$rt["eliminato"]=$gg;
$rt["prog"]=prog();
repTV("blog",$rt);

