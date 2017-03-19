from datetime import datetime
from app import app, db
from flask import render_template, g, abort, flash, redirect, url_for
from app.models import Holopost_Betreffs, Holopost_Nachrichten
from app.models.user import User
from flask_login import login_required
from app.forms.holopost import HolopostForm
from app.forms.holopost_respond import HolopostRespondForm
from sqlalchemy import or_, and_


@app.route("/holopost", methods=["GET", "POST"])
@login_required
def holopost():
    form = HolopostForm()
    mails = Holopost_Betreffs.query.filter(or_(and_(Holopost_Betreffs.receiver == g.user, Holopost_Betreffs.del_to == False),
                                           and_(Holopost_Betreffs.sender == g.user, Holopost_Betreffs.del_from == False))).all()
    if form.validate_on_submit():
        receiver = User.query.filter_by(username=form.receiver.data).first()

        betreff = Holopost_Betreffs(receiver=receiver, sender=g.user, lastedit=datetime.now(), betreff=form.subject.data,
                                    read_from=True)
        nachricht = Holopost_Nachrichten(user=g.user, uhrzeit=datetime.now(), message=form.body.data, betreff=betreff)
        db.session.add(betreff)
        db.session.add(nachricht)
        db.session.commit()

        flash("Holopost gesendet!")

        return redirect(url_for("holopost"))

    return render_template("holopost.html", mails=mails, form=form)


@app.route("/holopost/read/<int:id>", methods=["GET", "POST"])
@login_required
def holopost_read(id=1):
    form = HolopostRespondForm()
    mail = Holopost_Betreffs.query.filter_by(id=id).first()

    mail.set_read(g.user)
    db.session.commit()

    if not mail or not mail.can_read(g.user):
        return abort(403)

    if form.validate_on_submit():
        nachricht = Holopost_Nachrichten(user=g.user, uhrzeit=datetime.now(), message=form.body.data, betreff=mail)
        mail.lastedit = datetime.now()
        mail.set_read(mail.partner(), False)
        mail.set_delete(mail.partner(), False)

        db.session.add(nachricht)
        db.session.commit()

        flash("Holopost gesendet!")

        return redirect(url_for("holopost_read", id=id))
    return render_template("holopost_read.html", mail=mail, form=form)

@app.route("/holopost/delete/read")
@login_required
def holopost_delete_read():
    for mail in g.user.mails_sent:
        if mail.is_read(g.user):
            mail.set_delete(g.user)
    for mail in g.user.mails_received:
        if mail.is_read(g.user):
            mail.set_delete(g.user)
    db.session.commit()
    return redirect(url_for("holopost"))

@app.route("/holopost/delete/<int:id>")
@login_required
def holopost_delete(id):
    mail = Holopost_Betreffs.query.filter_by(id=id).first()
    mail.set_delete(g.user)
    db.session.commit()
    return redirect(url_for("holopost"))


