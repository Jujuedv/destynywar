from app import db

class Globalvars(db.Model):
    __tablename__ = "globalvars"
    id = db.Column(db.Integer, primary_key=True)

    planet_rot = db.Column(db.Float, default=0)
    planet_dist = db.Column(db.Integer, default=0)

    def __repr__(self):
        return "<Globalvars: {} {}>".format(self.planet_rot, self.planet_dist)

    @classmethod
    def get(cls):
        if Globalvars.query.count() == 0:
            db.session.add(Globalvars())
        return Globalvars.query.first()