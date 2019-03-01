const fs = require('fs');

function data(filename) {
    return (req, res) => res.send(fs.readFileSync(`./data/${filename}`, 'utf8'));
}

module.exports = function(app) {
    app.get('/api/form/:id', data('form.json'));
};