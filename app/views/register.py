from app import app, db
from flask import render_template, request, flash, redirect, url_for
from app.forms.registerform import RegisterForm
from app.models.user import User

@app.route("/register", methods=["GET", "POST"])
def register():
    form = RegisterForm()
    form.footer=form.footer1+url_for("login")+form.footer2
    if form.validate_on_submit():
        user = User(form.username.data, form.email.data, form.password.data)
        db.session.add(user)
        db.session.commit()
        flash("Account wurde erstellt. Bitte einloggen.")
        return redirect("login")
    return render_template("form.html", form=form, title="Registrierung")