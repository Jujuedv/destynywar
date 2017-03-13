from sqlalchemy import *
from migrate import *


from migrate.changeset import schema
pre_meta = MetaData()
post_meta = MetaData()
holomail = Table('holomail', post_meta,
    Column('id', Integer, primary_key=True, nullable=False),
    Column('sender_id', Integer),
    Column('receiver_id', Integer),
    Column('timestamp', DateTime),
    Column('subject', String(length=128)),
    Column('body', String(length=2048)),
    Column('read', Boolean),
)


def upgrade(migrate_engine):
    # Upgrade operations go here. Don't create your own engine; bind
    # migrate_engine to your metadata
    pre_meta.bind = migrate_engine
    post_meta.bind = migrate_engine
    post_meta.tables['holomail'].create()


def downgrade(migrate_engine):
    # Operations to reverse the above upgrade go here.
    pre_meta.bind = migrate_engine
    post_meta.bind = migrate_engine
    post_meta.tables['holomail'].drop()
