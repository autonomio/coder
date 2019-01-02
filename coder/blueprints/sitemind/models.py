import datetime
from collections import OrderedDict
from hashlib import md5


import pytz
from flask import current_app
from sqlalchemy import or_
from werkzeug.security import generate_password_hash, check_password_hash

from flask_login import UserMixin

from itsdangerous import URLSafeTimedSerializer, \
    TimedJSONWebSignatureSerializer

from lib.util_sqlalchemy import ResourceMixin, AwareDateTime
from coder.blueprints.billing.models.credit_card import CreditCard
from coder.blueprints.billing.models.subscription import Subscription
from coder.blueprints.billing.models.invoice import Invoice
from coder.extensions import db



# data model for classification sample
class CoderClassification(ResourceMixin, db.Model):
    __tablename__ = 'classifications'
    id = db.Column(db.Integer, primary_key=True)

    # Relationships.
    
    coderdatasample_id = db.Column(db.Integer, db.ForeignKey('learning_samples.id',
                                                  onupdate='CASCADE',
                                                  ondelete='CASCADE'),
                        index=True, nullable=True)
    

    class_right = db.Column(db.Boolean(), nullable=False, server_default='0') #right
    class_left = db.Column(db.Boolean(), nullable=False, server_default='0') #left
    class_positive = db.Column(db.Boolean(), nullable=False, server_default='0') #positive
    class_negative = db.Column(db.Boolean(), nullable=False, server_default='0') #negative
    class_functional = db.Column(db.Boolean(), nullable=False, server_default='0') #functional

    user_email = db.Column(db.Boolean(), nullable=False, server_default='0') #reduntant, because just to make sure..
    base_classification = db.Column(db.Integer, db.ForeignKey('classifications.id',
                                                  onupdate='CASCADE',
                                                  ondelete='CASCADE'),
                        index=True, nullable=True)

    classification_order = db.Column(db.Integer, server_default='0') #functional


    user_id = db.Column(db.Integer, db.ForeignKey('users.id',
                                                  onupdate='CASCADE',
                                                  ondelete='CASCADE'),
                        index=True, nullable=False)


    #classification table will have following columns:
    # user_ind : references the user who made the classification, can be also model!
    # boolean1..boolean10 : 
    # list of class names in order
    # sample_ind : reference to the sample
    # sequence_order : for simplicity, the number in sequence of classifications for this classification

    # storing of "reduntant" data is meant to make life easier: it is easy to check everything 
    # directly from one table, but also make calculations about boolean values etc.

    def __init__(self, **kwargs):
        # Call Flask-SQLAlchemy's constructor.
        super(CoderClassification, self).__init__(**kwargs)
        self.title = 'test title'
        self.url = 'test url'



