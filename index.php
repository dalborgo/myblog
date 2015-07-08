<?php
/**
 * Created by IntelliJ IDEA.
 * User: Scuola
 * Date: 20/06/2015
 * Time: 01:32
 */

?>

<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>MyBlog</title>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/plug-ins/1.10.7/integration/jqueryui/dataTables.jqueryui.css">
    <link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" charset="utf8" src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="http://zeroclipboard.org/javascripts/zc/v2.1.6/ZeroClipboard.js"></script>
    <script type="text/javascript" language="javascript" class="init">
        var output="";
        var hack=new Array();
        function canc(i){
            if($('.'+i).css("opacity")<1) {
                $('.' + i).fadeTo("fast", 1, function() {
                    $('#b' + i).text('Togli');
                });

            }
            else {
                $('.' + i).fadeTo("fast", 0.13, function() {
                    $('#b' + i).text('Vedi');
                });
            }

        }

        function creaPos(n,i,t){
            r=(!isNaN(t.value))?t.value:'';
            hack[n][i].pos= r;
            creaOu(n);
        }
        function creaRes(n,i,t){
            r=(!isNaN(t.value))?t.value:'';
            r=(r<hack[n][i].pos)?hack[n][i].pos:r;
            hack[n][i].res= r;
            creaOu(n);
        }
        function creaSoldi(n,i,t){
            var regex = /^[0-9.,]+$/;
            if( !regex.test(t.value) ) {
                hack[n][i].soldi= '';
            }else
                hack[n][i].soldi= t.value;
            creaOu(n);
        }
        function vai(){
            window.open(
                document.referrer+'#quickedit',
                '_parent' // <- This is what makes it open in a new window.
            );
        }
        function creaOu(n) {
            output = "";
            yu = 0;
            duo = 0;
            chiul = 0;
            for (var obj in hack[n]) {
                oi = hack[n][obj];
                if (yu == 0) {
                    var local = new Date();
                    ecco=('0'+local.getHours()).substr(-2);
                    ecco2=('0'+local.getMinutes()).substr(-2);
                    var localdatetime = ecco + ":" + ecco2;
                    ora ='[size=18][b]'+localdatetime+'[/B][/size]';
                    output +=oi.sqUsc+' ('+ora+')\n' + oi.formUsc + ' (' + oi.nictav + ')\n\n';
                    yu = 1;

                }
                if (oi.pos != "" || oi.soldi != "") {
                    if(duo==0) {
                        output += '[list]';
                        duo=1;
                        chiul=1;
                    }
                    if (oi.linkUsc != "")
                        output +='[*][size=17]'+oi.nomeUsc + ' ' + oi.linkUsc + '[/size]\n';
                    else
                        output +='[*][size=17]'+oi.nomeUsc + '[/size]\n';


                    if (oi.soldi != "") {
                        if(oi.pos!="")
                            output += '[B][size=22]FINITO:[color=#FF8400] ' + oi.pos +'\u00B0 - \u20AC' + oi.soldi + '[/color][/size][/B]\n\n';
                        else
                            output += '[B][size=22]FINITO:[color=#FF8400] \u20AC' + oi.soldi + '[/color][/size][/B]\n\n';

                    } else {
                        if(oi.res!="")
                            output += '[B][size=22]' + oi.pos + ' / ' + oi.res + '[/size][/B]\n\n';
                        else
                            output += '[B][size=22]' + oi.pos +'\u00B0[/size][/B]\n\n';
                    }
                }
            }
            if(chiul==1)
                output+="[/list]";
            $('#' + n).attr('data-clipboard-text', output);
        }
        function format ( d ) {
            var hus=new Array();
            nj=d.nick;
            var ost = '<div class="slider"><table class="display sotto" cellspacing="0" border="0" style="width:100%">';
            ost+='<thead><tr><th>Data</th><th>Nome</th><th>Poker Room</th><th>&euro; Buyin</th><th>Pos/Res</th><th>&euro; Soldi</th><th>Eliminato?</th></tr></thead>';
            for (ind = 0; ind < d.partite.length; ind++) {
                stri=d.nick+d.partite[ind].id;
                ost+='<tr>'+
                    '<td class="'+stri+'">'+d.partite[ind].data+'</td>'+
                    '<td class="'+stri+'">'+d.partite[ind].nome+'</td>'+
                    '<td class="'+stri+'">'+d.partite[ind].net+'</td>'+
                    '<td class="sright '+stri+'">'+d.partite[ind].buyin+'</td>'+
                    '<td class="center '+stri+'"><input type="text" size="4" onchange="creaPos(\''+nj+'\','+d.partite[ind].id+',this)"> / <input type="text" id="res'+stri+'" name="res'+stri+'" size="4" onchange="creaRes(\''+nj+'\','+d.partite[ind].id+',this)"></td>'+
                    '<td class="center '+stri+'"><input type="text" size="6" onchange="creaSoldi(\''+nj+'\','+d.partite[ind].id+',this)" ></td>'+
                    '<td class="center"><button id="b'+stri+'" onclick="canc(\''+stri+'\')">Togli</button></td>'+
                    '</tr>';
                    hus[d.partite[ind].id] = { "nome":d.partite[ind].nome, "net":d.partite[ind].net,"nomepul":d.partite[ind].nomepul,"pos":"","res":"","soldi":"","nictav":d.partite[ind].nictav,"formUsc":d.formUsc,"sqUsc":d.sqUsc,"nomeUsc":d.partite[ind].nomeUsc,"linkUsc":d.partite[ind].linkUsc};
            }
            hack[d.nick]=hus;
            //output+=hack[d.nick][1267081278].nomepul;
            ost+='<tr><td></td><td></td><td></td><td></td><td></td><td class="center" style=""><button class="submit-btn" id="'+ d.nick+'" data-clipboard-text="'+output+'" onclick="vai()">Esporta</button></td><td></td></tr>';
            ost+='</table></div>';


            return ost;
        }

        $(document).ready(function () {

            var table=$('#example').DataTable({
                "ajax": "dati.php",
                "columns": [
                    {
                        "className":'details-control',
                        "orderable":      false,
                        "data":           null,
                        "defaultContent": ''
                    },
                    { "data": "form" },
                    { "data": "sqf" },
                    { "data": "cont" }
                ],
                "jQueryUI":       true,
                "aoColumnDefs": [
                    { "sClass": "", "aTargets": [ 1] },
                    { "sClass": "center", "aTargets": [ 2] },
                    { "sClass": "center", "aTargets": [ 3] }
                    /*{ "sClass": "bold center", "aTargets": [ 3 ] },
                    { "sClass": "bold center usa", "aTargets": [ 4 ] },
                    { "sClass": "center", "aTargets": [ 2] },
                    { "sClass": "center", "aTargets": [ 5] }*/
                ],
                "order": [[1, 'asc']],
                "oLanguage": {
                    "sUrl": "./lang/ita.txt"
                },
                "paging": false,
                "bSort" : false,
                "pagingType": "full",
                "searching": true,
                "bLengthChange": false,
                "searching": false,
                "pageLength": 30,
                "fnInitComplete" : function () {   var json = table.ajax.json();  },
                "dom": '<"top"iflp<"clear">>rt<"bottom"il<"clear">>',
                "scrollCollapse": true,
                columnDefs: [
                    {type: 'date-uk', targets: 0}
                ]
            });



            $('#example tbody').on('click', 'td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );

                if ( row.child.isShown() ) {
                    // This row is already open - close it

                    $('div.slider', row.child()).slideUp( function () {                row.child.hide();                tr.removeClass('shown');            } );


                }
                else {
                    // Open this row
                    row.child( format(row.data()), 'no-padding' ).show();            tr.addClass('shown');             $('div.slider', row.child()).slideDown();
                    //alert(row.data().nick);
                    new ZeroClipboard( document.getElementById(row.data().nick));

                }
            } );
        } );
    </script>
</head>
<body id="mainB" class="piccolo2">

<table id="example" class="display" cellspacing="0" style="width:100%;">
    <thead class="">
    <tr>
        <th id="" class="" style=""></th>
        <th  style="">Nick</th>
        <th  style="">Squadra</th>
        <th  style="">Tornei</th>
        <!--<th id="" class=""></th>
        <th class=""><span>Team Europe</span> </th>
        <th class=""><span>Team USA</span> </th>
        <th id="" class=""><span></span></th>-->
    </tr>
    </thead>


</table>
<script>

</script>
</body>
</html>