// Destyny War - Ereignissschleife.cpp : Definiert den Einstiegspunkt für die Konsolenanwendung.
//
#include <c:\Users\julian\Desktop\mysql++-3.0.9\lib\mysql++.h>

#include <iostream>
#include <iomanip>
#include <process.h>

using namespace std;
#define elif else if
#define elseif elif

#pragma warning	(disable : 4390)
#undef MessageBox
#define MessageBox(a,b,c,d)
#define MessageBoxA(a,b,c,d)
struct Jmutex {
	HANDLE id;
};

/* Create a mutex */
Jmutex *CreateMutex(void)
{
	Jmutex *mutex;

	/* Allocate mutex memory */
	mutex = (Jmutex*)malloc(sizeof(*mutex));
	if ( mutex ) {
		/* Create the mutex, with initial value signaled */
		mutex->id = CreateMutex(NULL, FALSE, NULL);
		if ( ! mutex->id ) {
			free(mutex);
			mutex = NULL;
		}
	} else {
	}
	return(mutex);
}


string nts(int in){
	char out[32];
	sprintf_s(out,"%d",in);
	return string(out);
}  

string tts(time_t in){
	char out[32];
	sprintf_s(out,"%d",in);
	return string(out);
}

/* Free the mutex */
void DestroyMutex(Jmutex *mutex)
{
	if ( mutex ) {
		if ( mutex->id ) {
			CloseHandle(mutex->id);
			mutex->id = 0;
		}
		free(mutex);
	}
}

/* Lock the mutex */
int mutexP(Jmutex *mutex)
{
	if ( mutex == NULL ) {
		return -1;
	}
	if ( WaitForSingleObject(mutex->id, INFINITE) == WAIT_FAILED ) {
		return -1;
	}
	return(0);
}

/* Unlock the mutex */
int mutexV(Jmutex *mutex)
{
	if ( mutex == NULL ) {
		return -1;
	}
	if ( ReleaseMutex(mutex->id) == FALSE ) {
		return -1;
	}
	return(0);
}


int round(double In){
	int tmp = In;
	if(In-tmp >=  0.1) tmp++;
	return tmp;
}



const char* db = "dw", *server = 0, *user = "root", *pass = "jgames";
mysqlpp::Connection conn(false);
Jmutex *connm;

void erhoehen(void *){
	try{
		time_t now = time(NULL);
		char num[20];
		sprintf_s(num,"'%d'",now);
		mutexP(connm);
		mysqlpp::Query query = conn.query((string)"UPDATE `planets` SET `Platin` =  (`PPlatin`/3.6*("+num+"-lastplus)*(SELECT wert FROM gamesettings WHERE einstellung = 'speed'))+`Platin`,`Plasma` =  (`PPlasma`/3.6*("+num+"-lastplus)*(reaktorplasmaverhaeltnis/50)*(SELECT wert FROM gamesettings WHERE einstellung = 'speed'))+`Plasma`,`Energie` =  (`PEnergie`/3.6*("+num+"-lastplus)*((100-reaktorplasmaverhaeltnis)/50)*(SELECT wert FROM gamesettings WHERE einstellung = 'speed'))+`Energie`,`Plasmid` =  (`PPlasmid`/3.6*("+num+"-lastplus)*(SELECT wert FROM gamesettings WHERE einstellung = 'speed'))+`Plasmid`,`Nahrung` =  (`PNahrung`/3.6*("+num+"-lastplus)*(SELECT wert FROM gamesettings WHERE einstellung = 'speed'))+`Nahrung`,`Stahl` =  (`PStahl`/3.6*("+num+"-lastplus)*(SELECT wert FROM gamesettings WHERE einstellung = 'speed'))+`Stahl`, lastplus = "+num+";");
		query.exec();
		mutexV(connm);
	}catch(std::exception){}
}
void erhoeher(void *){
	time_t last			= time(NULL);
	time_t now			= time(NULL);

	map<string, map<int, int> > buildings;

	buildings["lagermenge"][0]       = 1500;
	buildings["lagermenge"][1]       = 1800;
	buildings["lagermenge"][2]       = 2160;
	buildings["lagermenge"][3]       = 2600;
	buildings["lagermenge"][4]       = 3110;
	buildings["lagermenge"][5]       = 3730;
	buildings["lagermenge"][6]       = 4480;
	buildings["lagermenge"][7]       = 5375;
	buildings["lagermenge"][8]       = 6450;
	buildings["lagermenge"][9]       = 7740;
	buildings["lagermenge"][10]      = 8515;
	buildings["lagermenge"][11]      = 9365;
	buildings["lagermenge"][12]      = 10300;
	buildings["lagermenge"][13]      = 11330;
	buildings["lagermenge"][14]      = 12465;
	buildings["lagermenge"][15]      = 13710;
	buildings["lagermenge"][16]      = 15080;
	buildings["lagermenge"][17]      = 16590;
	buildings["lagermenge"][18]      = 18250;
	buildings["lagermenge"][19]      = 20075;
	buildings["lagermenge"][20]      = 22080;
	buildings["lagermenge"][21]      = 24290;
	buildings["lagermenge"][22]      = 26720;
	buildings["lagermenge"][23]      = 29390;
	buildings["lagermenge"][24]      = 32330;
	buildings["lagermenge"][25]      = 48500;
	buildings["lagermenge"][26]      = 72750;
	buildings["lagermenge"][27]      = 109120;
	buildings["lagermenge"][28]      = 163675;
	buildings["lagermenge"][29]      = 245510;
	buildings["lagermenge"][30]      = 350000;
	
	while(1){
		_beginthread(erhoehen,NULL,NULL);
		Sleep(1000);
	}
}


int main(int argc, char *argv[])
{
	
	try{
	map<string,map<string ,int>> unit;
	
	unit["abfangjaeger"]["Deffground"] = 20;
	unit["abfangjaeger"]["Deffair"] = 50;
	unit["abfangjaeger"]["Att"] = 5;
	unit["abfangjaeger"]["Trans"] = 100;
	unit["abfangjaeger"]["Bevoelkerung"] = 1;
	unit["abfangjaeger"]["speed"] = 12;
	
	unit["techniker"]["Deffground"] = 50;
	unit["techniker"]["Deffair"] = 20;
	unit["techniker"]["Att"] = 5;
	unit["techniker"]["Trans"] = 10;
	unit["techniker"]["Bevoelkerung"] = 1;
	unit["techniker"]["speed"] = 18;
		
	unit["segler"]["Deffground"] = 40;
	unit["segler"]["Deffair"] = 100;
	unit["segler"]["Att"] = 1000;
	unit["segler"]["Trans"] = 2000;
	unit["segler"]["Bevoelkerung"] = 30;
	unit["segler"]["speed"] = 13;
	
	unit["stosstruppe"]["Deffground"] = 20;
	unit["stosstruppe"]["Deffair"] = 10;
	unit["stosstruppe"]["Att"] = 30;
	unit["stosstruppe"]["Trans"] = 100;
	unit["stosstruppe"]["Bevoelkerung"] = 1;
	unit["stosstruppe"]["speed"] = 18;
	
	unit["drone"]["Deffground"] = 10000;
	unit["drone"]["Deffair"] = 500;
	unit["drone"]["Att"] = 10000;
	unit["drone"]["Trans"] = 1000;
	unit["drone"]["Bevoelkerung"] = 100;
	unit["drone"]["speed"] = 20;
	
	unit["kreuzer"]["Deffground"] = 3000;
	unit["kreuzer"]["Deffair"] = 20000;
	unit["kreuzer"]["Att"] = 20000;
	unit["kreuzer"]["Trans"] = 50000;
	unit["kreuzer"]["Bevoelkerung"] = 300;
	unit["kreuzer"]["speed"] = 25;
	


	map<int,map<string ,int>> schild;

	schild[0]["grundverteidigung"] = 10;
	schild[1]["grundverteidigung"] = 15;
	schild[2]["grundverteidigung"] = 22;
	schild[3]["grundverteidigung"] = 34;
	schild[4]["grundverteidigung"] = 50;
	schild[5]["grundverteidigung"] = 75;
	schild[6]["grundverteidigung"] = 114;
	schild[7]["grundverteidigung"] = 170;
	schild[8]["grundverteidigung"] = 256;
	schild[9]["grundverteidigung"] = 512;
	schild[10]["grundverteidigung"] = 1024;
	
	schild[0]["bonus"] = 0;
	schild[1]["bonus"] = 4;
	schild[2]["bonus"] = 9;
	schild[3]["bonus"] = 15;
	schild[4]["bonus"] = 22;
	schild[5]["bonus"] = 30;
	schild[6]["bonus"] = 39;
	schild[7]["bonus"] = 49;
	schild[8]["bonus"] = 60;
	schild[9]["bonus"] = 72;
	schild[10]["bonus"] = 85;




	map<string, map<int, int> > buildings;
/*
	 buildings["produktion"][0]       = 10;
	 buildings["produktion"][1]       = 30;
	 buildings["produktion"][2]       = 39;
	 buildings["produktion"][3]       = 51;
	 buildings["produktion"][4]       = 66;
	 buildings["produktion"][5]       = 86;
	 buildings["produktion"][6]       = 30;
	 buildings["produktion"][7]       = 39;
	 buildings["produktion"][8]       = 51;
	 buildings["produktion"][9]       = 66;
	 buildings["produktion"][10]      = 86;
	 buildings["produktion"][11]      = 110;
	 buildings["produktion"][12]      = 145;
	 buildings["produktion"][13]      = 190;
	 buildings["produktion"][14]      = 245;
	 buildings["produktion"][15]      = 315;
	 buildings["produktion"][16]      = 415;
	 buildings["produktion"][17]      = 540;
	 buildings["produktion"][18]      = 700;
	 buildings["produktion"][19]      = 910;
	 buildings["produktion"][20]      = 1180;
	 buildings["produktion"][21]      = 1440;
	 buildings["produktion"][22]      = 1730;
	 buildings["produktion"][23]      = 2075;
	 buildings["produktion"][24]      = 2490;
	 buildings["produktion"][25]      = 3000;*/
	 
	
	
	buildings["produktion"][0]						= 10;
	buildings["produktion"][1]						= 30;
	buildings["produktion"][2]						= 39;
	buildings["produktion"][3]						= 51;
	buildings["produktion"][4]						= 66;
	buildings["produktion"][5]						= 86;
	buildings["produktion"][6]						= 114;
	buildings["produktion"][7]						= 147;
	buildings["produktion"][8]						= 185;
	buildings["produktion"][9]						= 225;
	buildings["produktion"][10]						= 277;
	buildings["produktion"][11]						= 327;
	buildings["produktion"][12]						= 380;
	buildings["produktion"][13]						= 450;
	buildings["produktion"][14]						= 527;
	buildings["produktion"][15]						= 600;
	buildings["produktion"][16]						= 678;
	buildings["produktion"][17]						= 762;
	buildings["produktion"][18]						= 892;
	buildings["produktion"][19]						= 1033;
	buildings["produktion"][20]						= 1180;
	buildings["produktion"][21]						= 1440;
	buildings["produktion"][22]						= 1730;
	buildings["produktion"][23]						= 2075;
	buildings["produktion"][24]						= 2490;
	buildings["produktion"][25]						= 3000;

	  
	 
	 buildings["lagermenge"][0]       = 1500;
	 buildings["lagermenge"][1]       = 1800;
	 buildings["lagermenge"][2]       = 2160;
	 buildings["lagermenge"][3]       = 2600;
	 buildings["lagermenge"][4]       = 3110;
	 buildings["lagermenge"][5]       = 3730;
	 buildings["lagermenge"][6]       = 4480;
	 buildings["lagermenge"][7]       = 5375;
	 buildings["lagermenge"][8]       = 6450;
	 buildings["lagermenge"][9]       = 7740;
	 buildings["lagermenge"][10]      = 8515;
	 buildings["lagermenge"][11]      = 9365;
	 buildings["lagermenge"][12]      = 10300;
	 buildings["lagermenge"][13]      = 11330;
	 buildings["lagermenge"][14]      = 12465;
	 buildings["lagermenge"][15]      = 13710;
	 buildings["lagermenge"][16]      = 15080;
	 buildings["lagermenge"][17]      = 16590;
	 buildings["lagermenge"][18]      = 18250;
	 buildings["lagermenge"][19]      = 20075;
	 buildings["lagermenge"][20]      = 22080;
	 buildings["lagermenge"][21]      = 24290;
	 buildings["lagermenge"][22]      = 26720;
	 buildings["lagermenge"][23]      = 29390;
	 buildings["lagermenge"][24]      = 32330;
	 buildings["lagermenge"][25]      = 48500;
	 buildings["lagermenge"][26]      = 72750;
	 buildings["lagermenge"][27]      = 109120;
	 buildings["lagermenge"][28]      = 163675;
	 buildings["lagermenge"][29]      = 245510;
	 buildings["lagermenge"][30]      = 350000;
	 buildings["lagermenge"][31]      = 400000;
	 buildings["lagermenge"][32]      = 500000;
	 buildings["lagermenge"][33]      = 600000;
	 buildings["lagermenge"][34]      = 700000;
	 buildings["lagermenge"][35]      = 800000;

	  
	 buildings["versorgtePlaetze"][0]     = 200;
	 buildings["versorgtePlaetze"][1]     = 240;
	 buildings["versorgtePlaetze"][2]     = 288;
	 buildings["versorgtePlaetze"][3]     = 346;
	 buildings["versorgtePlaetze"][4]     = 415;
	 buildings["versorgtePlaetze"][5]     = 500;
	 buildings["versorgtePlaetze"][6]     = 600;
	 buildings["versorgtePlaetze"][7]     = 715;
	 buildings["versorgtePlaetze"][8]     = 860;
	 buildings["versorgtePlaetze"][9]     = 1030;
	 buildings["versorgtePlaetze"][10]     = 1240;
	 buildings["versorgtePlaetze"][11]     = 1460;
	 buildings["versorgtePlaetze"][12]     = 1780;
	 buildings["versorgtePlaetze"][13]     = 2140;
	 buildings["versorgtePlaetze"][14]     = 2575;
	 buildings["versorgtePlaetze"][15]     = 3080;
	 buildings["versorgtePlaetze"][16]     = 3700;
	 buildings["versorgtePlaetze"][17]     = 4437;
	 buildings["versorgtePlaetze"][18]     = 5325;
	 buildings["versorgtePlaetze"][19]     = 6390;
	 buildings["versorgtePlaetze"][20]     = 7675;
	 buildings["versorgtePlaetze"][21]     = 9200;
	 buildings["versorgtePlaetze"][22]     = 11040;
	 buildings["versorgtePlaetze"][23]     = 13250;
	 buildings["versorgtePlaetze"][24]     = 15900;
	 buildings["versorgtePlaetze"][25]     = 19000;



	map < string, int > points;
	points["senat"]							= 15;
	points["raumhafen"] 					= 5;
	points["schildgenerator"]				= 20;
	points["bauer"] 						= 7;
	points["markt"] 						= 10;
	points["schmelze"] 						= 6;
	points["miene"] 						= 6;
	points["reaktor"]						= 7;
	points["biolabor"] 						= 6;
	points["lagerhalle"]					= 8;


	map < string, double > pointsplus;
	pointsplus["senat"]							= 1.3;
	pointsplus["raumhafen"] 					= 1.4;
	pointsplus["schildgenerator"]				= 2;
	pointsplus["bauer"] 						= 1.25;
	pointsplus["markt"] 						= 1.5;
	pointsplus["schmelze"] 						= 1.25;
	pointsplus["miene"] 						= 1.25;
	pointsplus["reaktor"]						= 1.25;
	pointsplus["biolabor"] 						= 1.25;
	pointsplus["lagerhalle"]					= 1.4;



	map< int, string> builds;
	builds[0] = "senat";
	builds[1] = "raumhafen";
	builds[2] = "schildgenerator";
	builds[3] = "bauer";
	builds[4] = "markt";
	builds[5] = "schmelze";
	builds[6] = "miene";
	builds[7] = "reaktor";
	builds[8] = "biolabor";
	builds[9] = "lagerhalle";
	int buildnum = 10;
	// Get database access parameters from command line
	char* db = "dw", *server = "j-pc", *user = "root", *pass = "jgames";
	if(argc == 5){
		db = argv[1];
		server = argv[2];
		user = argv[3];
		pass = argv[4];
	}
	connm = CreateMutex();
	// Connect to the sample database.conn(false);
	if (conn.connect(db, server, user, pass)) {
		// Retrieve the sample stock table set up by resetdb
		_beginthread(erhoeher,NULL,NULL);
		bool pointsc = false;
		while(1){
			/*////////////////////////////////////////////////////////////////
			//							Gebäude ausbauen					//
			////////////////////////////////////////////////////////////////*/
			time_t now = time(NULL);
			char num[20];
			sprintf_s(num,"'%d'",now);
			{
				string str = "SELECT * FROM event_build WHERE end < ";
				str += num;
				str += " ORDER BY end,id;";
				//string str = "SELECT * FROM event_build ";
				mutexP(connm);
				mysqlpp::Query query = conn.query(str.c_str());
				mysqlpp::StoreQueryResult res = query.store();
				mutexV(connm);
				// Display results
				if (res) {
					mysqlpp::Query  query = conn.query();
					size_t i;
					// Get each row in result set, and print its contents
					for (i = 0; i < res.num_rows(); ++i) {
						cout<<endl;
						cout<<"Bauen:";
						cout<<res[i]["id"];
						cout<<":::";
						cout<<res[i]["end"];
						cout<<":::";
						cout<<res[i]["village"];
						cout<<":::";
						cout<<res[i]["building"];
						cout<<endl;

						
						str = "UPDATE `planets` SET `";
						str += res[i]["building"];
						str += "` =  `";
						str += res[i]["building"];
						str += "`+'1' ";
						if(strstr(res[i]["building"],"miene")){	
							sprintf_s(num,"%d",buildings["produktion"][atoi(res[i]["stufe"])]);
							str += (string)", `PPlatin` = " + num + "";
							cout<<"PPlatin: "<<num;
						}
						elif(strstr(res[i]["building"],"schmelze")){
							sprintf_s(num,"%d",buildings["produktion"][atoi(res[i]["stufe"])]);
							str += (string)", `PStahl` = " + num + "";
							cout<<"PStahl: "<<num;
						}
						elif(strstr(res[i]["building"],"biolabor")){
							sprintf_s(num,"%d",buildings["produktion"][atoi(res[i]["stufe"])]);
							str += (string)", `PPlasmid` = " + num + "";
							cout<<"PPlasmid: "<<num;
						}
						elif(strstr(res[i]["building"],"bauer")){
							sprintf_s(num,"%d",buildings["produktion"][atoi(res[i]["stufe"])]);
							str += (string)", `PNahrung` = " + num + "";
							cout<<"PNahrung: "<<num;
						}
						elif(strstr(res[i]["building"],"reaktor")){
							sprintf_s(num,"%d",buildings["produktion"][atoi(res[i]["stufe"])]);
							str += (string)", `PEnergie` = " + num + "";
							cout<<"PEnergie: "<<num;
							str += (string)", `PPlasma` = " + num + "";
							cout<<"PPlasma: "<<num;
						}
						elif(strstr(res[i]["building"],"lagerhalle")){
							sprintf_s(num,"%d",buildings["lagermenge"][atoi(res[i]["stufe"])-1]);
							mutexP(connm);
							{
								mysqlpp::Query query = conn.query((string)"UPDATE `planets` SET `Platin` = " + num + " WHERE (planet=" + (string)res[i]["village"]+") and `Platin`>" + num + ";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Plasma` = "+num+" WHERE (planet="+(string)res[i]["village"]+") and `Plasma`>" + num + ";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Stahl` = "+num+" WHERE (planet="+(string)res[i]["village"]+") and `Stahl`>"+num+";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Energie` = "+num+" WHERE (planet="+(string)res[i]["village"]+") and `Energie`>"+num+";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Plasmid` = "+num+" WHERE (planet="+(string)res[i]["village"]+") and `Plasmid`>"+num+";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Nahrung` = "+num+" WHERE (planet="+(string)res[i]["village"]+") and `Nahrung`>"+num+";");
								query.exec();
							}
							mutexV(connm);
						}
						str += " WHERE `planets`.`id` = ";
						str += res[i]["village"];
						str += "";
						mutexP(connm);
						if(!query.exec(str)) MessageBox(NULL,L"2",L"2",MB_OK);
						mutexV(connm);

						str = "DELETE FROM `event_build` WHERE `id` = ";
						str += res[i]["id"];
						str += "";
						mutexP(connm);
						//mysqlpp::Query  query = conn.query(str.c_str());
						if(!query.exec(str)) MessageBox(NULL,L"1",L"1",MB_OK);
						mutexV(connm);

						str = "SELECT * FROM `planets` WHERE `id` = '";
						str += res[i]["village"];
						str += "'";
						mysqlpp::SQLTypeAdapter village(res[i]["village"]);
						//string str = "SELECT * FROM event_build ";
						mutexP(connm);
						mysqlpp::StoreQueryResult res2;
						if(!(res2 = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
						mutexV(connm);
						// Display results
						static double realpoints = 0;
						realpoints = 0;
						int userid = 0;
						if (res2 && (res2.num_rows()>0)) {
							userid = res2[0]["userid"];
							for(int j = 0; j < buildnum; j++){
								string bld = builds[j];
								double p = points[bld];
								double pp = pointsplus[bld];
								double lev = res2[0][bld.c_str()];
								realpoints += pp*pow(pp,lev/2);
							}/*
							char out[100];
							sprintf_s(out,"%f",realpoints);
							MessageBoxA(NULL,out,"query",MB_OK);*/
						}
						int aktpoints = (realpoints*10168)/832.30075639412985-168;
						sprintf_s(num,"%d",aktpoints);
						str = (string)"UPDATE `planets` SET `points` = "+num+" WHERE `id`="+(string)village+";";
						mutexP(connm);
						if(!query.exec(str)) MessageBox(NULL,L"4",L"4",MB_OK);
						mutexV(connm);

						str = "SELECT SUM(`points`) as pnt FROM `planets` WHERE `userid` = '";
						str += mysqlpp::SQLTypeAdapter(userid);
						str += "'";
						mutexP(connm);
						if(!(res = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
						mutexV(connm);
						if (res && (res.num_rows()>0) && userid) {
							str = (string)"UPDATE `user` SET `points` = "+(string)res[0]["pnt"]+" WHERE `id`="+(string)mysqlpp::SQLTypeAdapter(userid)+";";
							mutexP(connm);
							if(!query.exec(str)) MessageBox(NULL,L"5",L"5",MB_OK);
							mutexV(connm);
						}
						
						realpoints = 0;
						/*
	id  	int(20)
	end 	int(20)
	village 	int(10)
	building 	varchar(30) 
						*/
					}
					if(i > 0){ //Rangliste aktualisieren
						mutexP(connm);
						if(!query.exec(str = "TRUNCATE TABLE `user_platz`;")) MessageBoxA(NULL,query.error(),"6",MB_OK);
						if(!query.exec(str = "SET @num = 0;")) MessageBoxA(NULL,query.error(),"7",MB_OK);
						if(!query.exec(str = "INSERT INTO `user_platz` (id, platz) SELECT id, (@num := @num+1) FROM `user` WHERE userpasswd<>'' ORDER BY `points` DESC,`id` ASC;")) MessageBoxA(NULL,query.error(),"8",MB_OK);
						mutexV(connm);
					}
					//if(!i) cout<<".";
				}
				else {
					cerr << "Failed to open build_event table: \n" << query.error() << endl;
					DestroyMutex(connm);
					printf("restarting...\n");
					return 1;
				}
			}
			/*////////////////////////////////////////////////////////////////
			//							Truppen bauen						//
			////////////////////////////////////////////////////////////////*/
			{
				
				sprintf_s(num,"'%d'",now);
				string str = "SELECT * FROM event_recruit WHERE end_unit < ";
				str += num;
				str += " ORDER BY end_unit";
				mutexP(connm);
				mysqlpp::Query query = conn.query(str.c_str());
				mysqlpp::StoreQueryResult res = query.store();
				mutexV(connm);
				// Display results
				if (res) {
					size_t i;
					// Get each row in result set, and print its contents
					for (i = 0; i < res.num_rows(); ++i) {
						cout<<endl;
						cout<<"Rekrutieren:";
						cout<<res[i]["id"];
						cout<<":::";
						cout<<res[i]["end_unit"];
						cout<<":::";
						cout<<res[i]["planet"];
						cout<<":::";
						cout<<res[i]["type"];
						cout<<":::";
						cout<<res[i]["unitsleft"];
						cout<<endl;
						
						if((string)res[i]["unitsleft"] == "0"){
							str = "DELETE FROM `event_recruit` WHERE `id` = ";
							str += res[i]["id"];

							mutexP(connm);
							mysqlpp::Query query = conn.query(str);
							query.exec();
							mutexV(connm);
						}
						else{
							str = "UPDATE `event_recruit` SET `end_unit` = `end_unit`+`timePerUnit`, `unitsleft` = `unitsleft`-1 WHERE id = ";
							str += res[i]["id"];

							mutexP(connm);
							mysqlpp::Query query = conn.query(str);
							query.exec();
							mutexV(connm);
						}

						str = "UPDATE `planets` SET `";
						str += res[i]["type"];
						str += "` =  `";
						str += res[i]["type"];
						str += "`+'1' ";
						str += " WHERE `planets`.`id` = ";
						str += res[i]["planet"];
						str += "";
						mutexP(connm);
						mysqlpp::Query query2 = conn.query(str);
						query2.exec();
						mutexV(connm);
						/*
	id  	int(20)
	end 	int(20)
	village 	int(10)
	building 	varchar(30) 
						*/







/*

 id
 end_unit
 type
 timePerUnit
 planet
 unitsleft

*/
					}
					//if(!i) cout<<".";
				}
				else {
					cerr << "Failed to open build_event table: " << query.error() << endl;
					DestroyMutex(connm);
					printf("restarting...\n");
					return 1;
				}
			}

			/*////////////////////////////////////////////////////////////////
			//							Truppen bewegen						//
			////////////////////////////////////////////////////////////////*/
			{

				//angriffe
				
				sprintf_s(num,"'%d'",now);
				string str = "SELECT * FROM `event_troops_attack` WHERE ankunft < ";
				str += num;
				str += " ORDER BY ankunft,id";
				mutexP(connm);
				mysqlpp::Query query = conn.query(str.c_str());
				mysqlpp::StoreQueryResult res = query.store();
				mutexV(connm);
				// Display results
				if (res) {
					size_t i;
					// Get each row in result set, and print its contents
					for (i = 0; i < res.num_rows(); ++i) {
						cout<<endl;
						cout<<"Angriff:";
						cout<<res[i]["id"];
						cout<<":::";
						cout<<res[i]["ankunft"];
						cout<<":::";
						cout<<res[i]["from_planet"];
						cout<<":::";
						cout<<res[i]["to_planet"];	  
						cout<<endl;
						
						//berechnen der Einheiten
						int angreifer = 0;
						int verteidiger = 0;
						//Deffwerte
						map<string,int> deff;
						map<string,int> unitnums;

						unitnums["a_stosstruppe"]	= res[i]["stosstruppe"];	  
						unitnums["a_techniker"]		= res[i]["techniker"];
						unitnums["a_drone"]			= res[i]["drone"];									   
						unitnums["a_abfangjaeger"]	= res[i]["abfangjaeger"];
						unitnums["a_segler"]		= res[i]["segler"];
						unitnums["a_kreuzer"]		= res[i]["kreuzer"];
						int president				= res[i]["president"];

						string str = "SELECT * FROM planets WHERE id='" + (string)res[i]["to_planet"] + "'";
						mutexP(connm);
						mysqlpp::StoreQueryResult res4 = query.store(str);
						if(!res4  || (res4.num_rows() != 1)) MessageBox(NULL,L"10",L"10",MB_OK);
						mutexV(connm);	
						unitnums["v_stosstruppe"] = res4[0]["stosstruppe"];	  
						unitnums["v_techniker"] = res4[0]["techniker"];
						unitnums["v_drone"] = res4[0]["drone"];									   
						unitnums["v_abfangjaeger"] = res4[0]["abfangjaeger"];
						unitnums["v_segler"] = res4[0]["segler"];
						unitnums["v_kreuzer"] = res4[0]["kreuzer"];	
						unitnums["shield"] = res4[0]["schildgenerator"];

						int aunitsground = (unitnums["a_stosstruppe"] + unitnums["a_techniker"] + unitnums["a_drone"] );
						int aunitsair = (unitnums["a_abfangjaeger"] + unitnums["a_segler"] + unitnums["a_kreuzer"] );
						int aunitsges = aunitsground+aunitsair;
						
						deff["abfangjaeger"] = (unit["abfangjaeger"]["Deffground"]*(aunitsground) + 
												unit["abfangjaeger"]["Deffair"]*(aunitsair))/
											((aunitsges>0)?aunitsges:1)*(schild[unitnums["shield"]]["bonus"]/100+1);
											
						deff["techniker"] = (unit["techniker"]["Deffground"]*(aunitsground) + 
												unit["techniker"]["Deffair"]*(aunitsair))/
											((aunitsges>0)?(aunitsges):1)*(schild[unitnums["shield"]]["bonus"]/100+1);
						
						deff["segler"] = (unit["segler"]["Deffground"]*(aunitsground) + 
												unit["segler"]["Deffair"]*(aunitsair))/
											((aunitsges>0)?(aunitsges):1)*(schild[unitnums["shield"]]["bonus"]/100+1);	
											
						deff["stosstruppe"] = (unit["stosstruppe"]["Deffground"]*(aunitsground) + 
												unit["stosstruppe"]["Deffair"]*(aunitsair))/
											((aunitsges>0)?aunitsges:1)*(schild[unitnums["shield"]]["bonus"]/100+1);
											
						deff["drone"] = (unit["drone"]["Deffground"]*(aunitsground) + 
												unit["drone"]["Deffair"]*(aunitsair))/
											((aunitsges>0)?(aunitsges):1)*(schild[unitnums["shield"]]["bonus"]/100+1);
						
						deff["kreuzer"] = (unit["kreuzer"]["Deffground"]*(aunitsground) + 
												unit["kreuzer"]["Deffair"]*(aunitsair))/
											((aunitsges>0)?(aunitsges):1)*(schild[unitnums["shield"]]["bonus"]/100+1);			
						
						verteidiger = deff["abfangjaeger"]*unitnums["v_abfangjaeger"]+deff["techniker"]*unitnums["v_techniker"]+deff["segler"]*unitnums["v_segler"]+deff["stosstruppe"]*unitnums["v_stosstruppe"]+deff["drone"]*unitnums["v_drone"]+deff["kreuzer"]*unitnums["v_kreuzer"]+schild[unitnums["shield"]]["grundverteidigung"];
						angreifer = unit["abfangjaeger"]["Att"]*unitnums["a_abfangjaeger"]+unit["techniker"]["Att"]*unitnums["a_techniker"]+unit["segler"]["Att"]*unitnums["a_segler"]+unit["stosstruppe"]["Att"]*unitnums["a_stosstruppe"]+unit["drone"]["Att"]*unitnums["a_drone"]+unit["kreuzer"]["Att"]*unitnums["a_kreuzer"];
						
						
						int vk_abfangjaeger = 0;
						int vk_techniker = 0;
						int vk_segler = 0;
						int vk_stosstruppe = 0;
						int vk_drone = 0;
						int vk_kreuzer = 0;
						int ak_abfangjaeger = 0;
						int ak_techniker = 0;
						int ak_segler = 0;
						int ak_stosstruppe = 0;
						int ak_drone = 0;
						int ak_kreuzer = 0;
						if((angreifer == 0) && (verteidiger != 0)){
							vk_abfangjaeger = unitnums["v_abfangjaeger"];
							vk_techniker = unitnums["v_techniker"];
							vk_segler = unitnums["v_segler"];
							vk_stosstruppe = unitnums["v_stosstruppe"];
							vk_drone = unitnums["v_drone"];
							vk_kreuzer = unitnums["v_kreuzer"];
						}
						elseif(verteidiger >= angreifer){
							int killedpoints = angreifer-((verteidiger-angreifer)/50)-schild[unitnums["shield"]]["grundverteidigung"];
							if(killedpoints < 0) killedpoints = 0;
							if(unitnums["v_abfangjaeger"] > 0){
								double Faktor_abfangjaeger = ((double)verteidiger-schild[unitnums["shield"]]["grundverteidigung"])/((double)deff["abfangjaeger"]*unitnums["v_abfangjaeger"]);
								vk_abfangjaeger = round(unitnums["v_abfangjaeger"]-(double)killedpoints/Faktor_abfangjaeger/deff["abfangjaeger"]);
								if(vk_abfangjaeger > unitnums["v_abfangjaeger"]) vk_abfangjaeger = unitnums["v_abfangjaeger"];
								if(vk_abfangjaeger < 0) vk_abfangjaeger = 0;
							}
							if(unitnums["v_techniker"] > 0){
								double Faktor_techniker = ((double)verteidiger-schild[unitnums["shield"]]["grundverteidigung"])/((double)deff["techniker"]*unitnums["v_techniker"]);
								vk_techniker = round(unitnums["v_techniker"]-(double)killedpoints/Faktor_techniker/deff["techniker"]);
								if(vk_techniker > unitnums["v_techniker"]) vk_techniker = unitnums["v_techniker"];   
								if(vk_techniker < 0) vk_techniker = 0;
							}
							if(unitnums["v_segler"] > 0){
								double Faktor_segler = ((double)verteidiger-schild[unitnums["shield"]]["grundverteidigung"])/((double)deff["segler"]*unitnums["v_segler"]);
								vk_segler = round(unitnums["v_segler"]-(double)killedpoints/Faktor_segler/deff["segler"]);
								if(vk_segler > unitnums["v_segler"]) vk_segler = unitnums["v_segler"];
								if(vk_segler < 0) vk_segler = 0;
							}
							if(unitnums["v_stosstruppe"] > 0){
								double Faktor_stosstruppe = ((double)verteidiger-schild[unitnums["shield"]]["grundverteidigung"])/((double)deff["stosstruppe"]*unitnums["v_stosstruppe"]);
								vk_stosstruppe = round(unitnums["v_stosstruppe"]-(double)killedpoints/Faktor_stosstruppe/deff["stosstruppe"]);
								if(vk_stosstruppe > unitnums["v_stosstruppe"]) vk_stosstruppe = unitnums["v_stosstruppe"];
								if(vk_stosstruppe < 0) vk_stosstruppe = 0;
							}
							if(unitnums["v_drone"] > 0){
								double Faktor_drone = ((double)verteidiger-schild[unitnums["shield"]]["grundverteidigung"])/((double)deff["drone"]*unitnums["v_drone"]);
								vk_drone = round(unitnums["v_drone"]-(double)killedpoints/Faktor_drone/deff["drone"]);
								if(vk_drone > unitnums["v_drone"]) vk_drone = unitnums["v_drone"];
								if(vk_drone < 0) vk_drone = 0;
							}
							if(unitnums["v_kreuzer"] > 0){
								double Faktor_kreuzer = ((double)verteidiger-schild[unitnums["shield"]]["grundverteidigung"])/((double)deff["kreuzer"]*unitnums["v_kreuzer"]);
								vk_kreuzer = round(unitnums["v_kreuzer"]-(double)killedpoints/Faktor_kreuzer/deff["kreuzer"]);
								if(vk_kreuzer > unitnums["v_kreuzer"]) vk_kreuzer = unitnums["v_kreuzer"];   
								if(vk_kreuzer < 0) vk_kreuzer = 0;
							}

							//usernamen
							str = "SELECT * FROM user WHERE id IN (SELECT userid FROM `planets` WHERE `id` = '";
							str += res[i]["from_planet"];
							str += "')";
							mutexP(connm);
							mysqlpp::StoreQueryResult res2;
							if(!(res2 = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
							mutexV(connm);

							str = "SELECT * FROM user WHERE id IN (SELECT userid FROM `planets` WHERE `id` = '";
							str += res[i]["to_planet"];
							str += "' and userpasswd<>'')";
							mutexP(connm);
							mysqlpp::StoreQueryResult res3;
							if(!(res3 = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
							mutexV(connm);

							/*////////////////////////////////////////
							//										//
							//		Holoberichte versenden			//
							//										//
							////////////////////////////////////////*/
							if(res2.num_rows() > 0)
								if(!query.exec((string)"INSERT INTO `holoberichte` ( `gettime` , `userid` , `betreff` , `inhalt`, `time` ) VALUES ( '', '"+
									nts(res2[0]["id"])+
									"', 'Angriff auf "+
									((res3.num_rows() > 0)?(string)res3[0]["username"]:"Aliens")+
									" in "+
									(string)res4[0]["planetname"]+
									"("
									+nts(res4[0]["xcoords"])+
									"|"+
									nts(res4[0]["ycoords"])+
									")</a>', 'Du hast "+
									(((int)res3.num_rows() == 1)?(string)"<a href=\"player.php?id="+nts(res3[0]["id"])+"\">"+(string)res3[0]["username"]+"</a>":"Aliens")+
									" in <a href=\"planet.php?id="+
									nts(res4[0]["id"])+
									"\">"+(string)res4[0]["planetname"]+
									"("+nts(res4[0]["xcoords"])+
									"|"+nts(res4[0]["ycoords"])+
									")</a> angegriffen und verloren.', '"+
									tts(time(NULL))+
									"');")) MessageBox(NULL,L"15",L"15",MB_OK);
							if(res3.num_rows() > 0) if(!query.exec((string)"INSERT INTO `holoberichte` ( `gettime` , `userid` , `betreff` , `inhalt`, `time` ) VALUES ( '', '"+nts(res3[0]["id"])+"', 'Du wurdest von "+((res2.num_rows() > 0)?(string)res2[0]["username"]:"Aliens")+" in "+(string)res4[0]["planetname"]+"("+nts(res4[0]["xcoords"])+"|"+nts(res4[0]["ycoords"])+") angegriffen',"
								" 'Du wurdest von "+((res2.num_rows() > 0)?(string)"<a href=\"player.php?id="+nts(res2[0]["id"])+"\">"+(string)res2[0]["username"]+"</a>":"Aliens")+" in <a href=\"planet.php?id="+nts(res4[0]["id"])+"\">"+(string)res4[0]["planetname"]+"("+nts(res4[0]["xcoords"])+"|"+nts(res4[0]["ycoords"])+")</a> angegriffen und hast gewonnen. <br/><br/>Truppen:<br/><table border=\"1\"><tr><td></td><td>Abfangj&auml;ger</td><td>Techniker</td><td>Landungsschiff</td><td>Stosstruppe</td><td>Drone</td><td>Mutterschiff</td></tr><tr><td>Angreifer: </td><td>"+nts(unitnums["a_abfangjaeger"])+"/"+nts(unitnums["a_abfangjaeger"]-ak_abfangjaeger)+"</td><td>"+nts(unitnums["a_techniker"])+"/"+nts(unitnums["a_techniker"]-ak_techniker)+"</td><td>"+nts(unitnums["a_segler"])+"/"+nts(unitnums["a_segler"]-ak_segler)+"</td><td>"+nts(unitnums["a_stosstruppe"])+"/"+nts(unitnums["a_stosstruppe"]-ak_stosstruppe)+"</td><td>"+nts(unitnums["a_drone"])+"/"+nts(unitnums["a_drone"]-ak_drone)+"</td><td>"+nts(unitnums["a_kreuzer"])+"/"+nts(unitnums["a_kreuzer"]-ak_kreuzer)+"</td></tr><tr><td>Verteidiger: </td><td>"+nts(unitnums["v_abfangjaeger"])+"/"+nts(unitnums["v_abfangjaeger"]-vk_abfangjaeger)+"</td><td>"+nts(unitnums["v_techniker"])+"/"+nts(unitnums["v_techniker"]-vk_techniker)+"</td><td>"+nts(unitnums["v_segler"])+"/"+nts(unitnums["v_segler"]-vk_segler)+"</td><td>"+nts(unitnums["v_stosstruppe"])+"/"+nts(unitnums["v_stosstruppe"]-vk_stosstruppe)+"</td><td>"+nts(unitnums["v_drone"])+"/"+nts(unitnums["v_drone"]-vk_drone)+"</td><td>"+nts(unitnums["v_kreuzer"])+"/"+nts(unitnums["v_kreuzer"]-vk_kreuzer)+"</td></tr></table><br/>Legende: <b>Truppen</b>/<b>Verluste</b>', '"+tts(time(NULL))+"');")) MessageBox(NULL,L"12",L"12",MB_OK);
							/*////////////////////////////////////////
							//										//
							//			Truppen zurückgeben			//
							//										//
							////////////////////////////////////////*/
							int a_minustroops = 
								(unit["segler"]["Bevoelkerung"]			*unitnums["a_segler"])			+ 
								(unit["kreuzer"]["Bevoelkerung"]		*unitnums["a_kreuzer"])			+
								(unit["drone"]["Bevoelkerung"]			*unitnums["a_drone"])			+
								(unit["stosstruppe"]["Bevoelkerung"]	*unitnums["a_stosstruppe"])		+
								(unit["techniker"]["Bevoelkerung"]		*unitnums["a_techniker"])		+
								(unit["abfangjaeger"]["Bevoelkerung"]	*unitnums["a_abfangjaeger"]);
							int v_minustroops = 
								(unit["segler"]["Bevoelkerung"]			*(unitnums["v_segler"]			-vk_segler))		+ 
								(unit["kreuzer"]["Bevoelkerung"]		*(unitnums["v_kreuzer"]			-vk_kreuzer))		+
								(unit["drone"]["Bevoelkerung"]			*(unitnums["v_drone"]			-vk_drone))			+
								(unit["stosstruppe"]["Bevoelkerung"]	*(unitnums["v_stosstruppe"]		-vk_stosstruppe))	+
								(unit["techniker"]["Bevoelkerung"]		*(unitnums["v_techniker"]		-vk_techniker))		+
								(unit["abfangjaeger"]["Bevoelkerung"]	*(unitnums["v_abfangjaeger"]	-vk_abfangjaeger));   
							mutexP(connm);
							if(!query.exec((string)"UPDATE `planets` SET troopsGes = ( troopsGes -"+nts(a_minustroops)+" ) WHERE id='" + (string)res[i]["from_planet"] + "';")) MessageBox(NULL,L"13",L"13",MB_OK);
							if(!query.exec((string)"UPDATE `planets` SET kreuzer="+nts(vk_kreuzer)+",drone="+nts(vk_drone)+",segler="+nts(vk_segler)+",stosstruppe="+nts(vk_stosstruppe)+",techniker="+nts(vk_techniker)+",abfangjaeger="+nts(vk_abfangjaeger)+", troopsGes = ( troopsGes -"+nts(v_minustroops)+" ) WHERE id='" + (string)res[i]["to_planet"] + "';")) MessageBox(NULL,L"13",L"13",MB_OK);
							mutexV(connm);
						}
						elseif(verteidiger < angreifer){
							int killedpoints = verteidiger-((angreifer-verteidiger)/50);
							if(unitnums["a_abfangjaeger"] > 0){
								double Faktor_abfangjaeger = (double)angreifer/((double)deff["abfangjaeger"]*unitnums["a_abfangjaeger"]);
								ak_abfangjaeger = round(unitnums["a_abfangjaeger"]-(double)killedpoints/Faktor_abfangjaeger/deff["abfangjaeger"]);
								if(ak_abfangjaeger > unitnums["a_abfangjaeger"]) ak_abfangjaeger = unitnums["a_abfangjaeger"];
								if(ak_abfangjaeger < 0) ak_abfangjaeger = 0;
							}
							if(unitnums["a_techniker"] > 0){
								double Faktor_techniker = (double)angreifer/((double)deff["techniker"]*unitnums["a_techniker"]);
								ak_techniker = round(unitnums["a_techniker"]-(double)killedpoints/Faktor_techniker/deff["techniker"]);
								if(ak_techniker > unitnums["a_techniker"]) ak_techniker = unitnums["a_techniker"];
								if(ak_techniker < 0) ak_techniker = 0;
							}
							if(unitnums["a_segler"] > 0){
								double Faktor_segler = (double)angreifer/((double)deff["segler"]*unitnums["a_segler"]);
								ak_segler = round(unitnums["a_segler"]-(double)killedpoints/Faktor_segler/deff["segler"]);
								if(ak_segler > unitnums["a_segler"]) ak_segler = unitnums["a_segler"];   
								if(ak_segler < 0) ak_segler = 0;
							}
							if(unitnums["a_stosstruppe"] > 0){
								double Faktor_stosstruppe = (double)angreifer/((double)deff["stosstruppe"]*unitnums["a_stosstruppe"]);
								ak_stosstruppe = round(unitnums["a_stosstruppe"]-(double)killedpoints/Faktor_stosstruppe/deff["stosstruppe"]);
								if(ak_stosstruppe > unitnums["a_stosstruppe"]) ak_stosstruppe = unitnums["a_stosstruppe"];
								if(ak_stosstruppe < 0)				ak_stosstruppe = 0;
							}
							if(unitnums["a_drone"] > 0){
								double Faktor_drone = (double)angreifer/((double)deff["drone"]*unitnums["a_drone"]);
								ak_drone = round(unitnums["a_drone"]-(double)killedpoints/Faktor_drone/deff["drone"]);
								if(ak_drone > unitnums["a_drone"])	ak_drone = unitnums["a_drone"];
								if(ak_drone < 0)					ak_drone = 0;
							}
							if(unitnums["a_kreuzer"] > 0){
								double Faktor_kreuzer = (double)angreifer/((double)deff["kreuzer"]*unitnums["a_kreuzer"]);
								ak_kreuzer = round(unitnums["a_kreuzer"]-(double)killedpoints/Faktor_kreuzer/deff["kreuzer"]);
								if(ak_kreuzer > unitnums["a_kreuzer"]) ak_kreuzer = unitnums["a_kreuzer"];
								if(ak_kreuzer < 0) ak_kreuzer = 0;
							}

							//usernamen
							str = "SELECT * FROM user WHERE id IN (SELECT userid FROM `planets` WHERE `id` = '";
							str += res[i]["from_planet"];
							str += "')";
							mutexP(connm);
							mysqlpp::StoreQueryResult res2;
							if(!(res2 = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
							mutexV(connm);

							str = "SELECT * FROM user WHERE id IN (SELECT userid FROM `planets` WHERE `id` = '";
							str += res[i]["to_planet"];
							str += "' and userpasswd<>'')";
							mutexP(connm);
							mysqlpp::StoreQueryResult res3;
							if(!(res3 = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
							mutexV(connm);

							/*////////////////////////////////////////
							//										//
							//			Beute berechnen				//
							//										//
							////////////////////////////////////////*/
							int ressges =	ak_kreuzer		*unit["kreuzer"]["Trans"]		+
											ak_drone		*unit["drone"]["Trans"]			+
											ak_segler		*unit["segler"]["Trans"]		+
											ak_stosstruppe	*unit["stosstruppe"]["Trans"]	+
											ak_techniker	*unit["techniker"]["Trans"]		+
											ak_abfangjaeger	*unit["abfangjaeger"]["Trans"]	;
							int lagerstufe = atoi(res4[0]["lagerhalle"]);
							int maxlager = buildings["lagermenge"][lagerstufe];
							int playersress =	min(maxlager,(int)(res4[0]["Platin"]	/1000))	+ 
												min(maxlager,(int)(res4[0]["Plasma"]	/1000))	+
												min(maxlager,(int)(res4[0]["Nahrung"]	/1000))	+
												min(maxlager,(int)(res4[0]["Energie"]	/1000))	+
												min(maxlager,(int)(res4[0]["Plasmid"]	/1000))	+
												min(maxlager,(int)(res4[0]["Stahl"]		/1000))	;
							
							int *nochzuklauenOUT[6], nochzuklauenIN[6];
							bool ressleft[6]	= {true,true,true,true,true,true};
							int klauPlatin		= 0;
							int klauPlasma		= 0;
							int klauNahrung		= 0;
							int klauStahl		= 0;
							int klauEnergie		= 0;
							int klauPlasmid		= 0;

							int klauleft		= min(playersress,ressges);

							nochzuklauenOUT[0]	= &klauPlatin;
							nochzuklauenOUT[1]	= &klauPlasma;
							nochzuklauenOUT[2]	= &klauNahrung;
							nochzuklauenOUT[3]	= &klauStahl;
							nochzuklauenOUT[4]	= &klauEnergie;
							nochzuklauenOUT[5]	= &klauPlasmid;

							nochzuklauenIN[0]	= min(maxlager,(int)(res4[0]["Platin"]	/1000));
							nochzuklauenIN[1]	= min(maxlager,(int)(res4[0]["Plasma"]	/1000));
							nochzuklauenIN[2]	= min(maxlager,(int)(res4[0]["Nahrung"]	/1000));
							nochzuklauenIN[3]	= min(maxlager,(int)(res4[0]["Stahl"]	/1000));
							nochzuklauenIN[4]	= min(maxlager,(int)(res4[0]["Energie"]	/1000));
							nochzuklauenIN[5]	= min(maxlager,(int)(res4[0]["Plasmid"]	/1000));
							int resssortsleft = 6;

							while(klauleft){
								int tmp = klauleft/resssortsleft;
								klauleft = 0;
								for(int i = 0; i < 6 ; i++){
									if(nochzuklauenIN[i]){
										nochzuklauenIN[i] -= tmp;
										*nochzuklauenOUT[i] += tmp;
										if(nochzuklauenIN[i] <= 0){
											klauleft -= nochzuklauenIN[i];
											*nochzuklauenOUT[i] += nochzuklauenIN[i];
											resssortsleft--;
											nochzuklauenIN[i] = 0;
										}
									}
								}
							}
							/*////////////////////////////////////////
							//										//
							//		Holoberichte versenden			//
							//										//
							////////////////////////////////////////*/

							string Beutetabelle = "<table><tr><td>Platin</td><td>Plasma</td><td>Energie</td><td>Plasmid</td><td>Stahl</td><td>Nahrung</td></tr><tr><td>"+nts(klauPlatin)+"</td><td>"+nts(klauPlasma)+"</td><td>"+nts(klauEnergie)+"</td><td>"+nts(klauPlasmid)+"</td><td>"+nts(klauStahl)+"</td><td>"+nts(klauNahrung)+"</td></tr></table>";			 
							string presitext;
							bool uebernommen = false;
							if(president){
								presitext += "Ein Pr&auml;sident hat angegriffen und ";
								int ueberlebend = (ak_abfangjaeger + ak_techniker + ak_segler + ak_stosstruppe + ak_kreuzer + ak_drone);
								int angreifende_truppen = unitnums["a_stosstruppe"]+unitnums["a_techniker"]+unitnums["a_drone"]+unitnums["a_abfangjaeger"]+unitnums["a_segler"]+unitnums["a_kreuzer"];
								if(angreifende_truppen-angreifende_truppen/4 <= ueberlebend){
									if(((int)res4[0]["points"] >= 1500) || ((int)res4[0]["userid"] == -1)){
										presitext += "den Planeten &uuml;bernommen.";

										str = "SELECT * FROM variablen WHERE `variable` = 'user_punkte_akt'";
										mutexP(connm);
										mysqlpp::StoreQueryResult resx;
										if(!(resx = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"x",L"x",MB_OK);
										if(!query.exec("UPDATE `planets` SET `userid` = '"+nts((res2.num_rows() > 0)?(int)res2[0]["id"]:-1)+"' WHERE `planets`.`id` = "+(string)res[i]["to_planet"])) MessageBox(NULL,L"20",L"20",MB_OK);
										std::cout<<"Planet uebernommen::"<<(string)res[i]["to_planet"]<<"::"<<((res2.num_rows() > 0)?(int)res2[0]["id"]:-1)<<std::endl;
										//if(resx.num_rows() == 0) query.exec("INSERT INTO `variablen` ( `variable` , `inhalt` ) VALUES ( 'user_punkte_akt', '');"); 
										pointsc = true;
										mutexV(connm);
										uebernommen = true;

										
									}
									else{
										presitext += "ist im Kampf gestorben, da der gegnerische Planet weniger als 1500 Punkte hat.";
									}
								}
								else{
									presitext += "ist im Kampf gestorben, da mehr als 25% seiner Truppen gestorben sind.";
								}
							}
							if(res2.num_rows() > 0) if(!query.exec((string)"INSERT INTO `holoberichte` ( `gettime` , `userid` , `betreff` , `inhalt`, `time` ) VALUES ( '', '"+
								nts(res2[0]["id"])+"', 'Angriff auf "+((res3.num_rows() == 1)?(string)res3[0]["username"]:"Aliens")+" in "+(string)res4[0]["planetname"]+"("+nts(res4[0]["xcoords"])+"|"+nts(res4[0]["ycoords"])+")',"
								" 'Du hast "+((res3.num_rows() == 1)?(string)"<a href=\"player.php?id="+nts(res3[0]["id"])+"\">"+(string)res3[0]["username"]+"</a>":"Aliens")+" in <a href=\"planet.php?id="+nts(res4[0]["id"])+"\">"+(string)res4[0]["planetname"]+"("+nts(res4[0]["xcoords"])+"|"+nts(res4[0]["ycoords"])+
								")</a> angegriffen und gewonnen. <br/><br/>Truppen:<br/><table border=\"1\"><tr><td></td><td>Abfangj&auml;ger</td><td>Techniker</td><td>Landungsschiff</td><td>Stosstruppe</td><td>Drone</td><td>Mutterschiff</td></tr><tr><td>Angreifer: </td><td>"+nts(unitnums["a_abfangjaeger"])+"/"+nts(unitnums["a_abfangjaeger"]-ak_abfangjaeger)+"</td><td>"+nts(unitnums["a_techniker"])+"/"+nts(unitnums["a_techniker"]-ak_techniker)+"</td><td>"+nts(unitnums["a_segler"])+"/"+nts(unitnums["a_segler"]-ak_segler)+"</td><td>"+nts(unitnums["a_stosstruppe"])+"/"+nts(unitnums["a_stosstruppe"]-ak_stosstruppe)+"</td><td>"+nts(unitnums["a_drone"])+"/"+nts(unitnums["a_drone"]-ak_drone)+"</td><td>"+nts(unitnums["a_kreuzer"])+"/"+nts(unitnums["a_kreuzer"]-ak_kreuzer)+"</td></tr><tr><td>Verteidiger: </td><td>"+nts(unitnums["v_abfangjaeger"])+"/"+nts(unitnums["v_abfangjaeger"]-vk_abfangjaeger)+"</td><td>"+nts(unitnums["v_techniker"])+"/"+nts(unitnums["v_techniker"]-vk_techniker)+"</td><td>"+nts(unitnums["v_segler"])+"/"+nts(unitnums["v_segler"]-vk_segler)+"</td><td>"+nts(unitnums["v_stosstruppe"])+"/"+nts(unitnums["v_stosstruppe"]-vk_stosstruppe)+"</td><td>"+nts(unitnums["v_drone"])+"/"+nts(unitnums["v_drone"]-vk_drone)+"</td><td>"+nts(unitnums["v_kreuzer"])+"/"+nts(unitnums["v_kreuzer"]-vk_kreuzer)+"</td></tr></table><br/>Beute:<br/>"+Beutetabelle+"<br/>Legende: <b>Truppen</b>/<b>Verluste</b><br/><br/><b>"+presitext+"</b>', '"+tts(time(NULL))+"');")) MessageBox(NULL,L"11",L"11",MB_OK);
							
							
							if(res3.num_rows() > 0) if(!query.exec((string)"INSERT INTO `holoberichte` ( `gettime` , `userid` , `betreff` , `inhalt`, `time` ) VALUES ( '', '"+nts(res3[0]["id"])+"', 'Du wurdest von "+((res2.num_rows() > 0)?(string)res2[0]["username"]:"Aliens")+" in "+(string)res4[0]["planetname"]+"("+nts(res4[0]["xcoords"])+"|"+nts(res4[0]["ycoords"])+") angegriffen',"
								" 'Du wurdest von "+((res2.num_rows() > 0)?(string)"<a href=\"player.php?id="+nts(res2[0]["id"])+"\">"+(string)res2[0]["username"]+"</a>":"Aliens")+" in <a href=\"planet.php?id="+nts(res4[0]["id"])+"\">"+(string)res4[0]["planetname"]+"("+nts(res4[0]["xcoords"])+"|"+nts(res4[0]["ycoords"])+")</a> angegriffen und hast verloren. <br/><br/>Truppen:<br/><table border=\"1\"><tr><td></td><td>Abfangj&auml;ger</td><td>Techniker</td><td>Landungsschiff</td><td>Stosstruppe</td><td>Drone</td><td>Mutterschiff</td></tr><tr><td>Angreifer: </td><td>"+nts(unitnums["a_abfangjaeger"])+"/"+nts(unitnums["a_abfangjaeger"]-ak_abfangjaeger)+"</td><td>"+nts(unitnums["a_techniker"])+"/"+nts(unitnums["a_techniker"]-ak_techniker)+"</td><td>"+nts(unitnums["a_segler"])+"/"+nts(unitnums["a_segler"]-ak_segler)+"</td><td>"+nts(unitnums["a_stosstruppe"])+"/"+nts(unitnums["a_stosstruppe"]-ak_stosstruppe)+"</td><td>"+nts(unitnums["a_drone"])+"/"+nts(unitnums["a_drone"]-ak_drone)+"</td><td>"+nts(unitnums["a_kreuzer"])+"/"+nts(unitnums["a_kreuzer"]-ak_kreuzer)+"</td></tr><tr><td>Verteidiger: </td><td>"+nts(unitnums["v_abfangjaeger"])+"/"+nts(unitnums["v_abfangjaeger"]-vk_abfangjaeger)+"</td><td>"+nts(unitnums["v_techniker"])+"/"+nts(unitnums["v_techniker"]-vk_techniker)+"</td><td>"+nts(unitnums["v_segler"])+"/"+nts(unitnums["v_segler"]-vk_segler)+"</td><td>"+nts(unitnums["v_stosstruppe"])+"/"+nts(unitnums["v_stosstruppe"]-vk_stosstruppe)+"</td><td>"+nts(unitnums["v_drone"])+"/"+nts(unitnums["v_drone"]-vk_drone)+"</td><td>"+nts(unitnums["v_kreuzer"])+"/"+nts(unitnums["v_kreuzer"]-vk_kreuzer)+"</td></tr></table><br/>Beute:<br/>"+Beutetabelle+"<br/>Legende: <b>Truppen</b>/<b>Verluste</b><br/><br/><b>"+presitext+"</b>', '"+tts(time(NULL))+"');")) MessageBox(NULL,L"12",L"12",MB_OK);
							
							
							/*////////////////////////////////////////
							//										//
							//			Truppen zurückgeben			//
							//										//
							////////////////////////////////////////*/
							sprintf_s(num,"%d",maxlager);
							mutexP(connm);
							{
								mysqlpp::Query query = conn.query((string)"UPDATE `planets` SET `Platin` = " + num + " WHERE (planet=" + (string)res4[0]["id"]+") and `Platin`>" + num + ";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Plasma` = "+num+" WHERE (planet="+(string)res4[0]["id"]+") and `Plasma`>" + num + ";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Stahl` = "+num+" WHERE (planet="+(string)res4[0]["id"]+") and `Stahl`>"+num+";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Energie` = "+num+" WHERE (planet="+(string)res4[0]["id"]+") and `Energie`>"+num+";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Plasmid` = "+num+" WHERE (planet="+(string)res4[0]["id"]+") and `Plasmid`>"+num+";");
								query.exec();
								query = conn.query((string)"UPDATE `planets` SET `Nahrung` = "+num+" WHERE (planet="+(string)res4[0]["id"]+") and `Nahrung`>"+num+";");
								query.exec();
							}
							mutexV(connm);
							string tmp;
							int v_minustroops = 
								(unit["segler"]["Bevoelkerung"]			*unitnums["v_segler"])			+ 
								(unit["kreuzer"]["Bevoelkerung"]		*unitnums["v_kreuzer"])			+
								(unit["drone"]["Bevoelkerung"]			*unitnums["v_drone"])			+
								(unit["stosstruppe"]["Bevoelkerung"]	*unitnums["v_stosstruppe"])		+
								(unit["techniker"]["Bevoelkerung"]		*unitnums["v_techniker"])		+
								(unit["abfangjaeger"]["Bevoelkerung"]	*unitnums["v_abfangjaeger"]);
							int a_minustroops = 
								(unit["segler"]["Bevoelkerung"]			*(unitnums["a_segler"]			-ak_segler))		+ 
								(unit["kreuzer"]["Bevoelkerung"]		*(unitnums["a_kreuzer"]			-ak_kreuzer))		+
								(unit["drone"]["Bevoelkerung"]			*(unitnums["a_drone"]			-ak_drone))			+
								(unit["stosstruppe"]["Bevoelkerung"]	*(unitnums["a_stosstruppe"]		-ak_stosstruppe))	+
								(unit["techniker"]["Bevoelkerung"]		*(unitnums["a_techniker"]		-ak_techniker))		+
								(unit["abfangjaeger"]["Bevoelkerung"]	*(unitnums["a_abfangjaeger"]	-ak_abfangjaeger));	 
							int a_minustroopsges = 
								(unit["segler"]["Bevoelkerung"]			*(unitnums["a_segler"]			))		+ 
								(unit["kreuzer"]["Bevoelkerung"]		*(unitnums["a_kreuzer"]			))		+
								(unit["drone"]["Bevoelkerung"]			*(unitnums["a_drone"]			))		+
								(unit["stosstruppe"]["Bevoelkerung"]	*(unitnums["a_stosstruppe"]		))		+
								(unit["techniker"]["Bevoelkerung"]		*(unitnums["a_techniker"]		))		+
								(unit["abfangjaeger"]["Bevoelkerung"]	*(unitnums["a_abfangjaeger"]	));
							mutexP(connm);
							if(!uebernommen || ((res4[0]["troopsGes"]-v_minustroops+a_minustroopsges-a_minustroops) < buildings["versorgtePlaetze"][res4[0]["bauer"]])){
								if(!query.exec((string)"UPDATE `planets` SET troopsGes = ( troopsGes -"+nts(a_minustroops)+" ) WHERE id='" + (string)res[i]["from_planet"] + "';")) MessageBox(NULL,L"13",L"13",MB_OK);
								if(!query.exec(tmp=(string)"UPDATE `planets` SET kreuzer=0,drone=0,segler=0,stosstruppe=0,techniker=0,abfangjaeger=0,president=0,"+
									"`Platin` = (`Platin`-"+nts(klauPlatin*1000)+"),"+
									"`Plasma` = (`Plasma`-"+nts(klauPlasma*1000)+"),"+
									"`Nahrung` = (`Nahrung`-"+nts(klauNahrung*1000)+"),"+
									"`Plasmid` = (`Plasmid`-"+nts(klauPlasmid*1000)+"),"+
									"`Energie` = (`Energie`-"+nts(klauEnergie*1000)+"),"+
									"`Stahl` = (`Stahl`-"+nts(klauStahl*1000)+"),"+
									"`troopsGes` = (`troopsGes`-"+nts(v_minustroops)+")"+
									"WHERE id=" + (string)res[i]["to_planet"] + ";")) MessageBox(NULL,L"14",L"14",MB_OK);
								if(!query.exec(tmp = (string)"INSERT INTO event_troops_back(from_planet,to_planet,abfangjaeger,techniker,segler,stosstruppe,drone,kreuzer,ankunft,Platin,Stahl,Plasma,Energie,Nahrung,Plasmid)VALUES ("+(string)res[i]["to_planet"]+","+(string)res[i]["from_planet"]+","+nts(ak_abfangjaeger)+","+nts(ak_techniker)+","+nts(ak_segler)+","+nts(ak_stosstruppe)+","+nts(ak_drone)+","+nts(ak_kreuzer)+","+nts((int)res[i]["ankunft"] + (int)res[i]["laufzeit"])+","+nts(klauPlatin)+","+nts(klauStahl)+","+nts(klauPlasma)+","+nts(klauEnergie)+","+nts(klauNahrung)+","+nts(klauPlasmid)+");")) MessageBox(NULL,L"17",L"17",MB_OK);
							}
							else{
								if(!query.exec((string)"UPDATE `planets` SET troopsGes = ( troopsGes -"+(nts(a_minustroops+a_minustroopsges-a_minustroops))+" ) WHERE id='" + (string)res[i]["from_planet"] + "';")) MessageBox(NULL,L"13",L"13",MB_OK);
								if(!query.exec(tmp=(string)"UPDATE `planets` SET kreuzer="+nts(ak_kreuzer)+",drone="+nts(ak_drone)+",segler="+nts(ak_segler)+",stosstruppe="+nts(ak_stosstruppe)+",techniker="+nts(ak_techniker)+",abfangjaeger="+nts(ak_abfangjaeger)+",president=0,"+
									"WHERE id=" + (string)res[i]["to_planet"] + ";")) MessageBox(NULL,L"14",L"14",MB_OK);
							}
							mutexV(connm);
						}
						mutexP(connm);
						query.exec((string)"DELETE FROM `event_troops_attack` WHERE id="+(string)res[i]["id"]);
						mutexV(connm);
						
					}
					//if(!i) cout<<".";
				}
				else {
					cerr << "Failed to open event_troops_attack table: " << query.error() << endl;
					DestroyMutex(connm);
					printf("restarting...\n");
					return 1;
				}
				//heimkehrende angriffe
				{
					sprintf_s(num,"'%d'",now);
					string str = "SELECT * FROM `event_troops_back` WHERE ankunft < ";
					str += num;
					str += " ORDER BY ankunft,id";
					mutexP(connm);
					mysqlpp::Query query = conn.query(str.c_str());
					mysqlpp::StoreQueryResult res = query.store();
					mutexV(connm);
					// Display results
					if (res) {
						size_t i;
						// Get each row in result set, and print its contents
						for (i = 0; i < res.num_rows(); ++i) {
							cout<<endl;
							cout<<"Angriff (zurueck):";
							cout<<res[i]["id"];
							cout<<":::";
							cout<<res[i]["ankunft"];
							cout<<":::";
							cout<<res[i]["from_planet"];
							cout<<":::";
							cout<<res[i]["to_planet"];	  
							cout<<endl;
							string str;
							query.exec(str = (string)"UPDATE `planets` SET "
								"`platin`	= (`platin`+"+nts(res[i]["Platin"]*1000)+"),"
								"`plasma`	= (`plasma`+"+nts(res[i]["Plasma"]*1000)+"),"
								"`plasmid`	= (`plasmid`+"+nts(res[i]["Plasmid"]*1000)+"),"
								"`energie`	= (`energie`+"+nts(res[i]["Energie"]*1000)+"),"
								"`nahrung`	= (`nahrung`+"+nts(res[i]["Nahrung"]*1000)+"),"
								"`stahl`	= (`stahl`+"+nts(res[i]["Stahl"]*1000)+"),"


								"`segler`	= (`segler`+"+(string)res[i]["segler"]+"),"
								"`kreuzer`	= (`kreuzer`+"+(string)res[i]["kreuzer"]+"),"
								"`abfangjaeger`	= (`abfangjaeger`+"+(string)res[i]["abfangjaeger"]+"),"
								"`stosstruppe`	= (`stosstruppe`+"+(string)res[i]["stosstruppe"]+"),"
								"`techniker`	= (`techniker`+"+(string)res[i]["techniker"]+"),"
								"`drone`	= (`drone`+"+(string)res[i]["drone"]+") WHERE `id`="+(string)res[i]["to_planet"]);
							query.exec((string)"DELETE FROM `event_troops_back` WHERE `id` = "+(string)res[i]["id"]);
						}
						//if(!i) cout<<".";
					}
					else {
						cerr << "Failed to open event_troops_attack table: " << query.error() << endl;
						DestroyMutex(connm);
						printf("restarting...\n");
						return 1;
					}
				}
				//geschenke
				{
					sprintf_s(num,"'%d'",now);
					string str = "SELECT * FROM `event_troops_defense` WHERE ankunft < ";
					str += num;
					str += " ORDER BY ankunft,id";
					mutexP(connm);
					mysqlpp::Query query = conn.query(str.c_str());
					mysqlpp::StoreQueryResult res = query.store();
					mutexV(connm);
					// Display results
					if (res) {
						size_t i;
						// Get each row in result set, and print its contents
						for (i = 0; i < res.num_rows(); ++i) {
							cout<<endl;
							cout<<"Verteidigung:";
							cout<<res[i]["id"];
							cout<<":::";
							cout<<res[i]["ankunft"];
							cout<<":::";
							cout<<res[i]["from_planet"];
							cout<<":::";
							cout<<res[i]["to_planet"];	  
							cout<<endl;
							string str;
							query.exec(str = (string)"UPDATE `planets` SET "
								"`segler`	= (`segler`+"+(string)res[i]["segler"]+"),"
								"`kreuzer`	= (`kreuzer`+"+(string)res[i]["kreuzer"]+"),"
								"`abfangjaeger`	= (`abfangjaeger`+"+(string)res[i]["abfangjaeger"]+"),"
								"`stosstruppe`	= (`stosstruppe`+"+(string)res[i]["stosstruppe"]+"),"
								"`techniker`	= (`techniker`+"+(string)res[i]["techniker"]+"),"
								"`drone`	= (`drone`+"+(string)res[i]["drone"]+") WHERE `id`="+(string)res[i]["to_planet"]);
							query.exec((string)"DELETE FROM `event_troops_defense` WHERE `id` = "+(string)res[i]["id"]);
						}
						//if(!i) cout<<".";
					}
					else {
						cerr << "Failed to open event_troops_attack table: " << query.error() << endl;
						DestroyMutex(connm);
						printf("restarting...\n");
						return 1;
					}
				}
			}

			/*////////////////////////////////////////////////////////////////
			//							SonderEvents						//
			////////////////////////////////////////////////////////////////*/
			{
				string str = "SELECT * FROM variablen WHERE variable = 'punkte_akt' ";
				mutexP(connm);
				mysqlpp::Query query = conn.query(str.c_str());
				mysqlpp::StoreQueryResult res = query.store();
				mutexV(connm);
				// Display results
				if ((res && (res.num_rows()>0)) || pointsc) {
					string str = "SELECT * FROM planets";
					mutexP(connm);
					mysqlpp::Query query = conn.query(str.c_str());
					mysqlpp::StoreQueryResult res = query.store();
					mysqlpp::StoreQueryResult res2;
					mutexV(connm);
					str = (string)"UPDATE `user` SET `points` = 0;";
					mutexP(connm);
					if(!query.exec(str)) MessageBox(NULL,L"5",L"5",MB_OK);
					mutexV(connm);
					// Display results
					if (res && (res.num_rows()>0)) {
						query.exec("DELETE FROM variablen WHERE variable = 'punkte_akt'");
						for (int i = 0; i < res.num_rows(); ++i) {
							double realpoints = 0;
							realpoints = 0;
							int userid = 0;
							string bld;
							userid = res[i]["userid"];
							for(int j = 0; j < buildnum; j++){
								bld = builds[j];
								double p = points[bld];
								double pp = pointsplus[bld];
								double lev = res[i][bld.c_str()];
								realpoints += pp*pow(pp,lev/2);
							}
							int aktpoints = (realpoints*10168)/832.30075639412985-168;
							sprintf_s(num,"%d",aktpoints);
							str = (string)"UPDATE `planets` SET `points` = "+num+" WHERE `id`="+(string)res[i]["id"]+";";
							mutexP(connm);
							if(!query.exec(str)) MessageBox(NULL,L"4",L"4",MB_OK);
							mutexV(connm);

							str = "SELECT SUM(`points`) as pnt FROM `planets` WHERE `userid` = '";
							str += mysqlpp::SQLTypeAdapter(userid);
							str += "'";
							mutexP(connm);
							if(!(res2 = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
							mutexV(connm);
							if (res2 && (res2.num_rows()>0) && (userid>0)) {
								str = (string)"UPDATE `user` SET `points` = "+(string)res2[0]["pnt"]+" WHERE `id`="+(string)mysqlpp::SQLTypeAdapter(userid)+";";
								mutexP(connm);
								if(!query.exec(str)) MessageBox(NULL,L"5",L"5",MB_OK);
								mutexV(connm);
							}
							
							realpoints = 0;
						}
						mutexP(connm);
						if(!query.exec(str = "TRUNCATE TABLE `user_platz`;")) MessageBoxA(NULL,query.error(),"6",MB_OK);
						if(!query.exec(str = "SET @num = 0;")) MessageBoxA(NULL,query.error(),"7",MB_OK);
						if(!query.exec(str = "INSERT INTO `user_platz` (id, platz) SELECT id, (@num := @num+1) FROM `user` WHERE userpasswd<>'' ORDER BY `points` DESC,`id` ASC;")) MessageBoxA(NULL,query.error(),"8",MB_OK);
						mutexV(connm);
					}
				}
				
				{
					string str = "SELECT * FROM variablen WHERE variable = 'user_punkte_akt' ";
					mutexP(connm);
					mysqlpp::Query query = conn.query(str.c_str());
					mysqlpp::StoreQueryResult res = query.store();
					mutexV(connm);
					// Display results
					if ((res && (res.num_rows()>0)) || pointsc) {
						string str = "SELECT * FROM user WHERE userpasswd<>''";
						query.exec("DELETE FROM variablen WHERE variable = 'user_punkte_akt'");
						mutexP(connm);
						mysqlpp::Query query = conn.query(str.c_str());
						mysqlpp::StoreQueryResult res = query.store();
						mysqlpp::StoreQueryResult res2;
						mutexV(connm);
						str = (string)"UPDATE `user` SET `points` = 0;";
						mutexP(connm);
						if(!query.exec(str)) MessageBox(NULL,L"5",L"5",MB_OK);
						mutexV(connm);
						// Display results
						if (res && (res.num_rows()>0)) {
							for (int i = 0; i < res.num_rows(); ++i) {
								str = "SELECT SUM(`points`) as pnt FROM `planets` WHERE `userid` = '";
								str += (string)res[i]["id"];
								str += "'";
								mutexP(connm);
								if(!(res2 = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
								mutexV(connm);
								if (res2 && (res2.num_rows()>0)) {
									str = (string)"UPDATE `user` SET `points` = "+(string)res2[0]["pnt"]+" WHERE `id`="+(string)res[i]["id"]+";";
									mutexP(connm);
									if(!query.exec(str)) MessageBox(NULL,L"5",L"5",MB_OK);
									mutexV(connm);
								}
							}
							mutexP(connm);
							if(!query.exec(str = "TRUNCATE TABLE `user_platz`;")) MessageBoxA(NULL,query.error(),"6",MB_OK);
							if(!query.exec(str = "SET @num = 0;")) MessageBoxA(NULL,query.error(),"7",MB_OK);
							if(!query.exec(str = "INSERT INTO `user_platz` (id, platz) SELECT id, (@num := @num+1) FROM `user` WHERE userpasswd<>'' ORDER BY `points` DESC,`id` ASC;")) MessageBoxA(NULL,query.error(),"8",MB_OK);
							mutexV(connm);
						}
					}
				}
				pointsc = false;
			}
			Sleep(1);

		}
		DestroyMutex(connm);
		printf("restarting...\n");

		return 0;
	}
	else {
		cerr << "DB connection failed: " << conn.error() << endl;
		DestroyMutex(connm);
		printf("restarting...\n");
		return 1;
	}
	
	}catch(std::exception){
		printf("restarting...\n");
		return 1;
	}
}
