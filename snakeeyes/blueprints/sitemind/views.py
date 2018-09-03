from flask import (
    Blueprint,
    redirect,
    request,
    flash,
    url_for,
    render_template)

from flask_login import login_required, current_user

from snakeeyes.blueprints.sitemind.forms import CoderForm
from snakeeyes.blueprints.user.models import db

from snakeeyes.blueprints.sitemind.models import CoderClassification
from snakeeyes.blueprints.datahandling.models import CoderDataSample

sitemind = Blueprint('sitemind', __name__, template_folder='templates')

@sitemind.route('/sitemind', methods=['GET', 'POST'])
@login_required
def index():

    # Fetch info for random sample
    data = CoderDataSample().get_random_sample()
    
    # Pre-populate the email field with the user's email and other fields according to the sample
    #form = CoderForm(obj=current_user, checkbox1 = True)
    form = CoderForm()

    user_text = current_user.email
    if(current_user.name):
        user_text = current_user.name

    c = CoderClassification()

    if form.validate_on_submit():
        c.user_id = current_user.id
        c.text = request.form.get('message')
        c.class_right = (request.form.get('checkbox1') == 'on')
        c.class_left = (request.form.get('checkbox2') == 'on')
        c.class_postivite = (request.form.get('checkbox3') == 'on')
        c.class_negative = (request.form.get('checkbox4') == 'y')
        c.class_functional = (request.form.get('checkbox5') == 'y')
        db.session.add(c)
        db.session.commit()
        # flash('Success will be yours! Care to classify another?', 'success')
        #return render_template('sitemind/selections.html', form=form, email=user_text, test=end_text)
        return redirect(url_for('sitemind.index'))

    return render_template('sitemind/selections.html', form=form, email=user_text, title = data.title, text=data.text)

#    c = Coder()
#    c.title = '-test-title-'
#    c.url = 'test-url'
    #db.session.add(c)
    #db.session.commit()
#    c.save()

    #print("Hello  !!")
    '''
        form.populate_obj(u)
        u.password = User.encrypt_password(request.form.get('password'))
        u.save()
        '''

    return render_template('sitemind/selections.html')

'''
@user.route('/settings/update_credentials', methods=['GET', 'POST'])
@login_required
def update_credentials():
    form = UpdateCredentialsForm(current_user, uid=current_user.id)

    if form.validate_on_submit():
        new_password = request.form.get('password', '')
        current_user.email = request.form.get('email')

        if new_password:
            current_user.password = User.encrypt_password(new_password)

        current_user.save()

        flash('Your sign in settings have been updated.', 'success')
        return redirect(url_for('user.settings'))

    return render_template('user/update_credentials.html', form=form)

'''