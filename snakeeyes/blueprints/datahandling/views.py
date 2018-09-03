from flask import (
    Blueprint,
    flash,
    redirect,
    request,
    url_for,
    render_template)
from flask_login import current_user

from snakeeyes.blueprints.datahandling.forms import DatahandlingForm
from snakeeyes.blueprints.datahandling.models import CoderDataSample

datahandling = Blueprint('datahandling', __name__, template_folder='templates')


@datahandling.route('/datahandling', methods=['GET', 'POST'])
def loaddata():
    # Pre-populate the email field if the user is signed in.
    form = DatahandlingForm(obj=current_user)
    data = CoderDataSample.read_data_from_file('test')

    
    '''
    if form.validate_on_submit():
        # This prevents circular imports.
        from snakeeyes.blueprints.contact.tasks import deliver_contact_email

        deliver_contact_email.delay(request.form.get('email'),
                                    request.form.get('message'))

        flash('Thanks, expect a response shortly.', 'success')

        return redirect(url_for('contact.index'))
    '''

    return render_template('datahandling/loaddata.html', form=form)
