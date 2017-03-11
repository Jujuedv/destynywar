from app import app, db
from flask import render_template, request, flash, redirect
from app.forms.registerform import RegisterForm
from app.models.user import User

@app.route("/register", methods=["GET", "POST"])
def register():
    form = RegisterForm(request.form)
    if form.validate_on_submit():
        user = User(form.username.data, form.email.data, form.password.data)
        db.session.add(user)
        db.session.commit()
        flash("Account wurde erstellt. Bitte einloggen.")
        return redirect("login")
    return render_template("register.html", form=form)