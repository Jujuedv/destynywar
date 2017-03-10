from app import app
from flask import render_template, session

@app.route('/')
def hello_world():
    if "visits" not in session:
        session["visits"] = 1
    else:
        session["visits"] += 1
    return render_template("index.html", visits=session["visits"])