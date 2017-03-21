storage_capacity = list()

storage_capacity.append(1500)
storage_capacity.append(1800)
storage_capacity.append(2160)
storage_capacity.append(2600)
storage_capacity.append(3110)
storage_capacity.append(3730)
storage_capacity.append(4480)
storage_capacity.append(5375)
storage_capacity.append(6450)
storage_capacity.append(7740)
storage_capacity.append(8515)
storage_capacity.append(9365)
storage_capacity.append(10300)
storage_capacity.append(11330)
storage_capacity.append(12465)
storage_capacity.append(13710)
storage_capacity.append(15080)
storage_capacity.append(16590)
storage_capacity.append(18250)
storage_capacity.append(20075)
storage_capacity.append(22080)
storage_capacity.append(24290)
storage_capacity.append(26720)
storage_capacity.append(29390)
storage_capacity.append(32330)
storage_capacity.append(48500)
storage_capacity.append(72750)
storage_capacity.append(109120)
storage_capacity.append(163675)
storage_capacity.append(245510)
storage_capacity.append(350000)

production = list()

production.append(10)
production.append(30)
production.append(39)
production.append(51)
production.append(66)
production.append(86)
production.append(114)
production.append(147)
production.append(185)
production.append(225)
production.append(277)
production.append(327)
production.append(380)
production.append(450)
production.append(527)
production.append(600)
production.append(678)
production.append(762)
production.append(892)
production.append(1033)
production.append(1180)
production.append(1440)
production.append(1730)
production.append(2075)
production.append(2490)
production.append(3000)

buildings = [
    {
        "intname": "senate",
        "minlevel": 1,
        "maxlevel": 25,
        "name": "Interplanetarischer Senat",
        "platinum": 80,
        "energy": 60,
        "steel": 100,
        "plasma": 30,
        "plasmid": 0,
        "food": 100,
        "farm_needed": 5,
        "build_time": 5,
        "cost_factor": 1.2,
        "time_factor": 1.2,
        "description": "Im Interplanetarischen Senat kannst du Gebäude ausbauen. Umso höher die Stufe des Interplanetarischen Senats ist, umso schneller kannst du Gebäude bauen.",
    },
    {
        "intname": "spaceport",
        "minlevel": 0,
        "maxlevel": 20,
        "name": "Militärstützpunkt",
        "platinum": 160,
        "energy": 210,
        "steel": 180,
        "plasma": 50,
        "plasmid": 20,
        "food": 200,
        "farm_needed": 10,
        "build_time": 15,
        "cost_factor": 1.5,
        "time_factor": 1.3,
        "description": "Im Militärstützpunkt kannst du Einheiten ausbilden.",
    },
    {
        "intname": "shield",
        "minlevel": 0,
        "maxlevel": 10,
        "name": "Schildgenerator",
        "platinum": 60,
        "energy": 200,
        "steel": 50,
        "plasma": 200,
        "plasmid": 0,
        "food": 0,
        "farm_needed": 8,
        "build_time": 14,
        "cost_factor": 1.8,
        "time_factor": 1.3,
        "description": "Der Schildgenerator erstellt einen starken Schutzschild um deinen Planet, welcher deinen Truppen bei der Verteidigung hilft.",
    },
    {
        "intname": "farm",
        "minlevel": 0,
        "maxlevel": 25,
        "name": "Farm",
        "platinum": 10,
        "energy": 130,
        "steel": 100,
        "plasma": 5,
        "plasmid": 0,
        "food": 4,
        "farm_needed": 0,
        "build_time": 12,
        "cost_factor": 1.3,
        "time_factor": 1.25,
        "description": "Die Farm sorgt für die Nahrung deines Planeten. Zusätzlich begrenzt sie die Anzahl der Truppen, die dein Planet maximal bauen darf.",
    },
    {
        "intname": "market",
        "minlevel": 0,
        "maxlevel": 20,
        "name": "Handelszentrum",
        "platinum": 50,
        "energy": 50,
        "steel": 50,
        "plasma": 50,
        "plasmid": 50,
        "food": 50,
        "farm_needed": 15,
        "build_time": 30,
        "cost_factor": 1.6,
        "time_factor": 1.25,
        "description": "Im Handelszentrum kannst du Rohstoffe an deine eigene Allianz verschicken.",
    },
    {
        "intname": "smeltery",
        "minlevel": 0,
        "maxlevel": 25,
        "name": "Eisenschmelze",
        "platinum": 115,
        "energy": 75,
        "steel": 6,
        "plasma": 5,
        "plasmid": 0,
        "food": 125,
        "farm_needed": 7,
        "build_time": 8,
        "cost_factor": 1.3,
        "time_factor": 1.25,
        "description": "Die Eisenschmelze erstellt Stahl. Umso höher die Stufe der Eisenschmelze ist, desto mehr Stahl kann produziert werden.",
    },
    {
        "intname": "mine",
        "minlevel": 0,
        "maxlevel": 25,
        "name": "Platinmiene",
        "platinum": 4,
        "energy": 75,
        "steel": 115,
        "plasma": 5,
        "plasmid": 0,
        "food": 125,
        "farm_needed": 7,
        "build_time": 8,
        "cost_factor": 1.3,
        "time_factor": 1.25,
        "description": "In der Platinmiene wird das wertvolle Platin gefördert. Umso höher die Stufe der Platinmiene ist, desto mehr Platin kann gefördert werden.",
    },
    {
        "intname": "reactor",
        "minlevel": 0,
        "maxlevel": 25,
        "name": "Reaktor",
        "platinum": 150,
        "energy": 6,
        "steel": 175,
        "plasma": 8,
        "plasmid": 0,
        "food": 190,
        "farm_needed": 10,
        "build_time": 12,
        "cost_factor": 1.3,
        "time_factor": 1.25,
        "description": "Im Reaktor werden Plasma und Energie erzeugt. Umso höher die Stufe des Reaktors ist, desto mehr Plasma und Energie kann erzeugt werden.",
    },
    {
        "intname": "lab",
        "minlevel": 0,
        "maxlevel": 25,
        "name": "Biolabor",
        "platinum": 100,
        "energy": 75,
        "steel": 100,
        "plasma": 20,
        "plasmid": 50,
        "food": 200,
        "farm_needed": 10,
        "build_time": 10,
        "cost_factor": 1.3,
        "time_factor": 1.25,
        "description": "Im Biolabor wird das Plasmid produziert. Umso höher die Stufe des Biolabors ist, desto mehr Plasmid kann produziert werden.",
    },
    {
        "intname": "storage",
        "minlevel": 0,
        "maxlevel": 35,
        "name": "Lagerhalle",
        "platinum": 42,
        "energy": 125,
        "steel": 142,
        "plasma": 0,
        "plasmid": 0,
        "food": 150,
        "farm_needed": 0,
        "build_time": 21,
        "cost_factor": 1.2,
        "time_factor": 1.15,
        "description": "In deiner Lagerhalle kannst du deine Rohstoffe lagern. Umso höher die Stufe der Lagerhalle, desto mehr Rohstoffe kannst du Lagern.",
    },
]
