<?

	$o = SqlObjQuery("SELECT * FROM `planets` WHERE id = '{$_SESSION['planetid']}'");
	$erg = mysql_query("SELECT * FROM `buildings`");
	
	$buildings = array();
	
	while($out = mysql_fetch_object($erg)){
		$buildings[$out->intname]["intname"] = $out->intname;
		
		$buildings[$out->intname]["maxlevel"] = $out->maxlevel;
		$buildings[$out->intname]["Name"] = $out->Name;
		$buildings[$out->intname]["Stufe"] = $o->{$out->intname};
		
		$buildings[$out->intname]["Platin"] = $out->Platin;
		$buildings[$out->intname]["Energie"] = $out->Energie;
		$buildings[$out->intname]["Stahl"] = $out->Stahl;
		$buildings[$out->intname]["Plasma"] = $out->Plasma;
		$buildings[$out->intname]["Plasmid"] = $out->Plasmid;
		$buildings[$out->intname]["Nahrung"] = $out->Nahrung;
		
		$buildings[$out->intname]["Dauer"] = $out->Dauer;
		$buildings[$out->intname]["Faktor"]["Build"] = $out->FaktorBuild;
		$buildings[$out->intname]["Faktor"]["Time"] = $out->FaktorTime;
		
		$buildings[$out->intname]["Description"] = html_entity_decode( $out->Description, ENT_QUOTES );
		
		$builds[$out->intname] = &$buildings[$out->intname];
	}
	
	$buildings["reaktor"]["verhaeltnis"] = $o->reaktorplasmaverhaeltnis;
	
	$erg = mysql_query("SELECT * FROM `produktion`");
	while($out = mysql_fetch_object($erg)){
		$buildings["produktion"][$out->stufe] = $out->produktion;
		$lasts = $out->stufe;
	}
	$buildings["produktion"][$lasts + 1] = "Maximale Stufe erreicht";
	
	
	
	$erg = mysql_query("SELECT * FROM `lagermenge`");
	while($out = mysql_fetch_object($erg)){
		$buildings["lagermenge"][$out->stufe] = $out->lagermenge;
		$lasts = $out->stufe;
	}
	$buildings["lagermenge"][$lasts + 1] = "Maximale Stufe erreicht";
	
	
	
	$erg = mysql_query("SELECT * FROM `versorgteplaetze`");
	while($out = mysql_fetch_object($erg)){
		$buildings["versorgtePlaetze"][$out->stufe] = $out->versorgtePlaetze;
		$lasts = $out->stufe;
	}
	$buildings["versorgtePlaetze"][$lasts + 1] = "Maximale Stufe erreicht";
	
	
	$erg = mysql_query("SELECT * FROM `schild`");
	while($out = mysql_fetch_object($erg)){
		$buildings["schild"][$out->stufe]["grundverteidigung"] = $out->grundverteidigung;
		$buildings["schild"][$out->stufe]["bonus"] = $out->bonus;
		$lasts = $out->stufe;
	}
	$buildings["schild"][$lasts + 1]["grundverteidigung"] = "Maximale Stufe erreicht";
	$buildings["schild"][$lasts + 1]["bonus"] = "Maximale Stufe erreicht";
	
	
	/*
	$buildings["senat"]["pic"]["datei"]					= "pics\\senat";
	$buildings["senat"]["pic"]["ext"]					= ".png";
	$buildings["senat"]["intname"] 						= "senat";
	
	$buildings["senat"]["pic"]["1"]						= 5;
	$buildings["senat"]["pic"]["2"]						= 10;
	$buildings["senat"]["pic"]["3"]						= 20;
	
	$buildings["senat"]["maxlevel"]						= 25;
	$buildings["senat"]["Name"]							= "Interplanetarischer Senat";
	$buildings["senat"]["Stufe"]						= $out->senat;
	
	$buildings["senat"]["Platin"]						= 80;
	$buildings["senat"]["Energie"]						= 60;
	$buildings["senat"]["Stahl"]						= 100;
	$buildings["senat"]["Plasma"]						= 30;
	$buildings["senat"]["Plasmid"]						= 0;
	$buildings["senat"]["Nahrung"]						= 100;
	
	$buildings["senat"]["Bevoelkerung"]					= 5;
	$buildings["senat"]["Dauer"]						= 5;
	$buildings["senat"]["Faktor"]["Build"]				= 1.2;
	$buildings["senat"]["Faktor"]["Time"]				= 1.2;
	
	$buildings["senat"]["Description"]					= "Im Interplanetarischen Senat kannst du Gebäude ausbauen. Umso höher die Stufe des Interplanetarischen Senats ist, umso schneller kannst du Gebäude bauen.";
	
	
	
	$buildings["raumhafen"]["pic"]["datei"] 			= "pics\\raumhafen";
	$buildings["raumhafen"]["pic"]["ext"] 				= ".png";
	$buildings["raumhafen"]["intname"] 					= "raumhafen";
	
	$buildings["raumhafen"]["pic"]["1"] 				= 3;
	$buildings["raumhafen"]["pic"]["2"] 				= 7;
	$buildings["raumhafen"]["pic"]["3"] 				= 15;
	
	$buildings["raumhafen"]["maxlevel"] 				= 20;
	$buildings["raumhafen"]["Name"]						= "Militärstützpunkt";
	$buildings["raumhafen"]["Stufe"]					= $out->raumhafen;
	
	$buildings["raumhafen"]["Platin"] 					= 160;
	$buildings["raumhafen"]["Energie"] 					= 210;
	$buildings["raumhafen"]["Stahl"] 					= 180;
	$buildings["raumhafen"]["Plasma"] 					= 50;
	$buildings["raumhafen"]["Plasmid"] 					= 20;
	$buildings["raumhafen"]["Nahrung"] 					= 200;
	
	$buildings["raumhafen"]["Bevoelkerung"]				= 10;
	$buildings["raumhafen"]["Dauer"] 					= 15;
	$buildings["raumhafen"]["Faktor"]["Build"]			= 1.5;
	$buildings["raumhafen"]["Faktor"]["Time"]			= 1.3;
	
	$buildings["raumhafen"]["Description"]				= "Im Militärstützpunkt kannst du Einheiten ausbilden.";
	
	
	
	$buildings["schildgenerator"]["pic"]["datei"] 		= "pics\\schildgenerator";
	$buildings["schildgenerator"]["pic"]["ext"] 		= ".png";
	$buildings["schildgenerator"]["intname"] 			= "schildgenerator";
	
	$buildings["schildgenerator"]["pic"]["1"] 			= 2;
	$buildings["schildgenerator"]["pic"]["2"] 			= 5;
	$buildings["schildgenerator"]["pic"]["3"] 			= 8;
	
	$buildings["schildgenerator"]["maxlevel"] 			= 10;
	$buildings["schildgenerator"]["Name"]				= "Schildgenerator";
	$buildings["schildgenerator"]["Stufe"]				= $out->schildgenerator;
	
	$buildings["schildgenerator"]["Platin"] 			= 60;
	$buildings["schildgenerator"]["Energie"] 			= 200;
	$buildings["schildgenerator"]["Stahl"] 				= 50;
	$buildings["schildgenerator"]["Plasma"] 			= 200;
	$buildings["schildgenerator"]["Plasmid"] 			= 0;
	$buildings["schildgenerator"]["Nahrung"] 			= 0;
	
	$buildings["schildgenerator"]["Bevoelkerung"] 		= 8;
	$buildings["schildgenerator"]["Dauer"] 				= 14;
	$buildings["schildgenerator"]["Faktor"]["Build"]	= 1.8;
	$buildings["schildgenerator"]["Faktor"]["Time"]		= 1.3;
	
	$buildings["schildgenerator"]["Description"]		= "Der Schildgenerator erstellt einen starken Schutzschild um deinen Planet, welcher deinen Truppen bei der Verteidigung hilft.";
	
	
	
	$buildings["bauer"]["pic"]["datei"] 				= "pics\\bauer";
	$buildings["bauer"]["pic"]["ext"] 					= ".png";
	$buildings["bauer"]["intname"] 						= "bauer";
	
	$buildings["bauer"]["pic"]["1"] 					= 5;
	$buildings["bauer"]["pic"]["2"] 					= 15;
	$buildings["bauer"]["pic"]["3"] 					= 25;
	
	$buildings["bauer"]["maxlevel"] 					= 25;
	$buildings["bauer"]["Name"]							= "Farm";
	$buildings["bauer"]["Stufe"]						= $out->bauer;
	
	$buildings["bauer"]["Platin"] 						= 10;
	$buildings["bauer"]["Energie"] 						= 130;
	$buildings["bauer"]["Stahl"] 						= 100;
	$buildings["bauer"]["Plasma"] 						= 5;
	$buildings["bauer"]["Plasmid"] 						= 0;
	$buildings["bauer"]["Nahrung"] 						= 4;
	
	$buildings["bauer"]["Bevoelkerung"] 				= 0;
	$buildings["bauer"]["Dauer"] 						= 12;
	$buildings["bauer"]["Faktor"]["Build"]				= 1.3;
	$buildings["bauer"]["Faktor"]["Time"]				= 1.25;
	
	$buildings["bauer"]["Description"]					= "Die Farm sorgt für die Nahrung deines Planeten. Zusätzlich begrenzt sie die Anzahl der Truppen, die dein Planet maximal bauen darf.";
	
	
	
	$buildings["markt"]["pic"]["datei"] 				= "pics\\markt";
	$buildings["markt"]["pic"]["ext"] 					= ".png";
	$buildings["markt"]["intname"] 						= "markt";
	
	$buildings["markt"]["pic"]["1"] 					= 6;
	$buildings["markt"]["pic"]["2"] 					= 12;
	$buildings["markt"]["pic"]["3"] 					= 18;
	
	$buildings["markt"]["maxlevel"] 					= 20;
	$buildings["markt"]["Name"]							= "Handelszentrum";
	$buildings["markt"]["Stufe"]						= $out->markt;
	
	$buildings["markt"]["Platin"] 						= 50;
	$buildings["markt"]["Energie"] 						= 50;
	$buildings["markt"]["Stahl"] 						= 50;
	$buildings["markt"]["Plasma"] 						= 50;
	$buildings["markt"]["Plasmid"] 						= 50;
	$buildings["markt"]["Nahrung"] 						= 50;
	
	$buildings["markt"]["Bevoelkerung"] 				= 15;
	$buildings["markt"]["Dauer"] 						= 30;
	$buildings["markt"]["Faktor"]["Build"]				= 1.6;
	$buildings["markt"]["Faktor"]["Time"]				= 1.25;
	
	$buildings["markt"]["Description"]					= "Im Handelszentrum kannst du Rohstoffe an deine eigene Allianz verschicken.";
	
	
	
	$buildings["schmelze"]["pic"]["datei"] 				= "pics\\schmelze";
	$buildings["schmelze"]["pic"]["ext"] 				= ".png";
	$buildings["schmelze"]["intname"] 					= "schmelze";
	
	$buildings["schmelze"]["pic"]["1"] 					= 5;
	$buildings["schmelze"]["pic"]["2"] 					= 15;
	$buildings["schmelze"]["pic"]["3"] 					= 25;
	
	$buildings["schmelze"]["maxlevel"] 					= 25;
	$buildings["schmelze"]["Name"]						= "Eisenschmelze";
	$buildings["schmelze"]["Stufe"]						= $out->schmelze;
	
	$buildings["schmelze"]["Platin"] 					= 115;
	$buildings["schmelze"]["Energie"] 					= 75;
	$buildings["schmelze"]["Stahl"] 					= 6;
	$buildings["schmelze"]["Plasma"] 					= 5;
	$buildings["schmelze"]["Plasmid"] 					= 0;
	$buildings["schmelze"]["Nahrung"] 					= 125;
	
	$buildings["schmelze"]["Bevoelkerung"] 				= 7;
	$buildings["schmelze"]["Dauer"] 					= 8;
	$buildings["schmelze"]["Faktor"]["Build"]			= 1.3;
	$buildings["schmelze"]["Faktor"]["Time"]			= 1.25;
	
	$buildings["schmelze"]["Description"]				= "Die Eisenschmelze erstellt Stahl. Umso höher die Stufe der Eisenschmelze ist, desto mehr Stahl kann produziert werden.";
	
	
	
	$buildings["miene"]["pic"]["datei"] 				= "pics\\miene";
	$buildings["miene"]["pic"]["ext"] 					= ".png";
	$buildings["miene"]["intname"] 						= "miene";
	
	$buildings["miene"]["pic"]["1"] 					= 5;
	$buildings["miene"]["pic"]["2"] 					= 15;
	$buildings["miene"]["pic"]["3"] 					= 25;
	
	$buildings["miene"]["maxlevel"] 					= 25;
	$buildings["miene"]["Name"]							= "Platinmiene";
	$buildings["miene"]["Stufe"]						= $out->miene;
	
	$buildings["miene"]["Platin"]						= 4;
	$buildings["miene"]["Energie"] 						= 75;
	$buildings["miene"]["Stahl"]						= 115;
	$buildings["miene"]["Plasma"] 						= 5;
	$buildings["miene"]["Plasmid"] 						= 0;
	$buildings["miene"]["Nahrung"] 						= 125;
	
	$buildings["miene"]["Bevoelkerung"] 				= 7;
	$buildings["miene"]["Dauer"] 						= 8;
	$buildings["miene"]["Faktor"]["Build"]				= 1.3;
	$buildings["miene"]["Faktor"]["Time"]				= 1.25;
	
	$buildings["miene"]["Description"]					= "In der Platinmiene wird das wertvolle Platin gefördert. Umso höher die Stufe der Platinmiene ist, desto mehr Platin kann gefördert werden.";
	
	
	
	$buildings["reaktor"]["pic"]["datei"]				= "pics\\reaktor";
	$buildings["reaktor"]["pic"]["ext"] 				= ".png";
	$buildings["reaktor"]["intname"] 					= "reaktor";
	
	$buildings["reaktor"]["pic"]["1"] 					= 5;
	$buildings["reaktor"]["pic"]["2"] 					= 15;
	$buildings["reaktor"]["pic"]["3"] 					= 25;
	
	$buildings["reaktor"]["maxlevel"] 					= 25;
	$buildings["reaktor"]["Name"]						= "Reaktor";
	$buildings["reaktor"]["Stufe"]						= $out->reaktor;
	$buildings["reaktor"]["verhaeltnis"]				= $out->reaktorplasmaverhaeltnis;
	
	$buildings["reaktor"]["Platin"] 					= 150;
	$buildings["reaktor"]["Energie"] 					= 6;
	$buildings["reaktor"]["Stahl"] 						= 175;
	$buildings["reaktor"]["Plasma"] 					= 8;
	$buildings["reaktor"]["Plasmid"] 					= 0;
	$buildings["reaktor"]["Nahrung"] 					= 190;
	
	$buildings["reaktor"]["Bevoelkerung"] 				= 10;
	$buildings["reaktor"]["Dauer"] 						= 12;
	$buildings["reaktor"]["Faktor"]["Build"]			= 1.3;
	$buildings["reaktor"]["Faktor"]["Time"]				= 1.25;
	
	$buildings["reaktor"]["Description"]				= "Im Reaktor werden Plasma und Energie erzeugt. Umso höher die Stufe des Reaktors ist, desto mehr Plasma und Energie kann erzeugt werden.";
	
	
	
	$buildings["biolabor"]["pic"]["datei"] 				= "pics\\biolabor";
	$buildings["biolabor"]["pic"]["ext"] 				= ".png";
	$buildings["biolabor"]["intname"] 					= "biolabor";
	
	$buildings["biolabor"]["pic"]["1"] 					= 5;
	$buildings["biolabor"]["pic"]["2"] 					= 15;
	$buildings["biolabor"]["pic"]["3"] 					= 25;
	
	$buildings["biolabor"]["maxlevel"] 					= 25;
	$buildings["biolabor"]["Name"]						= "Biolabor";
	$buildings["biolabor"]["Stufe"]						= $out->biolabor;
	
	$buildings["biolabor"]["Platin"] 					= 100;
	$buildings["biolabor"]["Energie"] 					= 75;
	$buildings["biolabor"]["Stahl"] 					= 100;
	$buildings["biolabor"]["Plasma"] 					= 20;
	$buildings["biolabor"]["Plasmid"] 					= 50;
	$buildings["biolabor"]["Nahrung"] 					= 200;
	
	$buildings["biolabor"]["Bevoelkerung"] 				= 10;
	$buildings["biolabor"]["Dauer"] 					= 10;
	$buildings["biolabor"]["Faktor"]["Build"]			= 1.3;
	$buildings["biolabor"]["Faktor"]["Time"]			= 1.25;
	
	$buildings["biolabor"]["Description"]				= "Im Biolabor wird das Plasmid produziert. Umso höher die Stufe des Biolabors ist, desto mehr Plasmid kann produziert werden.";
	
	
	
	$buildings["lagerhalle"]["pic"]["datei"] 			= "pics\\lagerhalle";
	$buildings["lagerhalle"]["pic"]["ext"] 				= ".png";
	$buildings["lagerhalle"]["intname"] 				= "lagerhalle";
	
	$buildings["lagerhalle"]["pic"]["1"] 				= 5;
	$buildings["lagerhalle"]["pic"]["2"] 				= 15;
	$buildings["lagerhalle"]["pic"]["3"] 				= 25;
	
	$buildings["lagerhalle"]["maxlevel"] 				= 35;
	$buildings["lagerhalle"]["Name"]					= "Lagerhalle";
	$buildings["lagerhalle"]["Stufe"]					= $out->lagerhalle;
	
	$buildings["lagerhalle"]["Platin"] 					= 42;
	$buildings["lagerhalle"]["Energie"] 				= 125;
	$buildings["lagerhalle"]["Stahl"] 					= 142;
	$buildings["lagerhalle"]["Plasma"] 					= 0;
	$buildings["lagerhalle"]["Plasmid"] 				= 0;
	$buildings["lagerhalle"]["Nahrung"] 				= 150;
	
	$buildings["lagerhalle"]["Bevoelkerung"] 			= 0;
	$buildings["lagerhalle"]["Dauer"] 					= 21;
	$buildings["lagerhalle"]["Faktor"]["Build"]			= 1.2;
	$buildings["lagerhalle"]["Faktor"]["Time"]			= 1.15;
	
	$buildings["lagerhalle"]["Description"]				= "In deiner Lagerhalle kannst du deine Rohstoffe lagern. Umso höher die Stufe der Lagerhalle, desto mehr Rohstoffe kannst du Lagern.";
	
	
	
	
	
	
	
	
	
	
	
	$buildings["produktion"][0]							= 10;
	$buildings["produktion"][1]							= 30;
	$buildings["produktion"][2]							= 39;
	$buildings["produktion"][3]							= 51;
	$buildings["produktion"][4]							= 66;
	$buildings["produktion"][5]							= 86;
	$buildings["produktion"][6]							= 114;
	$buildings["produktion"][7]							= 147;
	$buildings["produktion"][8]							= 185;
	$buildings["produktion"][9]							= 225;
	$buildings["produktion"][10]						= 277;
	$buildings["produktion"][11]						= 327;
	$buildings["produktion"][12]						= 380;
	$buildings["produktion"][13]						= 450;
	$buildings["produktion"][14]						= 527;
	$buildings["produktion"][15]						= 600;
	$buildings["produktion"][16]						= 678;
	$buildings["produktion"][17]						= 762;
	$buildings["produktion"][18]						= 892;
	$buildings["produktion"][19]						= 1033;
	$buildings["produktion"][20]						= 1180;
	$buildings["produktion"][21]						= 1440;
	$buildings["produktion"][22]						= 1730;
	$buildings["produktion"][23]						= 2075;
	$buildings["produktion"][24]						= 2490;
	$buildings["produktion"][25]						= 3000;
	$buildings["produktion"][26]						= "Maximale Stufe erreicht";
	
	
	
	$buildings["lagermenge"][0]							= 1500;
	$buildings["lagermenge"][1]							= 1800;
	$buildings["lagermenge"][2]							= 2160;
	$buildings["lagermenge"][3]							= 2600;
	$buildings["lagermenge"][4]							= 3110;
	$buildings["lagermenge"][5]							= 3730;
	$buildings["lagermenge"][6]							= 4480;
	$buildings["lagermenge"][7]							= 5375;
	$buildings["lagermenge"][8]							= 6450;
	$buildings["lagermenge"][9]							= 7740;
	$buildings["lagermenge"][10]						= 8515;
	$buildings["lagermenge"][11]						= 9365;
	$buildings["lagermenge"][12]						= 10300;
	$buildings["lagermenge"][13]						= 11330;
	$buildings["lagermenge"][14]						= 12465;
	$buildings["lagermenge"][15]						= 13710;
	$buildings["lagermenge"][16]						= 15080;
	$buildings["lagermenge"][17]						= 16590;
	$buildings["lagermenge"][18]						= 18250;
	$buildings["lagermenge"][19]						= 20075;
	$buildings["lagermenge"][20]						= 22080;
	$buildings["lagermenge"][21]						= 24290;
	$buildings["lagermenge"][22]						= 26720;
	$buildings["lagermenge"][23]						= 29390;
	$buildings["lagermenge"][24]						= 32330;
	$buildings["lagermenge"][25]						= 48500;
	$buildings["lagermenge"][26]						= 72750;
	$buildings["lagermenge"][27]						= 109120;
	$buildings["lagermenge"][28]						= 163675;
	$buildings["lagermenge"][29]						= 245510;
	$buildings["lagermenge"][30]						= 350000;
	$buildings["lagermenge"][31]						= 400000;
	$buildings["lagermenge"][32]						= 500000;
	$buildings["lagermenge"][33]						= 600000;
	$buildings["lagermenge"][34]						= 700000;
	$buildings["lagermenge"][35]						= 800000;
	$buildings["lagermenge"][36]						= "Maximale Stufe erreicht";
	
	
	
	$buildings["versorgtePlaetze"][0]					= 200;
	$buildings["versorgtePlaetze"][1]					= 240;
	$buildings["versorgtePlaetze"][2]					= 288;
	$buildings["versorgtePlaetze"][3]					= 346;
	$buildings["versorgtePlaetze"][4]					= 415;
	$buildings["versorgtePlaetze"][5]					= 500;
	$buildings["versorgtePlaetze"][6]					= 600;
	$buildings["versorgtePlaetze"][7]					= 715;
	$buildings["versorgtePlaetze"][8]					= 860;
	$buildings["versorgtePlaetze"][9]					= 1030;
	$buildings["versorgtePlaetze"][10]					= 1240;
	$buildings["versorgtePlaetze"][11]					= 1460;
	$buildings["versorgtePlaetze"][12]					= 1780;
	$buildings["versorgtePlaetze"][13]					= 2140;
	$buildings["versorgtePlaetze"][14]					= 2575;
	$buildings["versorgtePlaetze"][15]					= 3080;
	$buildings["versorgtePlaetze"][16]					= 3700;
	$buildings["versorgtePlaetze"][17]					= 4437;
	$buildings["versorgtePlaetze"][18]					= 5325;
	$buildings["versorgtePlaetze"][19]					= 6390;
	$buildings["versorgtePlaetze"][20]					= 7675;
	$buildings["versorgtePlaetze"][21]					= 9200;
	$buildings["versorgtePlaetze"][22]					= 11040;
	$buildings["versorgtePlaetze"][23]					= 13250;
	$buildings["versorgtePlaetze"][24]					= 15900;
	$buildings["versorgtePlaetze"][25]					= 19000;
	$buildings["versorgtePlaetze"][26]					= "Maximale Stufe erreicht";
	
	
	
	$buildings["schild"][0]["grundverteidigung"] 		= 10;
	$buildings["schild"][0]["bonus"] 					= 0;
	$buildings["schild"][1]["grundverteidigung"] 		= 15;
	$buildings["schild"][1]["bonus"] 					= 4;
	$buildings["schild"][2]["grundverteidigung"] 		= 22;
	$buildings["schild"][2]["bonus"] 					= 9;
	$buildings["schild"][3]["grundverteidigung"] 		= 34;
	$buildings["schild"][3]["bonus"] 					= 15;
	$buildings["schild"][4]["grundverteidigung"] 		= 50;
	$buildings["schild"][4]["bonus"] 					= 22;
	$buildings["schild"][5]["grundverteidigung"] 		= 75;
	$buildings["schild"][5]["bonus"] 					= 30;
	$buildings["schild"][6]["grundverteidigung"] 		= 114;
	$buildings["schild"][6]["bonus"] 					= 39;
	$buildings["schild"][7]["grundverteidigung"] 		= 170;
	$buildings["schild"][7]["bonus"] 					= 49;
	$buildings["schild"][8]["grundverteidigung"] 		= 256;
	$buildings["schild"][8]["bonus"] 					= 60;
	$buildings["schild"][9]["grundverteidigung"] 		= 512;
	$buildings["schild"][9]["bonus"] 					= 72;
	$buildings["schild"][10]["grundverteidigung"] 		= 1024;
	$buildings["schild"][10]["bonus"] 					= 85;
	$buildings["schild"][11]["grundverteidigung"] 		= "Maximale Stufe erreicht";
	$buildings["schild"][11]["bonus"] 					= "Maximale Stufe erreicht";
	
	
	$builds = array(
		"senat" => &$buildings["senat"],
		"militaer" => &$buildings["raumhafen"],
		"schildgenerator" => &$buildings["schildgenerator"],
		"bauer" => &$buildings["bauer"],
		"markt" => &$buildings["markt"],
		"schmelze" => &$buildings["schmelze"],
		"miene" => &$buildings["miene"],
		"reaktor" => &$buildings["reaktor"],
		"biolabor" => &$buildings["biolabor"],
		"lagerhalle" => &$buildings["lagerhalle"]
	);
	
	*/
	
	$ress["Platin"] 									= (((int)($o->platin/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($o->platin/1000);
	$ress["Energie"] 									= (((int)($o->energie/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($o->energie/1000);
	$ress["Stahl"] 										= (((int)($o->stahl/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($o->stahl/1000);
	$ress["Plasma"] 									= (((int)($o->plasma/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($o->plasma/1000);
	$ress["Plasmid"] 									= (((int)($o->plasmid/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($o->plasmid/1000);
	$ress["Nahrung"] 									= (((int)($o->nahrung/1000))>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]])?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]:(int)($o->nahrung/1000);
	
	$ressReal["Platin"] 									= (($o->platin)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($o->platin);
	$ressReal["Energie"] 									= (($o->energie)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($o->energie);
	$ressReal["Stahl"] 										= (($o->stahl)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($o->stahl);
	$ressReal["Plasma"] 									= (($o->plasma)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($o->plasma);
	$ressReal["Plasmid"] 									= (($o->plasmid)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($o->plasmid);
	$ressReal["Nahrung"] 									= (($o->nahrung)>$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000)?$buildings["lagermenge"][$buildings["lagerhalle"]["Stufe"]]*1000:($o->nahrung);
	

	
?>