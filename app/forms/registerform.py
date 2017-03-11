from app.forms.redirectform import RedirectForm
from wtforms import StringField, PasswordField, BooleanField
from wtforms.validators import DataRequired, EqualTo, Email

class RegisterForm(RedirectForm):

    username = StringField("Username", validators=[DataRequired()])
    email = StringField("Email", validators=[Email()])
    password = PasswordField("Password", validators=[
        DataRequired(), EqualTo("password_rep", message="Passwortwiederholung falsch.")])
    password_rep = PasswordField("Password", validators=[DataRequired()])

    def __init__(self, *args, **kwargs):
        RedirectForm.__init__(self, *args, **kwargs)
