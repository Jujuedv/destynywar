from datetime import datetime

from math import cos, sin

import math

from app import db
from app.models import Globalvars

class Planet(db.Model):
    __tablename__ = "planet"
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(64))
    points = db.Column(db.Integer, default=0)  # TODO what are the actual starting points??
    x = db.Column(db.Integer)
    y = db.Column(db.Integer)

    owner_id = db.Column(db.Integer, db.ForeignKey('user.id'), nullable=True)
    owner = db.relationship("User", back_populates="planets")

    res_platinum = db.Column(db.Integer, default=1000000)
    res_plasma = db.Column(db.Integer, default=1000000)
    res_energy = db.Column(db.Integer, default=1000000)
    res_plasmid = db.Column(db.Integer, default=1000000)
    res_food = db.Column(db.Integer, default=1000000)
    res_steel = db.Column(db.Integer, default=1000000)

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

    settings_plasmatoenergy = db.Column(db.Float)

    lastupdate = db.Column(db.DateTime)

    __table_args__ = (db.UniqueConstraint('x', 'y', name='__coord_uc'),
                      )

    def __init__(self, owner=None, name="Alienplanet"):
        # coord calculation just taken from old version, yes radians and degrees are wrongly mixed here
        vars = Globalvars.get()
        if vars.planet_rot >= 360:
            vars.planet_rot -= 360
            vars.planet_dist += 1

        self.x = round(cos(vars.planet_rot) * vars.planet_dist)
        self.y = round(sin(vars.planet_rot) * vars.planet_dist)
        vars.planet_rot += 360 / ((vars.planet_dist + 1) * math.pi) * 4

        self.name = name
        self.owner = owner

    def __repr__(self):
        return "<Planet {}({}|{}): {}>".format(self.id, self.x, self.y, self.name)
