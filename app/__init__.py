from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager
from flask_pagedown import PageDown
from flaskext.markdown import Markdown
from flask_apscheduler import APScheduler

app = Flask(__name__)
app.config.from_object("config")

pagedown = PageDown(app)
Markdown(app)

db = SQLAlchemy(app)

lm = LoginManager()
lm.init_app(app)
lm.login_view = "login"

from app.eventing import handle_events

scheduler = APScheduler()
scheduler.init_app(app)
scheduler.add_job("event_loop", handle_events, trigger="interval", seconds=1, replace_existing=True)

from app import views, models
