const axios = require('axios');
const FormData = require('form-data');
const fs = require('fs');

const API_ENDPOINT = 'https://api-app.xtracta.com/v1/documents/upload';
const API_KEY = 'YOUR_API_KEY';
const WORKFLOW_ID = 'YOUR_WORKFLOW_ID';
const FILE_PATH = 'FULL_PATH_TO_YOUR_FILE';
const FILE_NAME = 'YOUR_FILE_NAME';

const file = fs.readFileSync(FILE_PATH);

const formData = new FormData();
formData.append('api_key', API_KEY);
formData.append('workflow_id', WORKFLOW_ID);
formData.append('userfile', file, FILE_NAME);

console.info('Starting the upload...');
axios.post(API_ENDPOINT, formData, {
    headers: {
        ...formData.getHeaders(),
        'accept': 'application/json', // application/xml to get XML
    }
}).then((response) => {
    console.info(response.data);
}).catch((error) => {
    console.error(error);
});

