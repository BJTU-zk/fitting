from flask import Flask, request, flash, render_template
from flask import redirect, url_for, make_response
from pymongo import MongoClient
import datetime

app = Flask(__name__)
# 设置密匙
app.config['SECRET_KEY'] = 'hard to guess'


# 主页判断
@app.route('/')
def hello():
    if not request.cookies.get('useremail') is None:
        return redirect(url_for("index"))
    else:
        return redirect(url_for('login'))


# 真正的主页
@app.route('/index', methods=['GET', 'POST'])
def index():
    if not request.cookies.get('useremail') is None:
        name = request.cookies.get('useremail')
        return render_template('index.html', name=name, result='')
    else:
        return render_template('index.html', name=None)


@app.route('/login', methods=['GET', 'POST'])
def login():
    if not request.cookies.get('useremail') is None:
        return redirect(url_for("index"))
    if request.method == 'GET':
        return render_template("login.html")
    useremail = request.form.get('useremail')
    password = request.form.get('password')
    login_rem = request.form.get('login_rem')
    user = select_user(useremail)
    if user:
        if user['password'] == password:
            response = make_response(redirect(url_for('index')))
            if login_rem is not None:
                outdate = datetime.datetime.today() + datetime.timedelta(days=30)
                response.set_cookie('useremail', useremail, expires=outdate)
                return response
            response.set_cookie('useremail', useremail)
            return response
        else:
            flash('sorry,the password is wrong!')
    else:
        flash("sorry,user don't exist! please register one!")
    return render_template("login.html")


@app.route('/register', methods=['GET', 'POST'])
def register():
    if not request.cookies.get('useremail') is None:
        return redirect(url_for("index"))
    if request.method == 'GET':
        return render_template("register.html")
    useremail = request.form.get('useremail')
    password = request.form.get('password')
    repassword = request.form.get('sec_password')
    if not password == repassword:
        flash('two passwords are not same !')
        return render_template("register.html")
    user = select_user(useremail)
    if user:
        flash('sorry,the user has existed!')
        return render_template("register.html")
    else:
        insert_user(useremail, password)
        flash("success!")
    return redirect(url_for("login"))


@app.route('/exit', methods=['GET'])
def out():
    response = make_response(redirect(url_for("login")))
    if not request.cookies.get('useremail') is None:
        response.delete_cookie('useremail')
    return response


# Description : 连接fitting数据库 创建连接 获取集合
def create_conn(collection_name):
    conn = MongoClient('127.0.0.1', 27017)
    db = conn.fitting
    my_set = db[collection_name]
    return my_set


def insert_user(useremail, password):
    my_set = create_conn('user')
    my_set.insert({"useremail": useremail, "password": password})


def select_user(useremail):
    # 查询指定useremail的
    my_set = create_conn('user')
    result = my_set.find_one({"useremail": useremail})
    return result


if __name__ == '__main__':
    app.run(host='127.0.0.1', debug=True, port=8000)
