from flask import (
    Blueprint,
    redirect,
    request,
    flash,
    url_for,
    render_template)

from flask_login import login_required, current_user

from coder.blueprints.sitemind.forms import CoderForm
from coder.blueprints.user.models import db

from coder.blueprints.sitemind.models import CoderClassification
from coder.blueprints.datahandling.models import CoderDataSample

sitemind = Blueprint('sitemind', __name__, template_folder='templates')

# Form for classification by the user
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
        c.coderdatasample_id = data.id
        db.session.add(c)
        db.session.commit()
        # flash('Success will be yours! Care to classify another?', 'success')
        #return render_template('sitemind/selections.html', form=form, email=user_text, test=end_text)
        return redirect(url_for('sitemind.index'))

    return render_template('sitemind/selections.html', form=form, email=user_text, title = data.title, text=data.text)

