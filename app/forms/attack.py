from flask_wtf import FlaskForm
from wtforms import SubmitField, IntegerField


class AttackForm(FlaskForm):

    interceptor = IntegerField("Interceptor")
    technician = IntegerField("Technician")
    jet = IntegerField("Jet")
    soldier = IntegerField("Soldier")
    drone = IntegerField("Drone")
    cruiser = IntegerField("Cruiser")

    submit = SubmitField("Senden", description="Senden!")

    def __init__(self, *args, **kwargs):
        FlaskForm.__init__(self, *args, **kwargs)
        self.unit_fields = {
            "Interceptor": self.interceptor,
            "Technician": self.technician,
            "Jet": self.jet,
            "Soldier": self.soldier,
            "Drone": self.drone,
            "Cruiser": self.cruiser
        }

