import os

WTF_CSRF_ENABLED = True
SECRET_KEY = "TODO_DO_THIS_PROPERLY"  # TODO do this properly

basedir = os.path.abspath(os.path.dirname(__file__))

SQLALCHEMY_DATABASE_URI = "sqlite:///" + os.path.join(basedir, "app.db")
SQLALCHEMY_MIGRATE_REPO = os.path.join(basedir, "db_repository")
SQLALCHEMY_TRACK_MODIFICATIONS = False

TEMPLATES_AUTO_RELOAD = True

DEBUG_EVENTS = True