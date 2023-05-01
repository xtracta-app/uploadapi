#A standalone script. It's necessary to install requests This is done using pip pip install requests

import os
import requests

API_KEY = <YOUR_API_KEY> # str
WORKFLOW_ID = <YOUR_WORKFLOW_ID> # int
file_path = "testdoc.jpg"

file_name = os.path.basename(file_path)

with open(file_path, 'rb') as f:
    multipart_form_data = {
        'submit': (None, 'Submit'),
        'api_key': (None, API_KEY),
        'workflow_id': (None, WORKFLOW_ID),
        'userfile': (file_name, f),
    }

    resp = requests.post('https://api-app.xtracta.com/v1/documents/upload',
                        files=multipart_form_data)

    print("HTTP STATUS: {}: {}".format(resp.status_code, file_name))
    if(resp.status_code == 200):
        print(resp.text)
    else:
        print("ERROR")