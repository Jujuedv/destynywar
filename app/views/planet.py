from app import app, db
from flask import render_template, session, g, abort, redirect, url_for
from app.models import Planet
from flask_login import login_required
from app.forms.attack import AttackForm
from app.forms.resources import ResourcesForm


@app.before_request
def before_planet():
    if g.user.is_authenticated:
        if not g.user.planets:
            Planet.create_planet(g.user, g.user.username + "'s Planet")
        g.planet = g.user.planets[0]
    else:
        g.planet = None


@app.route('/planet/<int:id>', methods=["GET", "POST"])
@app.route('/', methods=["GET", "POST"])
@login_required
def planet_overview(id=-1):

    if id==-1:
        planet = g.planet
    else:
        planet = Planet.query.filter_by(id=id).first()

    if not planet:
        abort(404)

    form_attack = AttackForm()
    form_resources = ResourcesForm()
    form_units = AttackForm()

    if form_attack.validate_on_submit():
        redirect(url_for("planet_overview", id=id))

    if form_resources.validate_on_submit():
        redirect(url_for("planet_overview", id=id))

    if form_units.validate_on_submit():
        redirect(url_for("planet_overview", id=id))

    return render_template("planet.html",
                           planet=planet,
                           form_attack=form_attack,
                           form_units=form_units,
                           form_resources=form_resources)