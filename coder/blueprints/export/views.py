from flask import (
    Blueprint,
    flash,
    redirect,
    request,
    url_for,
    render_template)

from flask_login import (
    login_required,
    login_user,
    current_user,
    logout_user)

from coder.blueprints.datahandling.forms import DatahandlingForm
from coder.blueprints.datahandling.models import CoderDataSample
from coder.blueprints.user.decorators import role_required

datahandling = Blueprint('datahandling', __name__, template_folder='templates')


@datahandling.route('/exportdata', methods=['GET', 'POST'])
@login_required
@role_required('admin')
def savedata():
    
    return render_template('export/index.html', form=form)
