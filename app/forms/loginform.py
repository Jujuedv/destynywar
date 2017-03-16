from app.forms.redirectform import RedirectForm
from wtforms import StringField, PasswordField, SubmitField
from wtforms.validators import DataRequired


class LoginForm(RedirectForm):

    username = StringField("Username", validators=[DataRequired()])
    password = PasswordField("Password", validators=[DataRequired()])
    submit = SubmitField("Login")

    def __init__(self, *args, **kwargs):
        RedirectForm.__init__(self, *args, **kwargs)
