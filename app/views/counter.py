from app import app, db
from flask import render_template, session, g
from app.models import Counter
from flask_login import login_required


@app.before_request
def init_db():
    if not Counter.query.filter_by(id="Visits").first():
        counter = Counter(id="Visits", count=0)
        db.session.add(counter)
        db.session.commit()
    g.visits = Counter.query.filter_by(id="Visits").first()


@app.route('/')
@login_required
def index():
    if "visits" not in session:
        session["visits"] = 1
    else:
        session["visits"] += 1
    g.visits.increase()
    return render_template("index.html", visits_session=session["visits"], visits_total=g.visits.count)