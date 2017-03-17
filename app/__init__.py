from flask import Flask
from flask_sqlalchemy import SQLAlchemy
from flask_login import LoginManager
from flask_pagedown import PageDown
from flaskext.markdown import Markdown
from flask_socketio import SocketIO

app = Flask(__name__)
app.config.from_object("config")

pagedown = PageDown(app)
Markdown(app)

db = SQLAlchemy(app)

lm = LoginManager()
lm.init_app(app)
lm.login_view = "login"

socketio = SocketIO(app)
socketio.run(app, debug=True)

from app import views, models