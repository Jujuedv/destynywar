from app import db

class Counter(db.Model):
    id = db.Column(db.String(64), primary_key=True)
    count = db.Column(db.Integer)

    def increase(self):
        self.count += 1
        db.session.commit()

    def __repr__(self):
        return "<{}: {}>".format(self.id, self.count)