from app.forms.redirectform import RedirectForm
from wtforms import StringField, PasswordField
from wtforms.validators import DataRequired

class LoginForm(RedirectForm):

    username = StringField("Username", validators=[DataRequired()])
    password = PasswordField("Password", validators=[DataRequired()])

    def __init__(self, *args, **kwargs):
        RedirectForm.__init__(self, *args, **kwargs)
