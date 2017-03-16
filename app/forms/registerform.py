from app.forms.redirectform import RedirectForm
from wtforms import StringField, SubmitField, PasswordField, BooleanField
from wtforms.validators import DataRequired, EqualTo, Email

class RegisterForm(RedirectForm):

    header = """<section class="header">
		<h1>Registrierung</h1>
	</section>"""
    footer1="""<section>
        Du hast schon einen Benutzernamen? Melde dich einfach auf der <a href=" """
    footer2=""" ">Startseite</a> an.
	</section>"""
    footer=""""""

    username = StringField("Username", validators=[DataRequired()])
    email = StringField("Email", validators=[Email()])
    password = PasswordField("Password", validators=[DataRequired(), EqualTo("password_rep", message="Passwortwiederholung falsch.")])
    password_rep = PasswordField("Wiederholung", validators=[DataRequired()])
    submit = SubmitField("Registrieren")

    def __init__(self, *args, **kwargs):
        RedirectForm.__init__(self, *args, **kwargs)
