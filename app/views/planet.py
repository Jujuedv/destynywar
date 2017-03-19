from app import app, db
from flask import render_template, session, g
from app.models import Counter
from flask_login import login_required


@app.route('/planet/<int:id>')
@login_required
def planet_overview(id):

    if "visits" not in session:
        session["visits"] = 1
    else:
        session["visits"] += 1
    g.visits.increase()
    return render_template("index.html", visits_session=session["visits"], visits_total=g.visits.count)