from app import db

class Holopost_Nachrichten(db.Model):
    __tablename__ = "holopost_nachrichten"
    id = db.Column(db.Integer, primary_key=True)

    holopost_id = db.Column(db.Integer, db.ForeignKey("holopost_betreffs.id"))
    user_id = db.Column(db.Integer, db.ForeignKey("user.id"))
    uhrzeit = db.Column(db.DateTime)

    message = db.Column(db.String)

    def __repr__(self):
        return "<Holomail Betreff {}>".format(self.id)
