from flask_wtf import FlaskForm
from wtforms import StringField, TextAreaField
from wtforms.validators import DataRequired


class MailForm(FlaskForm):

    receiver = StringField("Receiver", validators=[DataRequired()])
    subject = StringField("Subject", validators=[DataRequired()])
    body = TextAreaField("Body")

    def __init__(self, *args, **kwargs):
        FlaskForm.__init__(self, *args, **kwargs)
