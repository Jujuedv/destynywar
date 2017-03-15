from app import db

allianz_association_table = db.Table('allianz_association', db.Model.metadata,
                                     db.Column('allianz_id', db.Integer, db.ForeignKey('allianz.id')),
                                     db.Column('user_id', db.Integer, db.ForeignKey('user.id'))
                                     )

class Allianz(db.Model):
    id = db.Column(db.Integer, primary_key=True)
    owner_id = db.Column(db.Integer, db.ForeignKey("user.id"))
    name = db.Column(db.String(256))
    description = db.Column(db.String(4096))

    members = db.relationship("User", back_populates="allianz_member", lazy='dynamic', secondary=allianz_association_table)

    def __repr__(self):
        return "<Allianz {}>".format(self.id)
    
