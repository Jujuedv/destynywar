

from datetime import datetime

from app.models import *
from config import DEBUG_EVENTS


def handle_build_event(e):
    if DEBUG_EVENTS:
        print("build: ", e, e.building, e.planet)
    e.planet.change_building(e.building, 1, e.time)
    e.remove()

def handle_events():
    if DEBUG_EVENTS:
        print("starting event handling")
    events = Event.get_events_until(datetime.now())
    for e in events:
        if int(e.type) == Event.type_build:
            handle_build_event(e)
