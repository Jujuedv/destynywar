from app import app, db
from flask import render_template, request, flash
from app.forms.registerform import RegisterForm
from app.models.user import User

@app.route("/register", methods=["GET", "POST"])
def register():
    form = RegisterForm(request.form)
    print(form.validate_on_submit())
    if form.validate_on_submit():
        print("TEST")
        user = User(form.username.data, form.email.data, form.password.data)
        db.session.add(user)
        db.session.commit()
        flash("Account wurde erstellt. Bitte einloggen.")
        return form.redirect("login")
    return render_template("register.html", form=form)