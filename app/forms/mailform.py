from flask_wtf import FlaskForm
from wtforms import StringField, SubmitField, TextAreaField
from wtforms.validators import DataRequired, ValidationError
from app.models.user import User


def validate_receiver(form, field):
    user = User.query.filter_by(username=field.data).first()
    if not user:
        raise ValidationError("User does not exist")


class MailForm(FlaskForm):

    header = """<link href="{{ url_for("static", filename="css/holomail.css") }}" rel="stylesheet">
	<section class="header">
		<h1>Holopost Schreiben</h1>
	</section>"""
    header2_1="""<section>
		<div class="div text-center">
			<a href=" """
    header2_2=""" ">Holopost schreiben</a>&nbsp;&nbsp;
			<a href=" """
    header2_3=""" ">Postausgang</a>&nbsp;&nbsp;
			<a href=" """
    header2_4=""" ">Posteingang</a>
		</div>
	</section>"""
    footer=""""""

    receiver = StringField("Empfänger", validators=[DataRequired(), validate_receiver])
    subject = StringField("Betreff", validators=[DataRequired()])
    body = TextAreaField("Nachricht")
    submit = SubmitField("Senden")

    def __init__(self, *args, **kwargs):
        FlaskForm.__init__(self, *args, **kwargs)

