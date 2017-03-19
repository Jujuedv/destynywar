from flask_wtf import FlaskForm
from wtforms import StringField, SubmitField, TextAreaField
from wtforms.validators import DataRequired, ValidationError
from app.models.user import User




class HolopostRespondForm(FlaskForm):

    body = TextAreaField("Nachricht")
    submit = SubmitField("Senden", description="Senden!")

    def __init__(self, *args, **kwargs):
        FlaskForm.__init__(self, *args, **kwargs)

