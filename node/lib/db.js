var mysql = require('mysql');
var db_config = require('../config');
var c = console.log;
var db = '';
var tablepre = db_config.config['tablepre'];

db = mysql.createConnection(db_config.config);


function handleDisconnect(db) {
  db.on('error', function(err) {
    if (!err.fatal) {
      return;
    }

    if (err.code !== 'PROTOCOL_CONNECTION_LOST') {
      throw err;
    }
    console.log('Re-connecting lost mysql: ' + err.code);
    db = mysql.createConnection(db.config);
    handleDisconnect(db);
    db.connect();
  });
}
handleDisconnect(db);


db.connect(function(err) {
  if (err) throw console.log('mysql err: ' + err.code);
  console.log('   mysql connected');
});

function get_query(sql) {
	var db_query = '';

	if ( typeof sql === "object" ) {
		var n = 0;
		for (var k in sql){
			if ( typeof sql[k] === "string" ) {
				n++;
				db_query += k+"='"+sql[k]+"' AND ";
			}
		}
		db_query += ' 2 > 1';
	} else {
		db_query = sql;
	}

	return db_query;
}

exports.get_msg_list = function (sql, cb) {
	var db_query = 'SELECT * FROM ';
	var table = tablepre+'chat_msg ';
	db_query += table+' WHERE ';

	db_query += get_query(sql);
	db.query(db_query, function(err, rows) {
	  cb(rows);
	});
}

exports.del_msg = function (sql) {
	var db_query = 'DELETE FROM ';
	var table = tablepre+'chat_msg ';
	db_query += table+' WHERE ';

	db_query += get_query(sql);
	db.query(db_query, function(err, rows) {

	});
}

exports.update_msg = function (sql, values) {
	var db_query = 'UPDATE ';
	var table = tablepre+'chat_msg SET ';
	db_query += table;
	for (var k in values){
		db_query += k+"='"+values[k]+"' ";
	}
	db_query += ' WHERE ';

	db_query += get_query(sql);
	db.query(db_query, function(err, rows) {

	});
}

exports.get_store_msg = function (sql, cb) {
	var db_query = 'SELECT * FROM ';
	var table = tablepre+'store_msg ';
	db_query += table+' WHERE ';

	db_query += get_query(sql);
	db.query(db_query, function(err, rows) {
	  cb(rows);
	});
}
