from flask_wtf import Form
from wtforms import HiddenField, StringField, PasswordField, SelectField
from wtforms.validators import DataRequired, Length, Optional, Regexp
from wtforms_components import EmailField, Email, Unique

from config.settings import LANGUAGES
from lib.util_wtforms import ModelForm, choices_from_dict
from snakeeyes.blueprints.user.models import User, db
from snakeeyes.blueprints.user.validations import ensure_identity_exists, \
    ensure_existing_password_matches

from flask_wtf import Form
from wtforms import TextAreaField, BooleanField
from wtforms_components import EmailField
from wtforms.validators import DataRequired, Length



class CoderForm(Form):
    checkbox1 = BooleanField(label='LEFT',
        validators=[],
        default=False,
        description="check this if sample suits this category"
    )

    checkbox2 = BooleanField(label='RIGHT',
        validators=[],
        default=False,
        description="check this if sample suits this category"
    )

    checkbox3 = BooleanField(label='POSITIVE',
        validators=[],
        default=False,
        description="check this if sample suits this category"
    )

    checkbox4 = BooleanField(label='NEGATIVE',
        validators=[],
        default=False,
        description="check this if sample suits this category"
    )

    checkbox5 = BooleanField(label='FUNCTIONAL',
        validators=[],
        default=False,
        description="check this if sample suits this category"
    )


#    email = EmailField("What's your e-mail address?",
#                       [DataRequired(), Length(3, 254)])
#    message = TextAreaField("What's your question or issue?",
#                            [DataRequired(), Length(1, 8192)])
    
    message = TextAreaField("Notes",
                            [Optional(), Length(1, 8192)])
#                            [DataRequired(), Length(1, 8192)])
