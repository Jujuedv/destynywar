from flask_wtf import FlaskForm
from wtforms import StringField, SubmitField
from wtforms.validators import DataRequired, ValidationError
from flask_pagedown.fields import PageDownField
from app.models.user import User


def validate_receiver(form, field):
    user = User.query.filter_by(username=field.data).first()
    if not user:
        raise ValidationError("User does not exist")


class MailForm(FlaskForm):

    receiver = StringField("Receiver", validators=[DataRequired(), validate_receiver])
    subject = StringField("Subject", validators=[DataRequired()])
    body = PageDownField("Body")
    submit = SubmitField("Senden")

    def __init__(self, *args, **kwargs):
        FlaskForm.__init__(self, *args, **kwargs)

