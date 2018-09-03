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
from snakeeyes.blueprints.billing.models.credit_card import CreditCard
from snakeeyes.blueprints.billing.models.subscription import Subscription
from snakeeyes.blueprints.billing.models.invoice import Invoice
from snakeeyes.blueprints.bet.models.bet import Bet
from snakeeyes.blueprints.sitemind.models import CoderClassification
from snakeeyes.extensions import db

import sys
import random
#import hashlib
#import re
from sqlalchemy import exc

from sqlalchemy.sql import select



class CoderDataSample(ResourceMixin, db.Model):
    __tablename__ = 'learning_samples'
    id = db.Column(db.Integer, primary_key=True)

    # Relationships.
    '''
    coder_id = db.Column(db.Integer, db.ForeignKey('coder.id',
                                                  onupdate='CASCADE',
                                                  ondelete='CASCADE'),
                        index=True, nullable=False)
    '''

    # input details
    url_or_id = db.Column(db.String(1027), unique=False, index=True, nullable=False)
    title = db.Column(db.String(1027), unique=True, index=False, nullable=False,
                      server_default='')
    text = db.Column(db.String(2703), unique=False, index=False, nullable=False,
                      server_default='')
    times_classified = db.Column(db.Integer, index=True, nullable=False, server_default='0')
    user_id = db.Column(db.Integer, db.ForeignKey('users.id',
                                                  onupdate='CASCADE',
                                                  ondelete='CASCADE'),
                        index=True, nullable=True)
    user = db.relationship('User')

    '''
    cursor = connnect_db()

    query = "SELECT * FROM `tbl`"

    cursor.execute(query)

    result = cursor.fetchall() //result = (1,2,3,) or  result =((1,3),(4,5),)
    '''


    # def __init__(self, **kwargs):
        # Call Flask-SQLAlchemy's constructor.
        # super(CoderData, self).__init__(**kwargs)
        # self.title = 'test title'
        # self.url = 'test url'


    @classmethod 
    def read_data_from_file(cls, filename):
        '''
        # first read existing data from database to results
        db_results = db.session.query(Coder).all()
        print("***************************************")
        for result in db_results:
            print(result)
        '''

        # read data from csv file
        import csv
        with open('snakeeyes/testdata.csv', 'rb') as f:
            reader = csv.reader(f)
            file_results = list(reader)

        #print(file_results)
        #print("***************************************")
        # save read data to database
        # catch error, if row already exists (url exists in database already)
        c = CoderDataSample()

        ''' NOT REALLY..
        # read existing data from database to results
        #db_results = db.session.query(Coder).all()
        db_urls = []
        for db_result in db.session.query(CoderDataSample):
            db_urls.append(db_result.url_or_id)
        db_urls_as_textstring = str(db_urls)
        #print(db_urls)
        #print("***")
        #print(db_result.url)
        #raise
        '''

        # in following, it is assumed that in CSV-file 
        # file_result[0] == some random thing (this was in first file, so this is for historical reasons)
        # [1] == text of the article
        # [2] == title of the article
        # [3] == url of the article (can be omnitted, was not present in first sample file)

        # function to ensure that strings are not too long for database columns
        def limit_length(string_var, string_len):
            string_var = (string_var[:string_len] + '..') if len(string_var) > string_len else string_var
            #because I'm lazy, I will remove non-ascii characters here too, instead of making other function or something..
            string_var = string_var.decode('utf8', 'ignore')
            return string_var

        for file_result in file_results:
            # test to see if the url is already in the database

            url_or_id = limit_length(file_result[0], 1024)
            text = limit_length(file_result[1], 2700)
            title = limit_length(file_result[2], 1024)

            c.set_info(url_or_id, text, title)

            ''' FOR NOW, ALWAYS MAKE URL_OR_ID BY HASHING..
            #if url_or_id is not really valid url, use hashed article text as url_or_id
            regex = re.compile(
                r'^(?:http|ftp)s?://' # http:// or https://
                r'(?:(?:[A-Z0-9](?:[A-Z0-9-]{0,61}[A-Z0-9])?\.)+(?:[A-Z]{2,6}\.?|[A-Z0-9-]{2,}\.?)|' #domain...
                r'localhost|' #localhost...
                r'\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})' # ...or ip
                r'(?::\d+)?' # optional port
                r'(?:/?|[/?]\S+)$', re.IGNORECASE)
            if(re.match(regex, url_or_id) is not None):
                url_or_id = hashlib.md5(text).hexdigest()[:9]
            '''
            ''' FORGET HASHING AT ALL, LE'S JUST USE TITLE AS UNIQUE IDENTIFIER = NO SAME TITLES IN SAMPLES
            #hash text of the article to BIGINT
            h = text.encode('ascii', errors='ignore')

            n = int(hashlib.sha1(h).hexdigest(), 16) % (10 ** 16)
            #before saving to database, check that sample isn't there already
            # *todo* THIS SHOULD MAKE DATABASE CALL, OTHERWISE MULTIPLE SAME SAMPLES IN THE FILE WILL CRASH
            if(not url_or_id in db_urls_as_textstring):
                c.set_info(n, text, title)
            '''

        ''' OLD code, merging lists
        newlist=[]
        for result in db_results:
            newlist.append(result)
        for file_result in file_results:
            if file_result not in newlist: #make comparison of url here
                newlist.append(file_result)
        newlist.sort()
        print(newlist)
        '''


        # oikeastaan nain: luetaan file ja tallennetaan siina olevat rivit tietokantaan,
        # jos url ei jo ole tietokannassa
        # FUTURE: jos url on tietokannassa, niin verrataan myos title ja annetaan varoitus jos eri title
        # voidaan myos kertoa monta rivia kaikista tiedoston riveista oli jo kannassa..

        '''
        #save new list as new cvs file for easier testing..
        with open('snakeeyes/testdata2.csv', 'wb') as f2:
            writer = csv.writer(f2)
            for n in newlist:
                sequence = {'index1': n.title, 'index2': n.url}
                sequence = [{'index1': 'ind1', 'index2': 'ind2'}]
                writer.writerow(sequence)
                #writer.writerow(sequence)


        '''

        # return list of results that are free to review for this reviewer/user
        # (can be many reviewers for one sample)



    
    @classmethod
    def save_data_to_file(cls, filename):
        print('saving data')

    '''
    __tablename__ = 'learning_inputs'
    id = db.Column(db.Integer, primary_key=True)

    # Relationships.
    ''
    coder_id = db.Column(db.Integer, db.ForeignKey('coder.id',
                                                  onupdate='CASCADE',
                                                  ondelete='CASCADE'),
                        index=True, nullable=False)
    ''

    # input details
    title = db.Column(db.String(1024), unique=True, index=True, nullable=False,
                      server_default='')
    url = db.Column(db.String(1024), unique=True, index=True, nullable=False,
                      server_default='')

    def __init__(self, **kwargs):
        # Call Flask-SQLAlchemy's constructor.
        super(Coder, self).__init__(**kwargs)
        self.title = 'test title'
        self.url = 'test url'


    @classmethod
    def set_title(cls, title):
        instance = cls()
        instance.title = title
        instance.save()

    @classmethod
    def get_title(cls, title):
        instance = cls()
        return instance.title


    '''

    @classmethod
    def set_info(cls, url_or_id, text, title2):
        instance = cls()
        instance.url_or_id = url_or_id
        instance.text = text
        instance.title = title2
        exist_already = db.session.query(CoderDataSample).filter(CoderDataSample.title == title2).first()
        if(exist_already):
            print("*** TITLE: ", title2, ". already exist in database ***")
        else:
            instance.save()


    @classmethod
    def set_title(cls, title):
        instance = cls()
        instance.title = title
        instance.save()

    @classmethod
    def get_title(cls, title):
        instance = cls()
        return instance.title


    @classmethod
    def set_url(cls, url_or_id):
        instance = cls()
        instance.url_or_id = url_or_id
        instance.save()

    @classmethod
    def get_random_sample(cls):
        #select first all samples that have no classification yet
        results = db.session.query(CoderDataSample).filter(CoderDataSample.times_classified<=0).all()
        max_r = len(results)-1
        if(max_r < 0):
            # there are no unclassified samples left. Try to load them from the file.
            data = CoderDataSample.read_data_from_file('test')
            results = db.session.query(CoderDataSample).filter(CoderDataSample.times_classified<=0).all()
            max_r = len(results)-1
        r = random.randint(0, max_r)
        return results[r]
        #if there are no samples without classification, select samples that have only once been classified

        #if there are no samples that have been classified only once, select samples that have only twice been classified
