from flask_wtf import FlaskForm
from wtforms import StringField, SubmitField
from wtforms.validators import DataRequired
from flask_pagedown.fields import PageDownField


class MailForm(FlaskForm):

    receiver = StringField("Receiver", validators=[DataRequired()])
    subject = StringField("Subject", validators=[DataRequired()])
    body = PageDownField("Body")
    submit = SubmitField("Senden")

    def __init__(self, *args, **kwargs):
        FlaskForm.__init__(self, *args, **kwargs)
