from collections import namedtuple
from datetime import datetime

from math import cos, sin

import math

from sqlalchemy.exc import IntegrityError

from app import db
from app.gameconfig import production, storage_capacity, buildings
from app.models import Globalvars


class Planet(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(64))
    points = db.Column(db.Integer, default=0)  # TODO what are the actual starting points??
    x = db.Column(db.Integer)
    y = db.Column(db.Integer)

    owner_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=True)
    owner = db.relationship("User", back_populates="planets")

    res_platinum = db.Column(db.Float, default=1000)
    res_plasma = db.Column(db.Float, default=1000)
    res_energy = db.Column(db.Float, default=1000)
    res_plasmid = db.Column(db.Float, default=1000)
    res_food = db.Column(db.Float, default=1000)
    res_steel = db.Column(db.Float, default=1000)

    build_senate = db.Column(db.Integer, default=1)
    build_spaceport = db.Column(db.Integer, default=0)
    build_shield = db.Column(db.Integer, default=0)
    build_farm = db.Column(db.Integer, default=0)
    build_market = db.Column(db.Integer, default=0)
    build_smeltery = db.Column(db.Integer, default=0)
    build_mine = db.Column(db.Integer, default=0)
    build_reactor = db.Column(db.Integer, default=0)
    build_lab = db.Column(db.Integer, default=0)
    build_storage = db.Column(db.Integer, default=0)

    unit_interceptor = db.Column(db.Integer, default=0)
    unit_technician = db.Column(db.Integer, default=0)
    unit_jet = db.Column(db.Integer, default=0)
    unit_soldier = db.Column(db.Integer, default=0)
    unit_drone = db.Column(db.Integer, default=0)
    unit_cruiser = db.Column(db.Integer, default=0)

    settings_plasmatoenergy = db.Column(db.Float, default=0.5)

    lastupdate = db.Column(db.DateTime)

    __table_args__ = (db.UniqueConstraint('x', 'y', name='__coord_uc'),
                      )

    def __init__(self, x, y, owner=None, name="Alienplanet"):
        self.x = x
        self.y = y
        self.name = name
        self.owner = owner
        self.lastupdate = datetime.now()

    def get_res_db_handles(self):
        return {"platinum": self.res_platinum,
                "plasma": self.res_plasma,
                "energy": self.res_energy,
                "plasmid": self.res_plasmid,
                "food": self.res_food,
                "steel": self.res_steel,
                }

    def set_res_db_handles(self, res):
        self.res_platinum = res["platinum"]
        self.res_plasma = res["plasma"]
        self.res_energy = res["energy"]
        self.res_plasmid = res["plasmid"]
        self.res_food = res["food"]
        self.res_steel = res["steel"]

    def get_build_db_handles(self):
        return {"senate": self.build_senate,
                "spaceport": self.build_spaceport,
                "shield": self.build_shield,
                "farm": self.build_farm,
                "market": self.build_market,
                "smeltery": self.build_smeltery,
                "mine": self.build_mine,
                "reactor": self.build_reactor,
                "lab": self.build_lab,
                "storage": self.build_storage,
                }

    def set_build_db_handles(self, builds):
        self.build_senate = builds["senate"]
        self.build_spaceport = builds["spaceport"]
        self.build_shield = builds["shield"]
        self.build_farm = builds["farm"]
        self.build_market = builds["market"]
        self.build_smeltery = builds["smeltery"]
        self.build_mine = builds["mine"]
        self.build_reactor = builds["reactor"]
        self.build_lab = builds["lab"]
        self.build_storage = builds["storage"]

    def get_production(self):
        return {"platinum": production[self.build_mine],
                "plasma": int(production[self.build_reactor] * self.settings_plasmatoenergy),
                "energy": int(production[self.build_reactor] * (1 - self.settings_plasmatoenergy)),
                "plasmid": production[self.build_lab],
                "food": production[self.build_farm],
                "steel": production[self.build_smeltery],
                }

    def get_ressources(self, time):
        ret = self.get_res_db_handles()
        if time >= self.lastupdate:
            prod = self.get_production()
            for res in prod:
                ret[res] += (time - self.lastupdate).total_seconds() * prod[res]
        for res in prod:
            ret[res] = max(0, min(storage_capacity[self.build_storage], ret[res]))
        return ret

    def get_buildings(self):
        return self.get_build_db_handles()

    def get_buildings_plus(self):
        from app.models import Event

        ret = {"senate": 0,
               "spaceport": 0,
               "shield": 0,
               "farm": 0,
               "market": 0,
               "smeltery": 0,
               "mine": 0,
               "reactor": 0,
               "lab": 0,
               "storage": 0,
               }
        building_events = Event.query.filter_by(planet=self, type=Event.type_build).all()
        for e in building_events:
            ret[e.building] += 1
        return ret

    def update_ressources(self, time):
        if time <= self.lastupdate:
            return
        self.set_res_db_handles(self.get_ressources(time))
        self.lastupdate = time
        db.session.commit()

    def change_building(self, build, amount, time):
        self.update_ressources(time)
        builds = self.get_build_db_handles()
        builds[build] += amount
        self.set_build_db_handles(builds)
        db.session.commit()

    def get_ressource_data(self, time):
        res = self.get_ressources(time)
        prod = self.get_production()
        Resource = namedtuple("Resource", ["name", "internalname", "amount", "production", "description"])
        return (
            Resource(name="Platin",
                     internalname="platinum",
                     amount=res["platinum"],
                     production=prod["platinum"],
                     description=""),
            Resource(name="Plasma",
                     internalname="plasma",
                     amount=res["plasma"],
                     production=prod["plasma"],
                     description=""),
            Resource(name="Energie",
                     internalname="energy",
                     amount=res["energy"],
                     production=prod["energy"],
                     description=""),
            Resource(name="Plasmid",
                     internalname="plasmid",
                     amount=res["plasmid"],
                     production=prod["plasmid"],
                     description=""),
            Resource(name="Stahl",
                     internalname="steel",
                     amount=res["steel"],
                     production=prod["steel"],
                     description=""),
            Resource(name="Nahrung",
                     internalname="food",
                     amount=res["food"],
                     production=prod["food"],
                     description=""),
        )

    def get_single_building_data(self, building, build, build_plus):
        Building = namedtuple("Building",
                              ["name", "internalname", "level", "level_plus", "minlevel", "maxlevel", "description",
                               "platinum", "energy", "steel", "plasma", "plasmid", "food", "farm_needed", "build_time"])

        for data in buildings:
            if data["intname"] == building:
                level = build[data["intname"]]
                level_plus = build_plus[data["intname"]]
                return Building(
                    name=data["name"],
                    internalname=data["intname"],
                    minlevel=data["minlevel"],
                    maxlevel=data["maxlevel"],
                    level=level,
                    level_plus=level_plus,
                    platinum=int(data["platinum"] * pow(data["cost_factor"], level + level_plus + 1)),
                    energy=int(data["energy"] * pow(data["cost_factor"], level + level_plus + 1)),
                    steel=int(data["steel"] * pow(data["cost_factor"], level + level_plus + 1)),
                    plasma=int(data["plasma"] * pow(data["cost_factor"], level + level_plus + 1)),
                    plasmid=int(data["plasmid"] * pow(data["cost_factor"], level + level_plus + 1)),
                    food=int(data["food"] * pow(data["cost_factor"], level + level_plus + 1)),
                    farm_needed=int(data["farm_needed"] * pow(data["cost_factor"], level + level_plus + 1)),
                    build_time=int(data["build_time"] * pow(data["time_factor"], level + level_plus + 1)),
                    description=data["description"],
                )
        raise LookupError()

    def get_buildings_data(self):
        build = self.get_buildings()
        build_plus = self.get_buildings_plus()

        return (
            self.get_single_building_data("senate", build, build_plus),
            self.get_single_building_data("spaceport", build, build_plus),
            self.get_single_building_data("shield", build, build_plus),
            self.get_single_building_data("farm", build, build_plus),
            self.get_single_building_data("market", build, build_plus),
            self.get_single_building_data("smeltery", build, build_plus),
            self.get_single_building_data("mine", build, build_plus),
            self.get_single_building_data("reactor", build, build_plus),
            self.get_single_building_data("lab", build, build_plus),
            self.get_single_building_data("storage", build, build_plus),
        )

    @staticmethod
    def create_planet(owner=None, name="Alienplanet"):
        # coord calculation just taken from old version, yes radians and degrees are wrongly mixed here
        vars = Globalvars.get()
        if vars.planet_rot >= 360:
            vars.planet_rot -= 360
            vars.planet_dist += 1
        vars.planet_rot += 360 / ((vars.planet_dist + 1) * math.pi) * 4
        db.session.commit()

        x = round(cos(vars.planet_rot) * vars.planet_dist)
        y = round(sin(vars.planet_rot) * vars.planet_dist)
        p = Planet(x, y, owner, name)
        try:
            db.session.add(p)
            db.session.commit()
            if p.id % 3 == 0:
                Planet.create_planet()
            return p
        except IntegrityError:
            db.session.rollback()
            return Planet.create_planet(owner, name)

    @staticmethod
    def by_id(id):
        return Planet.query.filter_by(id=id).first()

    def __repr__(self):
        return "<Planet {}({}|{}): {}>".format(self.id, self.x, self.y, self.name)
