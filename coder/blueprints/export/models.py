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
from coder.blueprints.sitemind.models import CoderClassification
from coder.extensions import db

import sys
import random
#import hashlib
#import re
from sqlalchemy import exc

from sqlalchemy.sql import select



class Exporter():


    @classmethod 
    def save_data_to_file(cls, filename):

        # save data to csv file
        import csv
        with open('coder/exported-data.csv', 'wb') as f:
            reader = csv.reader(f)

        # Loop over all made classifications and write them along with information about sample to the csv file

        # query to get all classifications in a list and loop through the results
        db_urls = []
        for db_result in db.session.query(CoderDataSample):
            output_str = db_result.url_or_id + ',' + db_result.class_right
            db_urls.append(db_result.url_or_id)
        #db_urls_as_textstring = str(db_urls)

        print(db_urls)
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

            # FOR NOW, ALWAYS MAKE URL_OR_ID BY HASHING..
            # if url_or_id is not really valid url, use hashed article text as url_or_id
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
        with open('coder/testdata2.csv', 'wb') as f2:
            writer = csv.writer(f2)
            for n in newlist:
                sequence = {'index1': n.title, 'index2': n.url}
                sequence = [{'index1': 'ind1', 'index2': 'ind2'}]
                writer.writerow(sequence)
                #writer.writerow(sequence)


        '''

        # return list of results that are free to review for this reviewer/user
        # (can be many reviewers for one sample)



    
