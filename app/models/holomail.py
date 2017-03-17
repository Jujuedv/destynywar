from app import db

class Holomail(db.Model):
    __tablename__ = "holomail"
    id = db.Column(db.Integer, primary_key=True)
    sender_id = db.Column(db.Integer, db.ForeignKey("user.id"))
    receiver_id = db.Column(db.Integer, db.ForeignKey("user.id"))

    timestamp = db.Column(db.DateTime)

    subject = db.Column(db.String(128))
    body = db.Column(db.String(2048))

    read = db.Column(db.Boolean, default=False)

    deleted_sender = db.Column(db.Boolean, default=False)
    deleted_receiver = db.Column(db.Boolean, default=False)

    def delete(self, user):
        if user == self.sender:
            self.deleted_sender = True
        if user == self.receiver:
            self.deleted_receiver = True

    def __repr__(self):
        return "<Holomail {}>".format(self.id)
