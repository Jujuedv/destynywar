// Destyny War - Ereignissschleife.cpp : Definiert den Einstiegspunkt für die Konsolenanwendung.
//
#include <c:\Users\julian\Desktop\mysql++-3.0.9\lib\mysql++.h>

#include <iostream>
#include <iomanip>
#include <process.h>

using namespace std;
#define elif else if
#define elseif elif

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




const char* db = "dw", *server = 0, *user = "root", *pass = "<password>";
mysqlpp::Connection conn(false);
Jmutex *connm;

void erhoehen(void *){
	mutexP(connm);
	mysqlpp::Query query = conn.query("UPDATE `planets` SET `Platin` =  (`PPlatin`/3.6)+`Platin`,`Plasma` =  (`PPlasma`/3.6)+`Plasma`,`Energie` =  (`PEnergie`/3.6)+`Energie`,`Plasmid` =  (`PPlasmid`/3.6)+`Plasmid`,`Nahrung` =  (`PNahrung`/3.6)+`Nahrung`,`Stahl` =  (`PStahl`/3.6)+`Stahl` WHERE 1;");
	query.exec();
	mutexV(connm);
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
	unit["segler"]["Trans"] = 10000;
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
	 buildings["produktion"][25]      = 3000;

	  
	 
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
	points["uni"] 							= 10;
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
	pointsplus["uni"] 							= 1.5;
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
	builds[4] = "uni";
	builds[5] = "schmelze";
	builds[6] = "miene";
	builds[7] = "reaktor";
	builds[8] = "biolabor";
	builds[9] = "lagerhalle";
	int buildnum = 10;

	// Get database access parameters from command line
	const char* db = "dw", *server = 0, *user = "root", *pass = "jgames";
	connm = CreateMutex();
	// Connect to the sample database.conn(false);
	if (conn.connect(db, server, user, pass)) {
		// Retrieve the sample stock table set up by resetdb
		_beginthread(erhoeher,NULL,NULL);
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

						str = "DELETE FROM `event_build` WHERE `id` = ";
						str += res[i]["id"];
						str += "";
						mutexP(connm);
						//mysqlpp::Query  query = conn.query(str.c_str());
						if(!query.exec(str)) MessageBox(NULL,L"1",L"1",MB_OK);
						mutexV(connm);

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

						str = "SELECT * FROM `planets` WHERE `id` = '";
						str += res[i]["village"];
						str += "'";
						mysqlpp::SQLTypeAdapter village(res[i]["village"]);
						//string str = "SELECT * FROM event_build ";
						mutexP(connm);
						if(!(res = query.store(mysqlpp::SQLTypeAdapter(str)))) MessageBox(NULL,L"3",L"3",MB_OK);
						mutexV(connm);
						// Display results
						static double realpoints = 0;
						realpoints = 0;
						int userid = 0;
						if (res && (res.num_rows()>0)) {
							userid = res[0]["userid"];
							for(int i = 0; i < buildnum; i++){
								string bld = builds[i];
								double p = points[bld];
								double pp = pointsplus[bld];
								double lev = res[0][bld.c_str()];
								realpoints += pp*pow(pp,lev/2);
							}/*
							char out[100];
							sprintf_s(out,"%f",realpoints);
							MessageBoxA(NULL,out,"query",MB_OK);*/
						}
						int aktpoints = realpoints;
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
					//if(!i) cout<<".";
				}
				else {
					cerr << "Failed to open build_event table: " << query.error() << endl;
					DestroyMutex(connm);
					system("PAUSE");
					return 1;
				}
			}
			/*////////////////////////////////////////////////////////////////
			//							Truppen bauen						//
			////////////////////////////////////////////////////////////////*/
			{
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
					system("PAUSE");
					return 1;
				}
			}
			Sleep(500);
		}
		DestroyMutex(connm);
		system("PAUSE");

		return 0;
	}
	else {
		cerr << "DB connection failed: " << conn.error() << endl;
		DestroyMutex(connm);
		system("PAUSE");
		return 1;
	}
}
