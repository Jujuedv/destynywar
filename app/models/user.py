from passlib.hash import pbkdf2_sha256
from sqlalchemy.exc import IntegrityError

from app import db

class User(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    username = db.Column(db.String(64), index=True, unique=True)
    password = db.Column(db.String(128))
    planets = db.relationship('Planet', back_populates='owner')
    mails_sent = db.relationship("Holopost_Betreffs", backref="sender", lazy="dynamic", foreign_keys="Holopost_Betreffs.from_id")
    mails_received = db.relationship("Holopost_Betreffs", backref="receiver", lazy="dynamic",
                                     foreign_keys="Holopost_Betreffs.to_id")
    messages_sent = db.relationship("Holopost_Nachrichten", backref="user", lazy="dynamic", foreign_keys="Holopost_Nachrichten.user_id")

    def __init__(self, username, password):
        self.username = username
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

    def __repr__(self):
        return "<User {}>".format(self.username)

    def are_all_mails_read(self):
        for mail in self.mails_received:
            if not mail.read_to:
                return False
        for mail in self.mails_sent:
            if not mail.read_from:
                return False
        return True

    @staticmethod
    def from_username(username):
        return User.query.filter_by(username=username).first()

    @staticmethod
    def create_user(username, password):
        try:
            user = User(username, password)
            db.session.add(user)
            db.session.commit()
            return True
        except IntegrityError:
            return False
