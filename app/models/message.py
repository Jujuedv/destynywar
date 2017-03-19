from app import db

class Message(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    body = db.Column(db.String(2048))
    deleted = db.Column(db.Boolean, default=False)

    def __repr__(self):
        return "<Message {}>".format(self.id)
