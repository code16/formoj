const data = {
    form: require('./data/form'),
};

module.exports = function(app) {
    app.get('/api/form/:id', (req, res) => {
        res.send(data.form);
    });
};