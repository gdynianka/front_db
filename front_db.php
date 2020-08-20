<?php

// to jest plik bliblioteki FRONT_DB - nie edytować

if ($_GET['action']=='usun'){
    $json   =  file_get_contents($_POST['tabelka']);
    $array  =  json_decode($json,true);
    unset($array[$_POST['id']]);
    file_put_contents($_POST['tabelka'],prettyPrint(json_encode($array)));
    }
    
    
if ($_GET['action']=='zapisz'){
    $tabelka = $_POST['tabelka'];
    unset($_POST['tabelka']);
    $json   =  file_get_contents($tabelka);
    $array  =  json_decode($json,true);
    if (@!$_POST['id']) $_POST['id'] = time();
    $array[$_POST['id']] = $_POST;
    file_put_contents($tabelka,prettyPrint(json_encode($array)));
    }
    
    
if ($_GET['action']=='pobierz'){
    $tabelka = $_GET['tabelka'];
    $id      = @$_GET['id'];
    $json    =  file_get_contents($tabelka);

    if ($id)  { // wybrany wiersz
              $array  = json_decode($json,true);
              $one    = $array[$id];
              echo json_encode($one);
              }
              else
              {
              $pole   = @trim($_POST['pole']);
              $filter = @trim($_POST['filter']);

              if ($filter and $pole){
                                    // filtrujemy
                                    $array  = json_decode($json,true);
                                    foreach ($array as $id=>$row)
                                             if ( stripos(simplify($row[$pole]), $filter)===false) unset($array[$id]);
                                    echo json_encode($array);
                                    }
                                    else echo $json; // cała tabelka

               

              }
    }


// ***********************************************************************************************
// ***********************************************************************************************
// ***********************************************************************************************

function prettyPrint( $json )
{
    $result = '';
    $level = 0;
    $in_quotes = false;
    $in_escape = false;
    $ends_line_level = NULL;
    $json_length = strlen( $json );

    for( $i = 0; $i < $json_length; $i++ ) {
        $char = $json[$i];
        $new_line_level = NULL;
        $post = "";
        if( $ends_line_level !== NULL ) {
            $new_line_level = $ends_line_level;
            $ends_line_level = NULL;
        }
        if ( $in_escape ) {
            $in_escape = false;
        } else if( $char === '"' ) {
            $in_quotes = !$in_quotes;
        } else if( ! $in_quotes ) {
            switch( $char ) {
                case '}': case ']':
                    $level--;
                    $ends_line_level = NULL;
                    $new_line_level = $level;
                    break;

                case '{': case '[':
                    $level++;
                case ',':
                    $ends_line_level = $level;
                    break;

                case ':':
                    $post = " ";
                    break;

                case " ": case "\t": case "\n": case "\r":
                    $char = "";
                    $ends_line_level = $new_line_level;
                    $new_line_level = NULL;
                    break;
            }
        } else if ( $char === '\\' ) {
            $in_escape = true;
        }
        if( $new_line_level !== NULL ) {
            $result .= "\n".str_repeat( "\t", $new_line_level );
        }
        $result .= $char.$post;
    }

    return $result;
}


function simplify($imie){

$a1 = Array('ę','ó','ą','ś','ł','ż','ź','ć','ń');
$a2 = Array('Ę','Ó','Ą','Ś','Ł','Ż','Ź','Ć','Ń');
$a3 = Array('e','o','a','s','l','z','z','c','n');

$e1 = Array('\u0119','\u00f3','\u0105','\u015b','\u0142','\u017c','\u017a','\u0107','\u0144');
$e2 = Array('\u0118','\u00d3','\u0104','\u015a','\u0141','\u017b','\u0179','\u0106','\u0143');

$imie   = str_replace($a1,$a3,$imie);
$imie   = str_replace($a2,$a3,$imie);
$imie   = str_replace($e1,$a3,$imie);
$imie   = str_replace($e2,$a3,$imie);

$imie   = trim(strtolower($imie));
$imie   = preg_replace('/\s+/', ' ',$imie);
return $imie;
}




?>
