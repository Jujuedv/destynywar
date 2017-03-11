<?php
/*////////////////////////////////////////////////////////////
//	Diese Einstellungen anstatt der .PHP  Dateien #ndern	//
////////////////////////////////////////////////////////////*/

$settings = array();

//In welchem Ordner liegen die .PHP Dateien?
// "" steht dafür, dass es direkt im root verzeichniss liegt.
// Dieser Inhalt hat am Ende ein "/" es seiden er ist leer.
$settings['rootfolder'] = "";

//Wie schnell werden Einheiten rekrutiert?
// 1 ist der Standartwert
// Umso höher dieser Wert ist, desto schneller werden Einheiten rekrutiert.
$settings['recruitspeed'] = 1;

//In diesem Array steht die Datenbank, ihr Passwort und der Server
$settings['database'] = array();
$settings['database']["server"]		= "localhost";
$settings['database']["user"]		= "root";
$settings['database']["passwort"]	= "<password>";
$settings['database']["db"]			= "dw".$settings['rootfolder'];

//Wie schnell greifen Einheiten an?
// 1 ist der Standartwert
// Umso höher dieser Wert ist, desto schneller greifen Einheiten an.
$settings['attackspeed'] = 1;


//Wie schnell werden Gebäude gebaut?
// 1 ist der Standartwert
// Umso höher dieser Wert ist, desto schneller werden Gebäude gebaut.
$settings['buildspeed'] = 1;


//Wie schnell werden Rohstoffe produziert?
// 1 ist der Standartwert
// Umso höher dieser Wert ist, desto schneller werden Rohstoffe produziert.
$settings['speed'] = 1;


$settings['stufe322']= 0;
$settings['stufe221']= 0;


//Wie viele Planeten soll jeder Spieler mindestens haben?
$settings['minplanets'] = 3;


$databasesettingssave = array();

$databasesettingssave[] = 'buildspeed';
$databasesettingssave[] = 'attackspeed';
$databasesettingssave[] = 'recruitspeed';
$databasesettingssave[] = 'speed';
$databasesettingssave[] = 'stufe322';
$databasesettingssave[] = 'stufe221';
$databasesettingssave[] = 'minplanets';



?>
