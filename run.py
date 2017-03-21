#!/bin/python3

if __name__ == '__main__':
    try:
        from app import app, scheduler

        scheduler.start()
        app.run(debug=True, use_reloader=False)
    except:
        scheduler.shutdown(wait=True)
        raise
