from app import db

class Event(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    type = db.Column(db.String(64))
    time = db.Column(db.DateTime)

    type_build = 0
    building = db.Column(db.String(32))
    planet_id = db.Column(db.Integer, db.ForeignKey('planet.id'), nullable=True)
    planet = db.relationship("Planet")

    def __repr__(self):
        return "<event {}: {}>".format(self.id, self.type, self.time)

    def remove(self):
        db.session.delete(self)
        db.session.commit()

    @staticmethod
    def get_events_until(time):
        return Event.query.filter(Event.time <= time).order_by(Event.time, Event.id).all()

    @staticmethod
    def create_build_event(planet, time, building):
        e = Event()
        e.type = Event.type_build
        e.building = building
        e.planet = planet
        db.session.add(e)
        db.session.commit()