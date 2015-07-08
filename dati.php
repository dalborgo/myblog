<?php
/**
 * Created by IntelliJ IDEA.
 * User: Scuola
 * Date: 23/06/2015
 * Time: 18:12
 */
require_once "librerie/fetch.php";
require_once "librerie/date.php";
require_once "librerie/sql.php";

header('Content-Type: application/json');
$res = query("SELECT * from aa_player WHERE abil IS NULL");
$r=0;
$lista2="";
$status = array();
$squadra = array();
while (($ra = mysql_fetch_assoc($res))) {
    $val = $ra["nick"];
    $status[$val] = $ra["status"];
    $squadra[$val] = $ra["squadra"];

    $r++;
    $lista2.="network$r=PlayerGroup&player$r=$val&";
}
$lista2=substr($lista2,0,-1);
$service_url = 'http://www.sharkscope.com/api/dalborgo/activeTournaments?'.$lista2;
$decoded=ccall($service_url);
$abbin = array();
$ltot=$decoded->Response->PlayerResponse->PlayerView;

foreach ($ltot as $key => $gioc2) {
    if (!isset($gioc2->PlayerGroup->ActiveTournaments->Tournament))
    {
        $opz = null;
    }else {
        $opz = $gioc2->PlayerGroup->ActiveTournaments->Tournament;
    }
    if(!$opz)
        continue;
    $nick=$gioc2->PlayerGroup->{'@name'};
    $out = array();
    $obj = new stdClass();
    $obj->nick=$nick;
    $obj->squadra=$squadra[$nick];

    $obj->status=$status[$nick];
    $obj->form='<img src="http://www.dalborgo.it/public/ss/'.$obj->status.'.png" style="vertical-align:middle"><span> '.$obj->nick.'</span>';
    $obj->sqf='<img src="http://www.dalborgo.com/ryder/media/images/'.$obj->squadra.'_flag.gif" style="vertical-align:middle;border:1px solid gray" >';
    if($obj->squadra=="usa") {
        $obj->formUsc = '[img]http://www.dalborgo.it/public/ss/' . $obj->status . '.png[/img] [url=http://it.pokerstrategy.com/user/' . $obj->nick . '/profile/][size=22][b]' . $obj->nick . '[/b][/size][/url]';
        $obj->sqUsc='[img]http://www.dalborgo.com/ryder/media/images/'.$obj->squadra.'_flag.gif[/img] [size=22][color=#690000][b]Team USA[/b][/color][/size]';
    }
    else {
        $obj->formUsc = '[img]http://www.dalborgo.it/public/ss/' . $obj->status . '.png[/img] [url=http://it.pokerstrategy.com/user/' . $obj->nick . '/profile/][size=22][b]' . $obj->nick . '[/b][/size][/url]';
        $obj->sqUsc='[img]http://www.dalborgo.com/ryder/media/images/'.$obj->squadra.'_flag.gif[/img] [size=22][color=#1F4594][b]Team USA[/b][/color][/size]';
    }
    $cont=0;
    foreach ($gioc2->PlayerGroup->ActiveTournaments->Tournament as $key => $value) {
        $butt="";
        $cf="";
        $cont++;
        $obj2 = new stdClass();
        if (count($gioc2->PlayerGroup->ActiveTournaments->Tournament)<2)
            $value=$gioc2->PlayerGroup->ActiveTournaments->Tournament;
        if($value->{'@gameClass'}!="scheduled" || ($value->{'@state'}!="Running" && $value->{'@state'}!="Late Registration"))
        continue;
            //if($value->{'@gameClass'}!="scheduled" || $value->{'@state'}!="Registering")



        $datec="";
        try {
            $datec = new DateTime("@".$value->{'@scheduledStartDate'});
            $datec->setTimezone(new DateTimeZone('Europe/Rome'));
            $datec = $datec->format('d/m/Y (H:i)');
        } catch (Exception $e) {

        }
        $obj2->nictav='[size=18]'.$value->TournamentEntry->Player->{'@name'}.'[/size]';
        $obj2->data=$datec;
        if($value->{'@network'}=="PokerStars.it") {
            $nomepul=$value->{'@name'};
            $nome = "<span><a href='pokerstarsit://tournament/" . $value->{'@id'} . "/'>" . $value->{'@name'} . "</a></span>";
            $link='pokerstarsit://tournament/' . $value->{'@id'} . '/';
            $obj2->net='<img src="http://www.dalborgo.it/public/ss/ps1.png"> '.$value->{'@network'};
            $obj2->netUsc='[img]http://www.dalborgo.it/public/ss/ps1.png[/img]'.$value->{'@network'};
            $obj2->nomeUsc='[B][color=blue]'.$nomepul.'[/color][/B] - [B]'.$obj2->netUsc.'[/B]';
            $obj2->linkUsc='[IMG]http://resources.pokerstrategy.com/Editorial/elements/ps_image_arrow_link.png[/IMG] [B][URL=http://www.dalborgo.com/shark/toPok.php?id='.$value->{'@id'}.']Vai al tavolo![/URL][/B]';
        }else if($value->{'@network'}=="iPoker.it"){
            $nome = "<span>" . $value->{'@name'} . "</span>";
            $nomepul=$value->{'@name'};
            $link="";
            $obj2->linkUsc="";
            $obj2->net='<img src="http://www.dalborgo.it/public/ss/tit.png"> '.$value->{'@network'};
            $obj2->netUsc='[img]http://www.dalborgo.it/public/ss/tit.png[/img] '.$value->{'@network'};
            $obj2->nomeUsc='[B][color=blue]'.$nomepul.'[/color][/B] - [B]'.$obj2->netUsc.'[/B]';
        }else if($value->{'@network'}=="MicroGame"){
            $nomepul=$value->{'@name'};
            $nome = "<span>" . $value->{'@name'} . "</span>";
            $link="";
            $obj2->linkUsc="";
            $obj2->net='<img src="http://www.dalborgo.it/public/ss/all.png"> '.$value->{'@network'};
            $obj2->netUsc='[img]http://www.dalborgo.it/public/ss/all.png[/img] '.$value->{'@network'};
            $obj2->nomeUsc='[B][color=blue]'.$nomepul.'[/color][/B] - [B]'.$obj2->netUsc.'[/B]';
        }
        else {
            $nome = "<span>" . $value->{'@name'} . "</span>";
            $obj2->net=$value->{'@network'};
        }
        $obj2->id=$value->{'@id'};
        $obj2->nome=$nome;
        $obj2->nomepul=$nomepul;
        $obj2->link=$link;
        //echo "<td>".$ent."</td>";
        $obj2->ent=$value->{'@totalEntrants'};
        $obj2->buyin=round(($value->{'@stake'}+$value->{'@rake'}));
      /* echo "<td class='sright'>".$value->{'@network'}."</td>
       <td class='sright'>".round(($value->{'@stake'}+$value->{'@rake'}))."</td>
       <td class='sright'>".$str."</td>
       <td class='sright'>".$value->{'@totalEntrants'}."</td>";*/
        $out[]=$obj2;
        if (count($gioc2->PlayerGroup->ActiveTournaments->Tournament)<2)
            break;
    }
    $obj->cont=$cont;
    $obj->partite = $out;
    if(count($out)>0)
        $abbin[]=$obj;
}
$usc2 = new stdClass();
$usc2->data = $abbin;
echo json_encode($usc2);