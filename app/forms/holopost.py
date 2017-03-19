from flask_wtf import FlaskForm
from wtforms import StringField, SubmitField, TextAreaField
from wtforms.validators import DataRequired, ValidationError
from app.models.user import User


def validate_receiver(form, field):
    user = User.query.filter_by(username=field.data).first()
    if not user:
        raise ValidationError("User does not exist")


class HolopostForm(FlaskForm):

    receiver = StringField("An", validators=[DataRequired(), validate_receiver])
    subject = StringField("Betreff", validators=[DataRequired()])
    body = TextAreaField("Nachricht")
    submit = SubmitField("Senden", description="Senden!")

    def __init__(self, *args, **kwargs):
        FlaskForm.__init__(self, *args, **kwargs)

