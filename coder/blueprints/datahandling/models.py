#!/usr/bin/env python
# -*- coding: utf-8 -*- 

import datetime
from collections import OrderedDict
from hashlib import md5


import pytz
from flask import current_app
from sqlalchemy import or_
from werkzeug.security import generate_password_hash, check_password_hash

from flask_login import UserMixin
from flask_login import current_user


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

'''
Class for handling data samples
 - opens and reads datafile
 - reads batch information (batchsize, overlapping percentage) from file batch-info.txt

'''


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
    # batch_number tells to which batch the sample is associated
    # association is done at the same time as samples are originally read from file to database
    batch_number= db.Column(db.Integer, index=True, nullable=False, server_default='0')
    # times_to_classify tells how many times this sample is going to be offered for classification
    # problem here is that number of those who make classifications might be changing / inaccurate
    # this doesn't matter if the samples are offered so that those which have fewest amount of
    # classifications are offered first within current batch
    times_to_classify = db.Column(db.Integer, index=True, nullable=False, server_default='0')
    user = db.relationship('User')



    @classmethod 
    def read_data_from_file(cls, filename):
        # import for filehandling
        import csv

        # read information from batch-info.csv
        batch_sizes = []
        times_to_classify_in_batch = [] 
        overlay_percentage = []
        with open('coder/batch-info.csv', 'rb') as batch_info_file:
            info_reader = csv.reader(batch_info_file)
            bfile_results = list(info_reader)
        for bfile_result in bfile_results:
            if(bfile_result[0] == 'BATCH-INFOLINE'):
                batch_sizes.append(int(bfile_result[1]))
                times_to_classify_in_batch.append(int(bfile_result[2]))
                overlay_percentage.append(int(bfile_result[3]))
                

                
        # read data from csv file
        with open('coder/testdata.csv', 'rb') as f:
            reader = csv.reader(f)
            file_results = list(reader)

        #print(file_results)
        #print("***************************************")
        # save read data to database
        # catch error, if row already exists (url exists in database already)
        c = CoderDataSample()
        

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

        # randomize batch information:
        # - initialize list for batch information that has as many items as there were file_results
        # - this can be done by appending batch information to the list according to batch-info  
        # - then randomize list order
        # - then use randomized list to mark each file_result with batch information when written to database
        # -> this way even if file_results were picked from different datasets non-randomly, they
        #    will be handled randomly

        batch_info_list = []
        sample_no = 1 # counter for current sample
        sample_no_in_this_batch = 1 
        batch_no = 0 # counter for current batch (starts from zero)
        batch_sum = batch_sizes[0]  # counter for next batch level
        for file_result in file_results:
            if(sample_no > batch_sum):  # reached the end of current batch
                batch_no += 1
                sample_no_in_this_batch = 1
                batch_sum += batch_sizes[batch_no]
            # if we are within the overlay%, mark sample's times_to_classify_in_the_batch
            # additionally mark which batch this is
            if(100*sample_no_in_this_batch <= overlay_percentage[batch_no] * batch_sizes[batch_no]):
                batch_info_list.append([batch_no, times_to_classify_in_batch[batch_no]])
            else:
                batch_info_list.append([batch_no, 1]) # not on overlay area, classified just once (= by one user)
            sample_no += 1
            sample_no_in_this_batch += 1



        # after this one can get suitable samples to user by using information:
        # - batch_no: stored in database with sample information
        # - times_to_classify_in_batch: stored in database with sample information
        # - times_classified: stored in database with sample information
        # - how many samples the user has already classified
        # the last one can be got by querying the database, number of classified samples for current user 


        # randomize the list that was formed above
        import random
        random.shuffle(batch_info_list)


        x = 0
        for file_result in file_results:
            # test to see if the url is already in the database

            url_or_id = limit_length(file_result[0], 1024)
            text = limit_length(file_result[1], 2700)
            title = limit_length(file_result[2], 1024)

            c.set_info(url_or_id, text, title, batch_info_list[x][0], batch_info_list[x][1])
            x += 1



    
    @classmethod
    def save_data_to_file(cls, filename):
        print('saving data')


    @classmethod
    def set_info(cls, url_or_id, text, title2, batch_no, times_to_classify):
        instance = cls()
        instance.url_or_id = url_or_id
        instance.text = text
        instance.title = title2
        instance.batch_number = batch_no
        instance.times_to_classify = times_to_classify
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

        # THIS IS WAY TOO SLOW CURRENTLY AND NEEDS TO BE PLANNED AND IMPLEMENTED DIFFERENTLY
        # HAVE TO USE DIFFERENT DATABASE STRUCTURE TO ENABLE FAST CHECKING "IS SAMPLE ALREADY BEEN CATEGORIZED BY THIS USER"
        # THIS CODE IS TERRIBLE AND MY ONLY EXCUSE IS THAT IT WAS WEEKEND AND THEN NEW YEARS DAY.. 8)

        #select sample that is not yet classified by this user, which has not yet been classified 
        #as many times as times_to_classify says, and which is within lowest possible batch for this user

        # select first results where samples' "times_classified" is <= than "times_to_classify"
        samples = db.session.query(CoderDataSample).\
        filter(CoderDataSample.times_classified <= CoderDataSample.times_to_classify).all()

        # next loop through results and select sample that is within lowest possible batch for this user

        #results = db.session.query(CoderDataSample).filter(CoderDataSample.times_classified<=0).all()
        selected_sample = samples[0]
        print("sample.title=" + selected_sample.title)
        lowest_batch_number = 100
        for sample in samples:
            # check if this sample has been already classified by this user
            cont=False
            # first get all the classifications done by current user
            classifications = db.session.query(CoderClassification).filter(CoderClassification.user_id == current_user.id).all()
            #raise Exception('xyz')

            # then if within these there is classification for this sample already, continue to next in samples
            for classification in classifications: # this is ugly way and shows my newbiness in queries done like above..
                if(classification.coderdatasample_id == sample.id):
                    cont=True
                    #raise Exception('xyz')

            if(cont):
                continue

            # if this sample was not yet classified by this user, further check it            
            if(sample.title):
                #if this sample is of lower batch_no than previously selected, select this
                if(sample.batch_number < lowest_batch_number):
                    lowest_batch_number = sample.batch_number
                    selected_sample = sample

        return selected_sample # this is randomized, because data sample ordering is randomized when samples are read from file

