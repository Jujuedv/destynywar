from app import db

alliance_association_table = db.Table('alliance_association', db.Model.metadata,
                                     db.Column('alliance_id', db.Integer, db.ForeignKey('alliance.id')),
                                     db.Column('user_id', db.Integer, db.ForeignKey('user.id'))
                                     )

class Alliance(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    owner_id = db.Column(db.Integer, db.ForeignKey("user.id"))
    name = db.Column(db.String(256))
    description = db.Column(db.String(4096))

    members = db.relationship("User", back_populates="alliance_member", lazy='dynamic', secondary=alliance_association_table)

    def __repr__(self):
        return "<Alliance {}>".format(self.name)
    
