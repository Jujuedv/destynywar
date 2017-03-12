from app import db

class Holomail(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    sender_id = db.Column(db.Integer, db.ForeignKey("user.id"))
    receiver_id = db.Column(db.Integer, db.ForeignKey("user.id"))

    timestamp = db.Column(db.DateTime)

    subject = db.Column(db.String(128))
    body = db.Column(db.String(2048))

    read = db.Column(db.Boolean)

    def __repr__(self):
        return "<Holomail {}>".format(self.id)