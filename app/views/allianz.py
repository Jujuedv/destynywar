from app import app, db
from flask import render_template, g, abort, flash, redirect, url_for
from app.models.allianz import Allianz
from app.models.user import User
from flask_login import login_required
from app.forms.allianzform import AllianzForm
from app.forms.allianzinviteform import AllianzInviteForm


@app.route("/allianz")
@login_required
def allianz():
    allianzen = Allianz.query.join(Allianz.members).filter_by(id=g.user.id).paginate(1, 10000000000, False)
    if allianzen.pages==0:
        allianzen=None
    return render_template("allianz.html", title="Allianz", allianzen=allianzen)

@app.route("/allianz/view/<int:id>")
@login_required
def allianz_detail(id):
    allianz = Allianz.query.filter_by(id=id).first()
    if allianz and allianz.owner_id==g.user.id:
        return render_template("allianz_detail.html", title="Allianz ansehen", allianz=allianz)
    else:
        return abort(403)

@app.route("/allianz/invite/<int:id>", methods=["GET", "POST"])
@login_required
def allianz_invite(id):
    allianz = Allianz.query.filter_by(id=id).first()
    if allianz and allianz.owner_id==g.user.id:
        form=AllianzInviteForm()
        if form.validate_on_submit():
            allianz.members.append(User.query.filter_by(username=form.username.data).first())
            db.session.commit()
            flash("Allianz eingeladen!")
            return redirect(url_for("allianz"))
        return render_template("allianz_invite.html", title="Allianz einladen", allianz=allianz, form=form)
    else:
        return abort(403)

@app.route("/allianz_new", methods=["GET", "POST"])
@login_required
def allianz_new():
    form=AllianzForm()
    if form.validate_on_submit():
        allianz = Allianz(owner_id=g.user.id, name=form.name.data, description=form.description.data)
        allianz.members.append(g.user)
        db.session.add(allianz)
        db.session.commit()
        flash("Allianz erstellt!")
        return redirect(url_for("allianz"))
    return render_template("allianz_new.html", title="Allianz erstellen", form=form)