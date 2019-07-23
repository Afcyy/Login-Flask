
from flask import Flask, url_for, render_template, request, redirect, session, flash
from flask_sqlalchemy import SQLAlchemy

class CustomFlask(Flask):
    jinja_options = Flask.jinja_options.copy()
    jinja_options.update(dict(
    block_start_string='(%',
    block_end_string='%)',
    variable_start_string='((',
    variable_end_string='))',
    comment_start_string='(#',
    comment_end_string='#)',
  ))


app = CustomFlask(__name__)
app.config['SQLALCHEMY_DATABASE_URI'] = 'sqlite:///database.db'
db = SQLAlchemy(app)


class User(db.Model):
	id = db.Column(db.Integer, primary_key=True)
	name = db.Column(db.String(80), unique=True)
	password = db.Column(db.String(80))
	address = db.Column(db.String(80))
	number = db.Column(db.Integer())

	def __init__(self, name, password, address, number):
		self.name = name
		self.password = password
		self.address = address
		self.number = number



@app.route('/welcome', methods=['GET', 'POST'])
def home():
	if not session.get('logged_in'):
		return render_template('index.html')
	else:
		if request.method == 'POST':
			name = request.form['name']
			return render_template('index.html')
		return render_template('index.html')


@app.route('/', methods=['GET', 'POST'])
def login():
	if request.method == 'GET':
		return render_template('login.html')
	else:
		name = request.form['name']
		password = request.form['password']
		data = User.query.filter_by(name=name, password=password).first()
		if data is not None:
			session['logged_in'] = True
			return redirect(url_for('home'))
		else:
			flash("არასწორი მომხარებლის სახელი ან პაროლი!")
			return redirect(url_for('login'))


@app.route('/register', methods=['GET', 'POST'])
def register():
	if request.method == 'POST':
		new_user = User(name=request.form['name'], password=request.form['password'], address=request.form['address'], number=request.form['phonenumber'])
		db.session.add(new_user)
		db.session.commit()
		return render_template('login.html')
	return render_template('register.html')

@app.route("/logout")
def logout():
	"""Logout Form"""
	session['logged_in'] = False
	return redirect(url_for('home'))


if __name__ == '__main__':
	app.debug = True
	db.create_all()
	app.secret_key = "123"
	app.run(host='0.0.0.0')
	