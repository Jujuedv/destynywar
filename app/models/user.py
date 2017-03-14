from app import db
from flask import g
from app.models.holomail import Holomail
from passlib.hash import pbkdf2_sha256

class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(64), index=True, unique=True)
    email = db.Column(db.String(128), index=True)
    password = db.Column(db.String(128))
    planets = db.relationship('Planet', back_populates='owner')

    mails_sent = db.relationship("Holomail", backref="sender", lazy="dynamic", foreign_keys="Holomail.sender_id")
    mails_received = db.relationship("Holomail", backref="receiver", lazy="dynamic", foreign_keys="Holomail.receiver_id")

    def __init__(self, username, email, password):
        self.username = username
        self.email = email
        self.password = pbkdf2_sha256.using(rounds=8000, salt_size=10).hash(password)

    @property
    def is_authenticated(self):
        return True

    @property
    def is_active(self):
        return True

    @property
    def is_anonymous(self):
        return False

    def get_id(self):
        return str(self.id)

    def validate(self, password):
        return pbkdf2_sha256.verify(password, self.password)

    def allowed_to_read(self, mail):
        if mail.sender == self or mail.receiver == self:
            return True
        return False

    def __repr__(self):
        return "<User {}>".format(self.username)

    def all_mails_read(self):
        mails = self.mails_received.order_by(Holomail.timestamp.desc())
        for i in mails:
            if not i.read:
                return False
        return True
