from flask_wtf import FlaskForm
from wtforms import SubmitField, IntegerField


class ResourcesForm(FlaskForm):

    platin = IntegerField("Platin")
    plasma = IntegerField("Plasma")
    energie = IntegerField("Energie")
    plasmid = IntegerField("Plasmid")
    stahl = IntegerField("Stahl")
    nahrung = IntegerField("Nahrung")

    submit = SubmitField("Senden", description="Senden!")

    def __init__(self, *args, **kwargs):
        FlaskForm.__init__(self, *args, **kwargs)
        self.resource_fields = {
            "Platin": self.platin,
            "Plasma": self.plasma,
            "Energie": self.energie,
            "Plasmid": self.plasmid,
            "Stahl": self.stahl,
            "Nahrung": self.nahrung
        }

