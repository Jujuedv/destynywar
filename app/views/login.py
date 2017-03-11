from app import app, lm
from app.models.user import User

@lm.user_loader
def load_user(id):
    return User.query.get(int(id))

@app.route("/login", methods=["GET", "POST"])
def login():
    pass