from app import db
from flask import g

class Holopost_Betreffs(db.Model):
    __tablename__ = "holopost_betreffs"
    id = db.Column(db.Integer, primary_key=True)

    from_id = db.Column(db.Integer, db.ForeignKey("user.id"))
    to_id = db.Column(db.Integer, db.ForeignKey("user.id"))

    lastedit = db.Column(db.DateTime)

    del_from = db.Column(db.Boolean, default=False)
    del_to = db.Column(db.Boolean, default=False)

    read_from = db.Column(db.Boolean, default=False)
    read_to = db.Column(db.Boolean, default=False)

    betreff = db.Column(db.String)

    nachrichten = db.relationship("Holopost_Nachrichten", backref="betreff", lazy="dynamic", foreign_keys="Holopost_Nachrichten.holopost_id")

    def can_read(self, user):
        if user == self.sender or user == self.receiver:
            return True
        return False

    def is_read(self, user):
        if user == self.sender and self.read_from:
            return True
        elif user == self.receiver and self.read_to:
            return True
        else:
            return False

    def partner(self):
        if g.user == self.sender:
            return self.receiver
        else:
            return self.sender

    def set_read(self, user, value=True):
        if user == self.sender:
            self.read_from = value
        elif user == self.receiver:
            self.read_to = value

    def set_delete(self, user, value=True):
        if user == self.sender:
            self.del_from = value
        elif user == self.receiver:
            self.del_to = value

    def __repr__(self):
        return "<Holomail Betreff {}>".format(self.id)
