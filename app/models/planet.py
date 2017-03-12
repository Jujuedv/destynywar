from datetime import datetime

from app import db

class Planet(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    name = db.Column(db.String(64))
    points = db.Column(db.Integer, default=0) #TODO what are the actual starting points??
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

    lastupdate = db.Column(db.DateTime, default=datetime.now)

    __table_args__ = (db.UniqueConstraint('x', 'y', name='__coord_uc'),
                      )

    def __init__(self, owner = None, name = "Alienplanet"):
        #TODO calculate x, y
        self.name = name
        self.owner = owner

    def __repr__(self):
        return "<Planet {}: {}>".format(self.id, self.name)