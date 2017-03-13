from flask import render_template
from app import app

@app.errorhandler(403)
def error_forbidden(e):
    return render_template("403.html", title="VERBOTEN!"), 403

@app.errorhandler(404)
def error_not_found(e):
    return render_template("404.html", title="Nicht gefunden!"), 404