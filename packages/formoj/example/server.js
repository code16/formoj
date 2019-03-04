const fs = require('fs');

function data(filename) {
    return (req, res) => res.send(fs.readFileSync(`./data/${filename}`, 'utf8'));
}

module.exports = function(app) {
    app.get('/api/form/:id', data('form.json'));
    // app.post('/api/form/:id/validate/:sectionId', (req, res) => res.status(422).send({ errors: { 1:['required'] } }));
    app.post('/api/form/:id/validate/:sectionId', (req, res) => res.send('Ok'));
};